<?php

namespace RosaMolas\alumnosBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\alumnosBundle\Entity\AlumnoRepresentanteDatos;
use RosaMolas\alumnosBundle\Form\AlumnoRepresentanteDatosType;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggReps;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggRep;
use RosaMolas\alumnosBundle\Form\AlumnosTypeSimple;
use RosaMolas\alumnosBundle\Form\AlumnosTypeInscripcion;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\alumnosBundle\Entity\Alumnos;
use RosaMolas\alumnosBundle\Form\AlumnosTypeUsuario;
use RosaMolas\alumnosBundle\Form\PeriodoEscolarAlumnoType;
use RosaMolas\facturacionBundle\Entity\MontosAlumnos;
use RosaMolas\facturacionBundle\Entity\TipoFactura;
use RosaMolas\facturacionBundle\Form\TipoFacturaType;
use Symfony\Component\Validator\Constraints\Null;

class AlumnosFuncionesGenericas extends Controller
{
    public function __construct($container)
    {
        $this->container = $container;
    }
    public function crear_alumno_generico(Request $request, $remover = null, $ids_representantes=null){
        $p = New Alumnos();
        if ($ids_representantes){
            $instancias = $this->getDoctrine()
                ->getRepository('usuariosBundle:Usuarios')
                ->findBy(array('id' => $ids_representantes));
            foreach($instancias as $rep){
                $test_parent = New AlumnoRepresentanteDatos();
                $test_parent->setRepresentante($rep);
                $p->addAlumnoRepresentanteDatos($test_parent);
            }
        }
        $cursos = $this->getDoctrine()
            ->getRepository('inicialBundle:CursoSeccion')
            ->findBy(array('activo'=>true));

        $cant_seccion = [];
        foreach($cursos as $curso){
            $alumnos = $this->getDoctrine()
                ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
                ->findBy(array('cursoSeccion'=>$curso->getId(), 'activo'=>true));
            $cant_seccion[$curso->getId()] =  array('cupos'=>$curso->getCupos(), 'alumnos'=>count($alumnos));
        }
        $formulario = $this->createForm(new AlumnosTypeInscripcion('Crear Estudiante', $ids_representantes, $cant_seccion), $p);

        if($remover){
            foreach($remover as $campo){
                $formulario->remove($campo);
            }
        }
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                $cedula_alumno = '';
                $rep_ppal = 0;
                $representante_ppal = '';
                if(!$p->getCedula()){
                    $cedula_alumno = false;
                }
                foreach($p->getAlumnoRepresentanteDatos() as $alumno_rep_datos){
                    if($alumno_rep_datos->getPrincipal()==true){
                        $rep_ppal=  $rep_ppal + 1;
                        if($cedula_alumno == false){
                            $representante_ppal = $this->getDoctrine()
                                ->getRepository('usuariosBundle:Usuarios')
                                ->find($alumno_rep_datos->getRepresentante()->getId());
                        }
                    }
                }

                if($rep_ppal==0 or $rep_ppal>1){
                    $this -> get('session') -> getFlashBag() -> add(
                        'danger', 'Debe Seleccionar un Representante Principal');

                    return array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante');
                }

                if($cedula_alumno == false){
                    if($representante_ppal->getAlumnoRepresentanteDatos()){
                        $cant_alumnos_anio = 0;
                        foreach ($representante_ppal->getAlumnoRepresentanteDatos() as $alum_rep_datos) {
                            if ($alum_rep_datos->getAlumno()->getId() != $p->getId() and $alum_rep_datos->getAlumno()->getFechaNacimiento == $p->getFechaNacimiento()) {
                                $cant_alumnos_anio = $cant_alumnos_anio +1;
                            }
                        }
                        if($cant_alumnos_anio>0){
                            $p->setCedulaEstudiantil($p->getFechaNacimiento()->format('y').$representante_ppal->getCedula().$cant_alumnos_anio);
                        }
                        else{
                            $p->setCedulaEstudiantil($p->getFechaNacimiento()->format('y').$representante_ppal->getCedula());
                        }
                    }
                    else{
                        $p->setCedulaEstudiantil($p->getFechaNacimiento()->format('y').$representante_ppal->getCedula());
                    }
                }


                $p->setActivo(true);
                $periodo_activo = $this->getDoctrine()
                    ->getRepository('inicialBundle:PeriodoEscolar')
                    ->findOneBy(array('activo'=>true));

                foreach($p->getPeriodoEscolarCursoAlumno() as $periodo_alumno){
                    $periodo_alumno->setPeriodoEscolar($periodo_activo);
                    $periodo_alumno->setActivo(true);
                }

                /*if($usuario){
                    foreach($usuario as $representante){
                        $usuario_query = $this->getDoctrine()
                            ->getRepository('usuariosBundle:Usuarios')
                            ->find($representante->getId());
                        $p->addRepresentante($usuario_query);
                    }

                }*/

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

        $formulario = $this->createForm('collection', $instancias, array('type'=>new AlumnoRepresentanteDatos('Agregar Representante', $ids), 'allow_add' => true, 'allow_delete' => false,
            'by_reference' => false,'prototype' => false, 'label' => false, 'cascade_validation'=>false,
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

    public function agregar_monto_alumno(Request $request, $id_estudiante)
    {
        $p = $this->getDoctrine()
            ->getRepository('facturacionBundle:TipoFactura')
            ->findBy(array('activo' => 'true'));

        foreach ($p as $tfact) {
            foreach ($tfact->getConceptosFactura() as $con_fact) {
                $con_fact->addMontosAlumno(New MontosAlumnos());
            }
        }
        $formulario = $this->createForm('collection', $p, array('type' => new TipoFacturaType('Crear Tipo Factura', null, true), 'allow_add' => true, 'allow_delete' => false,
            'by_reference' => false, 'prototype' => false, 'label' => false, 'cascade_validation' => false,
            'error_bubbling' => false));
        $formulario->handleRequest($request);
        $estudiante = $this->getDoctrine()
            ->getRepository('alumnosBundle:Alumnos')
            ->find($id_estudiante);
        if ($request->getMethod() == 'POST') {
            if ($formulario->isValid()) {
               // $p->setActivo(true);

                foreach ($p as $tipo_factura) {
                    foreach($tipo_factura->getConceptosFactura() as $concepto_factura) {
                        foreach ($concepto_factura->getMontosAlumnos() as $monto_alumno) {
                            $monto_alumno->setConceptoFactura($concepto_factura);
                            $monto_alumno->setAlumno($estudiante);
                            $monto_alumno->setActivo(true);
                        }
                    }
                }
                $em = $this->getDoctrine()->getManager();
                foreach ($p as $tipo_factura) {
                    $em->persist($tipo_factura);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Montos particulares del estudiante creados con éxito');
                return array('alumno'=>$estudiante, 'monto_creado'=>true);
            }
            else{
                print_r('no valido');
                return array('form'=>$formulario->createView(), 'accion'=>'Crear Montos para Estudiante', 'estudiante'=>$estudiante);
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Crear Montos para Estudiante', 'estudiante'=>$estudiante);
    }
}
