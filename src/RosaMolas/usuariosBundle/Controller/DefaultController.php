<?php

namespace RosaMolas\usuariosBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\usuariosBundle\Form\UsuariosType;
use RosaMolas\usuariosBundle\Form\UsuariosTypeSimple;


class DefaultController extends Controller
{
    public function lista_usuarioAction(Request $request)
    {
        //hacer consulta simple a la bbdd

        if($request->get('_route')=='inicial_lista_representante'){
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id=5')
                ->orderBy('usuario.id', 'DESC')
                ->getQuery();
            $elemento = 'Representantes';

        }
        else{
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id!=5')
                ->orderBy('usuario.id')
                ->getQuery();
            $elemento = 'Usuarios';
        }

        $datos = $query->getArrayResult();

        return $this->render('usuariosBundle:Default:lista_usuario.html.twig', array('accion'=>$elemento, 'datos'=>$datos));
    }
    public function detalle_usuarioAction($id, $tipo, Request $request)
    {
        //hacer consulta simple a la bbdd

        if(strtolower($tipo) == 'representantes'){
            $plantilla = 'detalle_representante';
            $accion = 'Detalle Representante';
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario', 'alumnos')
                ->innerJoin('usuario.alumno', 'alumnos')
                ->where('usuario.id = :id')
                ->andWhere('usuario.activo = true')
                ->setParameter('id', $id)
                ->getQuery();

        }
        else{
            $plantilla = 'detalle_usuario';
            $accion = 'Detalle Usuario';
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->where('usuario.id = :id')
                ->andWhere('usuario.activo = true')
                ->setParameter('id', $id)
                ->getQuery();
        }

        $datos = $query->getArrayResult();

        if (!$datos)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }

        return $this->render('usuariosBundle:Default:'.$plantilla.'.html.twig', array('accion'=>$accion, 'datos'=>$datos));
    }
    public function crear_usuarioAction(Request $request)
    {
        $p = new Usuarios();
        if($request->get('_route')=='inicial_agregar_representante'){
            $formulario = $this->createForm(new UsuariosType('Crear Representante'), $p);
            $formulario -> remove('tipoUsuario');
            $formulario -> remove('principal');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('usuariosBundle:TipoUsuario')
                ->find(5);
            $p->setTipoUsuario($tipo_usuario);
            $p->setPrincipal('true');
            $elemento = 'Representante';
        }
        else{
            $formulario = $this->createForm(new UsuariosType('Crear Usuario'), $p);
            $formulario -> remove('alumno');
            $formulario -> remove('principal');
            $formulario -> remove('representanteContacto');
            $elemento = 'Usuario';
        }
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                print_r($elemento);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', $elemento.' Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_usuario'));
                }
            }
        }

        return $this->render('usuariosBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear '.$elemento));
    }

    public function crear_usuario_generico($request, $tipo)
    {
        $p = new Usuarios();
        if($tipo == 'representante'){
            $formulario = $this->createForm(new UsuariosType('Crear Representante'), $p);
            $formulario -> remove('tipoUsuario');
            $formulario -> remove('principal');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('inicialBundle:TipoUsuario')
                ->find(5);
            $p->setTipoUsuario($tipo_usuario);
            $p->setPrincipal('true');
            $elemento = 'Representante';
        }
        else{
            $formulario = $this->createForm(new UsuariosType('Crear Usuario'), $p);
            $formulario -> remove('alumno');
            $formulario -> remove('principal');
            $formulario -> remove('representanteContacto');
            $elemento = 'Usuario';
        }
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', $elemento.' Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_usuario'));
                }
            }
        }

        return $this->render('usuariosBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear '.$elemento));
    }

    public function borrar_usuarioAction($id, Request $request)
    {
        $usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:Usuarios')
            ->find($id);
        if (!$usuario)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }
        $formulario = $this->createForm(new UsuariosTypeSimple('Borrar Usuario'), $usuario);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');
        $formulario -> remove('cedula');
        $formulario -> remove('nombres');
        $formulario -> remove('apellidos');
        $formulario -> remove('fechaNacimiento');
        $formulario -> remove('direccion');
        $formulario -> remove('sexo');
        $formulario -> remove('activo');
        $formulario -> remove('guardar_crear');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
            ->createQueryBuilder('usuario')
            ->where('usuario.id = :id')
            ->andWhere('usuario.activo = true')
            ->setParameter('id', $id)
            ->getQuery();

        $datos = $query->getArrayResult();

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $usuario->setActivo(false);
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'warning', 'Usuario borrado con éxito'
                );
                return $this->redirect($this->generateUrl('inicial_lista_usuario'));

            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = 'inicial_agregar_banco';
        return $this->render('usuariosBundle:Default:borrar.html.twig', array('form'=>$formulario->createView(), 'datos'=>$datos, 'accion'=>'Borrar Usuario', 'atajo'=>$atajo));
    }
}
