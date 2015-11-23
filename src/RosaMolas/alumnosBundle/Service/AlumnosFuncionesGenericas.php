<?php

namespace RosaMolas\alumnosBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\alumnosBundle\Form\AlumnosTypeSimple;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\alumnosBundle\Entity\Alumnos;
use RosaMolas\alumnosBundle\Entity\PeriodoEscolarAlumno;
use RosaMolas\alumnosBundle\Form\AlumnosTypeUsuario;
use RosaMolas\alumnosBundle\Form\PeriodoEscolarAlumnoType;

class AlumnosFuncionesGenericas extends Controller
{
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function crear_representante_test_generico($request)
    {
        $p = new Usuarios();

        $formulario = $this->createForm(new UsuariosType('Crear Representante'), $p);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');
        $tipo_usuario = $this->getDoctrine()
            ->getRepository('usuarioslBundle:TipoUsuario')
            ->find(5);
        $p->setTipoUsuario($tipo_usuario);
        $p->setPrincipal('true');

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante Creado con éxito');

                //return $this->redirect($this->generateUrl('inicial_homepage'));
                return array('representante'=>$p);
            }
            else{
                return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
    }

    public function crear_usuario_generico($request, $tipo)
    {
        $p = new Usuarios();
        if($tipo == 'representante'){
            $formulario = $this->createForm(new UsuariosType('Crear Representante'), $p);
            $formulario -> remove('tipoUsuario');
            $formulario -> remove('principal');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('usuarioslBundle:TipoUsuario')
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
            else{
                return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
    }
    public function crear_alumnoAction(Request $request){
        $p = New Alumnos();
        $formulario = $this->createForm(new AlumnosTypeSimple('Crear Estudiante'), $p);
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Estudiante creado con éxito'
                );
                return array('alumnos'=>$p);
                //return $this->redirect($this->generateUrl('inicial_agregar_alumno'));
            }
            else{
                return array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante');
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante');
    }
}
