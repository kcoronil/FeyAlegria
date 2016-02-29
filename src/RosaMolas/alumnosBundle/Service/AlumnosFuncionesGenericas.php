<?php

namespace RosaMolas\alumnosBundle\Service;

use RosaMolas\alumnosBundle\Form\AlumnosTypeAggReps;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggRep;
use RosaMolas\alumnosBundle\Form\AlumnosTypeSimple;
use RosaMolas\alumnosBundle\Form\AlumnosTypeInscripcion;
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
    public function crear_alumno_generico(Request $request, $remover = null, $usuario = null){
        $p = New Alumnos();
        $formulario = $this->createForm(new AlumnosTypeInscripcion('Crear Estudiante'), $p);

        if($remover){
            foreach($remover as $campo){
                $formulario->remove($campo);
            }
        }
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $periodo_activo = $this->getDoctrine()
                    ->getRepository('inicialBundle:PeriodoEscolar')
                    ->findOneBy(array('activo'=>true));
                foreach($p->getPeriodoEscolarCursoAlumno() as $periodo_alumno){
                    $periodo_alumno->setPeriodoEscolar($periodo_activo);
                    $periodo_alumno->setActivo(true);
                }
                if($usuario){
                    $usuario_query = $this->getDoctrine()
                        ->getRepository('usuariosBundle:Usuarios')
                        ->find($usuario->getId());
                    $p->addUsuario($usuario_query);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Estudiante creado con éxito');

                if ($formulario->get('guardar')->isClicked()) {
                    return array('alumnos'=>$p, 'alumnos_finalizado'=>true);
                }
                if ($formulario->get('guardar_crear')->isClicked()) {
                    return array('alumnos'=>$p);
                }
                //return $this->redirect($this->generateUrl('inicial_agregar_alumno'));
            }
            else{
                return array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante');
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante');
    }
    public function agregar_representante(Request $request, $form, $instancias, $ids, $url_redireccion){


        $formulario = $this->createForm(new AlumnosTypeAggReps($ids), array('test'=>$instancias));
        $formulario -> remove('guardar_crear');
        $formulario -> remove('activo');

        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante agregado con éxito');
                return array('resulado'=>'exito', 'url'=> $url_redireccion);
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Agregar Representante a alumnno');

    }
}
