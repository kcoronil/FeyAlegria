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
    public function inscripcion_completa(Request $request)
    {
        $resultado = '';
        $session = $this->getRequest()->getSession();

        if (!$session->get('representante_inscripcion')) {
            /*$p = new Usuarios();

            $formulario_representante = $this->createForm(new UsuariosType('Crear Representante'), $p);
            $formulario_representante->remove('tipoUsuario');
            $formulario_representante->remove('principal');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('usuariosBundle:TipoUsuario')
                ->find(5);
            $p->setTipoUsuario($tipo_usuario->getId());
            $p->setPrincipal('true');

            $formulario_representante->remove('activo');
            $formulario_representante->handleRequest($request);

            if ($request->getMethod() == 'POST') {

                if ($formulario_representante->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($p);
                    $em->flush();
                    $session->set("representante_inscripcion", $p);
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Representante Creado con éxito');

                    //return $this->redirect($this->generateUrl('inicial_homepage'));
                }
            }
            $resultado = array('form' => $formulario_representante->createView(), 'accion' => 'Crear Representante');
            //return $this->render('usuariosBundle:Default:crear_usuario.html.twig', array('form' => $formulario_representante->createView(), 'accion' => 'Crear Representante'));
        */
            $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request);
            if (array_key_exists('representante', $resultado)) {
                $session->set("representante_inscripcion", $resultado['representante']);
            }
        }
        else {
            if (!$session->get('alumnos_inscripcion')) {
                $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request);
                if (array_key_exists('alumnos', $resultado)) {
                    $session->set("alumnos_inscripcion", $resultado['alumnos']);
                }
            }
        //$session = $this->getRequest()->getSession();
        //$resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request);
        return $this->render('usuariosBundle:Default:crear_usuario.html.twig', $resultado);
        //return $this->render('usuariosBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear '.$elemento));
        //$session->set("id_tipo_usuario", $user[2]['id']);
        }
    }
}
