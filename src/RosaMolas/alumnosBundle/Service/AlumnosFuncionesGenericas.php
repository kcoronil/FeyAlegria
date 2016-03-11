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
    public function crear_alumno_generico(Request $request, $remover = null, $usuario = null, $lista_id=null){
        $p = New Alumnos();

        $formulario = $this->createForm(new AlumnosTypeInscripcion('Crear Estudiante', $lista_id), $p);

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
                //print_r($p->getUsuario());
                if($usuario){
                    $usuario_query = $this->getDoctrine()
                        ->getRepository('usuariosBundle:Usuarios')
                        ->find($usuario->getId());
                    $p->addRepresentante($usuario_query);
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
    public function agregar_representante(Request $request, $id_estudiante, $ids, $url_redireccion){

        $instancias = $this->getDoctrine()
            ->getRepository('alumnosBundle:Alumnos')
            ->findBy(array('id'=>$id_estudiante));
        //print_r($instancias);

        $formulario = $this->createForm('collection', $instancias, array('type'=>new AlumnosTypeAggRep('Agregar Representante', $ids), 'allow_add' => true, 'allow_delete' => false,
            'by_reference' => false,'prototype' => false, 'label' => null, 'cascade_validation'=>false,
            'error_bubbling'=>false));
        $formulario -> remove('guardar_crear');
        $formulario -> remove('activo');

        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {
            //print_r($formulario->get(0)->getData()->getRepresentante()->get());
            if ($formulario->isValid()) {
                $i=0;
                $em = $this->getDoctrine()->getManager();
                foreach($instancias as $objeto){
                    foreach($formulario->get($i)->getData()->getRepresentante() as $representante){
                        print_r($formulario->get($i)->getData()->getRepresentante()->getNombres());

                        $objeto->addUsuario($representante);
                    }
                    $em->persist($objeto);
                    $i++;
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante agregado con éxito');
                return array('resulado'=>'exito', 'url'=> $url_redireccion, 'representantes_adic_anteriores'=>true);
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Agregar Representante a alumnno');

    }
}
