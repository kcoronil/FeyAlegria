<?php

namespace Test\inicialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Test\inicialBundle\Entity\Usuarios;
use Test\inicialBundle\Form\UsuariosType;
use Test\inicialBundle\Entity\Roles;
use Test\inicialBundle\Form\RolesType;
use Test\inicialBundle\Entity\PerfilUsuario;
use Test\inicialBundle\Form\PerfilUsuarioType;
use Test\inicialBundle\Entity\Alumnos;
use Test\inicialBundle\Form\AlumnosType;

class DefaultController extends Controller
{
    public function indexAction(request $request)
    {
        $session = $this->getRequest()->getSession();
        if($request->getMethod()=='POST'){
            $session->clear();
            $username=$request->get('usuario');
            $password= $request->get('password');
            $em = $this->getDoctrine()->getManager();
            $consulta = $em -> createQuery(
                'SELECT perfil.id as id_perfil, perfil.nombreUsuario, perfil.email, perfil.activo as perfil_activo, usuario.nombres, tipo_usuario.id as id_tipo_usuario, tipo_usuario.nombre as nombre_tipo_usuario, password.activo as pass_activo
                From inicialBundle:PerfilUsuario  perfil
                JOIN inicialBundle:Passwords password WITH perfil.id = password.usuario
                JOIN inicialBundle:Usuarios usuario WITH perfil.usuario = usuario.id
                JOIN inicialBundle:TipoUsuario tipo_usuario WITH usuario.tipoUsuario = tipo_usuario.id
                WHERE perfil.nombreUsuario = :user AND password.password = :pass'
            )->setParameter('user', $username)
                ->setParameter('pass', $password);

            /*$datos_cq = $consulta->getOneOrNullResult();
            print_r($datos_cq);
            exit;*/
            $user = $consulta->getOneOrNullResult();
            if ($user){
                $session = $request ->getSession();
                $session -> set("email", $user['email']);
                $session -> set("perfil_activo", $user['perfil_activo']);
                $session -> set("pass_activo", $user['pass_activo']);

                if($session->get('perfil_activo')==1){

                    if($session->get('pass_activo')==1 ){
                        $session -> set("id", $user['id_perfil']);
                        $session -> set("autenticado", true);
                        $session -> set("nombre_usuario", $user['nombreUsuario']);
                        $session -> set("nombres", $user['nombres']);
                        $session -> set("tipo_usuario", $user['nombre_tipo_usuario']);
                        $session -> set("id_tipo_usuario", $user['id_tipo_usuario']);
                        return $this->render('inicialBundle:Default:index.html.twig');
                    }
                    else{
                        $this -> get('session') -> getFlashBag() -> add(
                            'mensaje', 'Clave Inactiva debe actualizar su clave');
                    }
                }
                else{
                        $this -> get('session') -> getFlashBag() -> add(
                            'mensaje', 'Usuario Inactivo Contactar con el administrador del sistema');
                    return $this->render('inicialBundle:Default:index.html.twig');
                }
            }
            else{
                    $this -> get('session') -> getFlashBag() -> add(
                        'mensaje', 'Datos incorrectos'
                    );
                return $this->render('inicialBundle:Default:index.html.twig');
            }
        }
        return $this->render('inicialBundle:Default:index.html.twig');
    }
    public function logoutAction(Request $request)
    {
        $session = $request ->getSession();
        $session -> clear();
        $this -> get('session') -> getFlashBag() -> add(
            'mensaje', 'Sesion Cerrada'
        );
        return $this->redirect($this->generateUrl('inicial_homepage'));
    }

    public function lista_usuarioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $test_datos = $this->getDoctrine()
            ->getRepository('inicialBundle:Usuarios')
            ->findAll();
        $consulta = $em->createQuery(
            'SELECT usuario.id as usuario_id, tipo_usuario.id as tipo_usuario_id
                From inicialBundle:Usuarios usuario
                JOIN inicialBundle:TipoUsuario tipo_usuario WITH usuario.tipoUsuario = tipo_usuario.id
                WHERE usuario.activo = TRUE '
        );
        $datos = $consulta->getResult();
        /*$datos_test = $datos->;*/
        print_r($test_datos);
        exit;
        return $this->render('inicialBundle:Default:crear_usuario.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos));
    }

    public function crear_usuarioAction(Request $request)
    {
        $p = new Usuarios();
        $formulario = $this->createForm(new UsuariosType(), $p);
        $formulario -> remove('usuario');
        $formulario -> remove('nombreUsuario');
        $formulario -> remove('lugarNacimiento');
        $formulario -> remove('preguntaSecreta');
        $formulario -> remove('respuesta');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'mensaje', 'Usuario Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_usuario'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Usuario'));
    }

    public function crear_rolAction(Request $request)
    {
        $p = new Roles();
        $formulario = $this->createForm(new RolesType(), $p);
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'mensaje', 'Rol Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_rol'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear rol'));
    }
    public function crear_perfilAction(Request $request)
    {
        $p = new PerfilUsuario();
        $formulario = $this->createForm(new PerfilUsuarioType(),$p);
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'mensaje', 'Rol Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_perfil'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Perfil de Usuario'));
    }

    public function crear_alumnoAction(Request $request)
    {
        $p = new Alumnos();
        $formulario = $this->createForm(new AlumnosType(),$p);
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'mensaje', 'Rol Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_perfil'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Alumno'));
    }
}
