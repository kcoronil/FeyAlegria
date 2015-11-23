<?php

namespace RosaMolas\genericoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Test\inicialBundle\Entity\TrazaEventosUsuarios;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\usuariosBundle\Form\UsuariosType;
use RosaMolas\usuariosBundle\Form\UsuariosTypeSimple;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('genericoBundle:Default:index.html.twig', array('name' => $name));
    }
    public function crear_generico($request, $modelo, $formulario_base, $objeto, $accion, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos = null, $remover = null)
    {
        $p = $modelo;
        $formulario = $this->createForm($formulario_base, $p);

        if($remover){
            foreach($remover as $campo){
                $formulario->remove($campo);
            }
        }

        $formulario-> handleRequest($request);

        if($datos) {
            $query = $this->getDoctrine()->getRepository('inicialBundle:' . $objeto)
                ->createQueryBuilder(strtolower($objeto))
                ->where(strtolower($objeto) . '.activo = true')
                ->getQuery();

            $datos = $query->getArrayResult();
        }
        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', $objeto.' creado con éxito'
                );
                if(array_key_exists('guardar_crear', $formulario)){
                    if ($formulario->get('guardar')->isClicked()) {
                        return $this->redirect($this->generateUrl('inicial_homepage'));
                    }
                    if ($formulario->get('guardar_crear')->isClicked()) {
                        return $this->redirect($this->generateUrl($url_redireccion));
                    }
                }
                else {
                    return $this->redirect($this->generateUrl($url_redireccion));
                }
            }
        }
        return $this->render('inicialBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>$accion, 'url_editar'=>$url_editar,
            'url_borrar'=>$url_borrar, 'operaciones_datos'=>true));
    }

    public function editar_generico($id, $request, $formulario_base, $objeto, $accion, $url_redireccion, $plantilla, $remover = null)
    {

        $p = $this->getDoctrine()
            ->getRepository('inicialBundle:'.$objeto)
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe '.$objeto.' con este id: '.$id);
        }
        $formulario = $this->createForm($formulario_base, $p);
        $formulario -> remove('guardar_crear');
        $formulario -> remove('activo');
        if($remover){
            foreach($remover as $campo){
                $formulario->remove($campo);
            }
        }
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', $objeto.' editado con éxito'
                );
                return $this->redirect($this->generateUrl($url_redireccion));
            }
        }
        return $this->render('inicialBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(), 'accion'=>$accion));
    }

    public function borrar_generico($id, $request, $formulario_base, $objeto, $accion, $url_redireccion, $plantilla)
    {
        $p = $this->getDoctrine()
            ->getRepository('inicialBundle:'.$objeto)
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe concepto de Factura con este id: '.$id);
        }
        $formulario = $this->createForm($formulario_base, $p);
        $formulario -> remove('nombre');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('inicialBundle:'.$objeto)
            ->createQueryBuilder(strtolower($objeto))
            ->where(strtolower($objeto).'.id = :id')
            ->andWhere(strtolower($objeto).'.activo = true')
            ->setParameter('id', $id)
            ->getQuery();


        $datos = $query->getArrayResult();
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $p->setActivo('false');
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'warning', $objeto.' borrado con éxito'
                );

                return $this->redirect($this->generateUrl($url_redireccion));
            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = $url_redireccion;
        return $this->render('inicialBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>$accion, 'atajo'=>$atajo));
    }
    public function inscripcion_completaAction(Request $request)
    {
        $resultado = '';
        print_r('0<br/>');
        $session = $this->getRequest()->getSession();

        if (!$session->get('representante_inscripcion')) {
            print_r('1<br/>');
            $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request, true);
            if (array_key_exists('representante', $resultado)) {
                print_r('1-1');
                $session->set("representante_inscripcion", $resultado['representante']);
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        else {
            if (!$session->get('alumnos_finalizado')) {
                print_r('2<br/>');
                print_r($session->get('alumnos_finalizado'));
                $remover = array('usuario');
                $resultado = $this->get('alumnos_funciones_genericas')->crear_alumno_generico($request, $remover, $session->get('representante_inscripcion'));
                if (array_key_exists('alumnos', $resultado)){
                    print_r('2-1<br/>');
                    if(!$session->get('alumnos_inscripcion')){
                        $session->set("alumnos_inscripcion", array());
                    }
                    $array_alumnos = $session->get('alumnos_inscripcion');
                    array_push($array_alumnos, $resultado['alumnos']);
                    $session->set("alumnos_inscripcion",$array_alumnos);

                    //$session->set("alumnos_inscripcion", $resultado['alumnos']);
                    if(array_key_exists('alumnos_finalizado', $resultado)){
                        print_r('2-2<br/>');
                        $session->set("alumnos_finalizado", true);
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }

                }
            }
            else{
                if (!$session->get('representantes_adic_inscripcion')) {
                    print_r('3<br/>');
                    $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request);
                    if (array_key_exists('representante', $resultado)) {
                        $session->set("representantes_adic_inscripcion", $resultado['representante']);
                    }
                }
                else{
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
            }

        }
        return $this->render('genericoBundle:Default:crear_generico.html.twig', $resultado);
        //$session = $this->getRequest()->getSession();
        //$resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request);
        //return $this->render('usuariosBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear '.$elemento));
        //$session->set("id_tipo_usuario", $user[2]['id']);
    }
}
