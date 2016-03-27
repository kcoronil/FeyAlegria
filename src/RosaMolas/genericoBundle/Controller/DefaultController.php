<?php

namespace RosaMolas\genericoBundle\Controller;

use RosaMolas\alumnosBundle\Entity\Alumnos;
use RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno;
use RosaMolas\facturacionBundle\Entity\DetalleFactura;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggRep;
use RosaMolas\facturacionBundle\Entity\Factura;
use RosaMolas\facturacionBundle\Entity\MontosAlumnos;
use RosaMolas\facturacionBundle\Entity\TipoFactura;
use RosaMolas\facturacionBundle\Form\TipoFacturaType;
use RosaMolas\genericoBundle\Entity\Pagos;
use RosaMolas\genericoBundle\Entity\Parentescos;
use RosaMolas\genericoBundle\Form\PagosType;
use RosaMolas\genericoBundle\Form\ParentescosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($id, Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->createQueryBuilder('periodo_alumno')
            ->select('periodo_alumno', 'alumnos')
            ->Join('periodo_alumno.alumno', 'alumnos')
            ->Join('alumnos.representante', 'representantes')
            ->Join('representantes.representanteContacto', 'contactos')
            ->where('periodo_alumno.cursoSeccion = :id')
            ->andwhere('periodo_alumno.activo = true')
            ->andwhere('alumnos.activo = true')
            ->andwhere('representantes.activo = true')
            ->orderBy('alumnos.id', 'DESC')
            ->setParameter('id',$id)
            ->getQuery();

        $datos = $query->getResult();


        $fecha_actual = new \DateTime("now");
        $html = $this->renderView('genericoBundle:Default:index.html.twig', array('accion'=>'Listado de Alumnos', 'fecha'=>$fecha_actual, 'datos' => $datos));
        $mpdfService = $this->get('tfox.mpdfport');
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array('Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="file.pdf"'));
    }

    public function listado_alumnos_contactosAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->createQueryBuilder('periodo_alumno')
            ->select('periodo_alumno', 'alumnos')
            ->Join('periodo_alumno.alumno', 'alumnos')
            ->Join('alumnos.representante', 'representantes')
            ->Join('representantes.representanteContacto', 'contactos')
            ->where('periodo_alumno.activo = true')
            ->andwhere('alumnos.activo = true')
            ->andwhere('representantes.activo = true')
            ->orderBy('alumnos.id', 'DESC')
            ->getQuery();

        $datos = $query->getResult();


        $fecha_actual = new \DateTime("now");
        $html = $this->renderView('genericoBundle:Default:listado_alumnos_contactos.html.twig', array('accion'=>'Listado de Alumnos', 'fecha'=>$fecha_actual, 'datos' => $datos));

        $mpdfService = $this->get('tfox.mpdfport');
        $clase_mpdf = $mpdfService->getMpdf();

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array('Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="file.pdf"'));
    }

    public function recibo_pagoAction($id, Request $request)
    {
        $facturas = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->find($id);

        $pago = $this->getDoctrine()
            ->getRepository('genericoBundle:Pagos')
            ->findOneBy(array('factura'=>$id));

        $fecha_actual = new \DateTime("now");
        $html = $this->renderView('genericoBundle:Default:recibo_pago.html.twig', array('accion'=>'Listado de Alumnos', 'fecha'=>$fecha_actual, 'facturas' => $facturas, 'pago'=>$pago));
        print_r($html);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array('Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="file.pdf"'));
    }
    public function constancia_inscripcionAction($id, Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno as estudiante', 'periodo_alumno as periodo_estudiante', 'cursos as curso', 'secciones as seccion',
                'periodos as periodo', 'etapas as etapa')
            ->where('alumno.id = :id')
            ->andwhere('alumno.activo = true')
            ->innerJoin('alumnosBundle:PeriodoEscolarCursoAlumno', 'periodo_alumno', 'WITH', 'alumno.id = periodo_alumno.alumno')
            ->innerJoin('inicialBundle:PeriodoEscolar', 'periodos', 'WITH', 'periodo_alumno.periodoEscolar = periodos.id')
            ->innerJoin('inicialBundle:CursoSeccion', 'periodo_curso', 'WITH', 'periodo_alumno.cursoSeccion = periodo_curso.id')
            ->innerJoin('inicialBundle:Curso', 'cursos', 'WITH', 'periodo_curso.curso = cursos.id')
            ->innerJoin('inicialBundle:Seccion', 'secciones', 'WITH', 'periodo_curso.seccion = secciones.id')
            ->innerJoin('inicialBundle:Etapa', 'etapas', 'WITH', 'periodo_curso.etapa = etapas.id')
            ->setParameter('id',$id)
            ->getQuery();

        $datos = $query->getArrayResult();

        $fecha_nacimiento = $datos[0]['estudiante']['fechaNacimiento'];
        $fecha_actual = new \DateTime("now");
        $fecha_actual->getTimezone();
        $diff = $fecha_actual->diff($fecha_nacimiento);
        $edad = $diff->y;

        $html = $this->renderView('genericoBundle:Default:constancia_inscripcion.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos, 'edad'=>$edad, 'fecha'=>$fecha_actual));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }
    public function constancia_estudiosAction($id, Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno as estudiante', 'periodo_alumno as periodo_estudiante', 'cursos as curso', 'secciones as seccion',
                'periodos as periodo', 'etapas as etapa')
            ->where('alumno.id = :id')
            ->andwhere('alumno.activo = true')
            ->innerJoin('alumnosBundle:PeriodoEscolarCursoAlumno', 'periodo_alumno', 'WITH', 'alumno.id = periodo_alumno.alumno')
            ->innerJoin('inicialBundle:PeriodoEscolar', 'periodos', 'WITH', 'periodo_alumno.periodoEscolar = periodos.id')
            ->innerJoin('inicialBundle:CursoSeccion', 'periodo_curso', 'WITH', 'periodo_alumno.cursoSeccion = periodo_curso.id')
            ->innerJoin('inicialBundle:Curso', 'cursos', 'WITH', 'periodo_curso.curso = cursos.id')
            ->innerJoin('inicialBundle:Seccion', 'secciones', 'WITH', 'periodo_curso.seccion = secciones.id')
            ->innerJoin('inicialBundle:Etapa', 'etapas', 'WITH', 'periodo_curso.etapa = etapas.id')

            ->setParameter('id',$id)
            ->getQuery();
        $datos = $query->getArrayResult();

        $fecha_nacimiento = $datos[0]['estudiante']['fechaNacimiento'];
        $fecha_actual = new \DateTime("now");
        $fecha_actual->getTimezone();
        $diff = $fecha_actual->diff($fecha_nacimiento);
        $edad = $diff->y;

        $html = $this->renderView('genericoBundle:Default:constancia_estudios.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos, 'edad'=>$edad, 'fecha'=>$fecha_actual));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }
    public function lista_alumnoAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno.id','alumno.cedula','alumno.cedulaEstudiantil', 'alumno.primerApellido', 'alumno.primerNombre', 'alumno.fechaNacimiento', 'usuario.nombres as Nombre_Representante', 'usuario.apellidos as Apellido_Representante', 'usuario.id as usuario_id')
            ->leftJoin('alumno.representante', 'usuario')
            ->where('usuario.activo = true')
            ->where('usuario.principal = true')
            ->andwhere('alumno.activo = true')
            ->orderBy('alumno.id', 'DESC')
            ->getQuery();

        $datos = $query->getArrayResult();

        $html = $this->renderView('genericoBundle:Default:listado_alumnos.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }
    public function agregar_pagoAction($id, request $request)
    {
        $factura = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->find($id);
        $estudiante = $factura->getPeriodoEscolarCursoAlumnos()->getAlumno();
        $p = new Pagos();
        $p->setFactura($factura);
        $p->setFechaRegistro(new \DateTime("now"));
        $formulario = $this->createForm(new PagosType('Agregar Pago'), $p);
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $p->getFactura()->setPagada(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Pago creado con éxito'
                );

                if ($formulario->get('guardar')->isClicked()){
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
                if ($formulario->get('guardar_crear')->isClicked()){
                    return $this->redirect($this->generateUrl('generico_agregar_pago'));
                }
            }
        }
        return $this->render('genericoBundle:Default:agregar_pago.html.twig', array('form'=>$formulario->createView(),
            'accion'=>'Agregar Pago', 'factura'=>$factura, 'estudiante'=>$estudiante));
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


    public function crear_parentescoAction(Request $request){
        $modelo = New Parentescos();
        $form = new ParentescosType('Crear Parentesco');
        $objeto = 'Parentescos';
        $clase = 'genericoBundle:Parentescos';
        $titulo = 'Parentesco';
        $datos = 'true';
        $remover = '';
        $url_redireccion = 'generico_agregar_parentescos';
        $url_editar = 'generico_editar_parentescos';
        $url_borrar = 'generico_borrar_parentescos';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_parentescoAction($id, Request $request){
        $form = new ParentescosType('Editar Parentesco');
        $clase = 'genericoBundle:Parentescos';
        $titulo = 'Parentesco';
        $url_redireccion = 'generico_agregar_parentescos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_parentescoAction($id, Request $request){
        $form = New ParentescosType('Borrar Parentesco');
        $objeto = 'Parentescos';
        $clase = 'genericoBundle:Parentescos';
        $titulo = 'Parentesco';
        $remover = null;
        $url_redireccion = 'generico_agregar_parentescos';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }

    public function inscripcion_completaAction(Request $request)
    {
        $session = $this->getRequest()->getSession();

        if (!$session->get('representantes_finalizado')){
            $remover = null;
            $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request, false, $remover, null, 'Crear Representante');
            if (array_key_exists('representante', $resultado)) {
                if($resultado['representante']!='') {
                    if (!$session->get('representantes')) {
                        $session->set("representantes", array());
                    }
                    $array_representantes = $session->get('representantes');
                    array_push($array_representantes, $resultado['representante']);
                    $session->set("representantes", $array_representantes);
                }
                if(array_key_exists('representantes_finalizado', $resultado)){
                    $session->set("representantes_finalizado", true);
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));

                }
                else{
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
            }
        }
        else {
            if (!$session->get('alumnos_finalizado')) {
                $remover = null;
                $id_representantes = [];
                foreach($session->get('representantes') as $rep){
                    array_push($id_representantes, $rep->getId());
                }
                $resultado = $this->get('alumnos_funciones_genericas')->crear_alumno_generico($request, $remover, $id_representantes);
                if (array_key_exists('alumnos', $resultado)){
                    if(!$session->get('alumnos_inscripcion')){
                        $session->set("alumnos_inscripcion", array());
                    }

                    $array_alumnos = $session->get('alumnos_inscripcion');
                    array_push($array_alumnos, $resultado['alumnos']);
                    $session->set("alumnos_inscripcion",$array_alumnos);

                    if(array_key_exists('alumnos_finalizado', $resultado)){
                        $session->set("alumnos_finalizado", true);
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    else{
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                }
            }
            else{
                if(!$session->get('datos_facturacion')) {
                    if(!$session->get('alumnos_montos_procesados')){
                        $session->set("alumnos_montos_procesados", array());
                    }
                    $estudiante_monto = null;
                    foreach($session->get('alumnos_inscripcion') as $estudiante){
                        if ($estudiante->getTipoFacturacion()=='particular'){
                            if(!in_array($estudiante->getId(), $session->get('alumnos_montos_procesados')) ){
                                $estudiante_monto = $estudiante;
                                break;
                            }
                        }
                        else{
                            $array_alumnos_prc = $session->get('alumnos_montos_procesados');
                            array_push($array_alumnos_prc, $estudiante->getId());
                            $session->set("alumnos_montos_procesados", $array_alumnos_prc);
                        }
                    }
                    if($estudiante_monto){
                        $resultado = $this->get('alumnos_funciones_genericas')->agregar_monto_alumno($request, $estudiante_monto->getId());
                        if (array_key_exists('monto_creado', $resultado)) {
                            $array_alumnos_prc = $session->get('alumnos_montos_procesados');
                            array_push($array_alumnos_prc, $resultado['alumno']->getId());
                            $session->set("alumnos_montos_procesados", $array_alumnos_prc);
                            //return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                        }
                    }
                    if (sizeof($session->get('alumnos_inscripcion')) == sizeof($session->get('alumnos_montos_procesados'))) {
                        $session->set("datos_facturacion", true);
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }

                }
                else{
                    if (!$session->get('faturacion_finalizado')) {
                        $array_facturas=[];
                        $tipo_factura = $this->getDoctrine()->getRepository('facturacionBundle:TipoFactura')->find(1);
                        foreach($session->get('alumnos_inscripcion') as $estudiante){
                            $fact = $this->get('funciones_genericas')->crear_factura($estudiante, $tipo_factura);
                            array_push($array_facturas, $fact['factura']);
                        }
                        $session->set("facturas", $array_facturas);
                        if (sizeof($session->get('alumnos_inscripcion')) == sizeof($session->get('facturas'))) {
                            $session->set("faturacion_finalizado", true);
                            return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                        }
                    }
                    else {
                        if (!$session->get('pagos_finalizado')) {
                            foreach ($session->get('facturas') as $facturas) {
                                if ($facturas->getPagada() == false) {
                                    $factura_form = $facturas;
                                    break;
                                }
                            }
                            $resultado = $this->get('funciones_genericas')->agregar_pago_generico($factura_form->getId(), $request);
                            if (array_key_exists('pago', $resultado)) {

                                foreach ($session->get('facturas') as $fact) {
                                    if ($fact->getId() == $resultado['factura']->getId()) {
                                        $fact->setPagada(true);
                                    }
                                }
                                if (!$session->get('pagos')) {
                                    $session->set("pagos", array());
                                }
                                $array_pagos = $session->get('pagos');
                                array_push($array_pagos, $resultado['pago']);
                                $session->set("pagos", $array_pagos);
                                if (sizeof($session->get('facturas')) == sizeof($session->get('pagos'))) {
                                    $session->set("pagos_finalizado", true);
                                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                                }
                                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                            }
                        }
                        else {
                            if(!$session->get('resumen_visto')){
                                if($request->getMethod()=='POST') {
                                    $session->set("resumen_visto", true);
                                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                                }
                                $estudiantes=[];
                                $periodos_alumnos=[];
                                foreach($session->get('alumnos_inscripcion') as $estudiante_sesion) {
                                    $estudiante = $this->getDoctrine()
                                        ->getRepository('alumnosBundle:Alumnos')
                                        ->find($estudiante_sesion->getId());
                                    array_push($estudiantes, $estudiante);
                                }
                                foreach($session->get('alumnos_inscripcion') as $estudiante_sesion) {
                                    $periodo_alumno = $this->getDoctrine()
                                        ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
                                        ->findby(array('alumno'=>$estudiante_sesion->getId(), 'activo'=>'true'));
                                    array_push($periodos_alumnos, $periodo_alumno);
                                }

                                $resultado = array('accion'=>'Resumen Inscripción', 'estudiantes'=>$estudiantes, 'periodos_alumnos'=>$periodos_alumnos);
                            }
                            else {
                                //$mail = $this->get('funciones_genericas')->email_inscripcion($session->get('representantes')[0], $session->get('alumnos_inscripcion'));
                                $session->remove('representantes_finalizado');
                                $session->remove('representantes');
                                $session->remove('alumnos_finalizado');
                                $session->remove('alumnos_inscripcion');
                                $session->remove('datos_facturacion');
                                $session->remove('alumnos_montos_procesados');
                                $session->remove('faturacion_finalizado');
                                $session->remove('facturas');
                                $session->remove('pagos_finalizado');
                                $session->remove('pagos');
                                $session->remove('resumen_visto');
                                $this->get('session')->getFlashBag()->add(
                                    'success', 'Inscripción realizada con éxito');
                                return $this->redirect($this->generateUrl('inicial_homepage'));
                            }
                        }
                    }
                }
            }
        }
        return $this->render('genericoBundle:Default:crear_generico.html.twig', $resultado);
    }


    public function agregar_alumno_inscripcionAction($id_rep, Request $request)
    {
        $session = $this->getRequest()->getSession();

        if($id_rep){
            $p = $this->getDoctrine()
                ->getRepository('usuariosBundle:Usuarios')
                ->find($id_rep);
            $alumnos = $p->getAlumno();
            $lista_id = $alumnos->map(function($entity){return $entity->getId();})->toArray();
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario')
                ->innerJoin('usuario.alumno', 'alumnos')
                ->where('alumnos.id in (:id)')
                ->andWhere('usuario.activo = true')
                ->andWhere('usuario.principal = false')
                ->andWhere('usuario.tipoUsuario=5')
                ->setParameter('id', $lista_id)
                ->distinct('usuario.id')
                ->getQuery();
            $test = $query->getResult();
            if (!$session->get('representantes')) {
                $session->set("representantes", array());
            }
            $array_representantes = $session->get('representantes');
            foreach($test as $representante){
                array_push($array_representantes, $representante);
            }
            $session->set("representantes", $array_representantes);
            //print_r($array_representantes[0]->getprimerNombre());
        }

        if (!$session->get('representantes')) {
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.primerApellido, usuario.primerNombre, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id=5')
                ->orderBy('usuario.id', 'DESC')
                ->getQuery();
            $datos = $query->getArrayResult();
            $elemento = 'Seleccione Representante';

            return $this->render('genericoBundle:Default:crear_generico.html.twig', array('accion'=>$elemento, 'lista_representante'=>$datos));
        }

        else {
            if (!$session->get('alumnos_finalizado')) {
                $remover = null;

                $id_representantes = [];
                foreach($session->get('representantes') as $reps){
                    array_push($id_representantes, $reps->getId());
                }
                $resultado = $this->get('alumnos_funciones_genericas')->crear_alumno_generico($request, $remover, $id_representantes);
                if (array_key_exists('alumnos', $resultado)){
                    if(!$session->get('alumnos_inscripcion')){
                        $session->set("alumnos_inscripcion", array());
                    }
                    $array_alumnos = $session->get('alumnos_inscripcion');
                    array_push($array_alumnos, $resultado['alumnos']);
                    $session->set("alumnos_inscripcion",$array_alumnos);

                    if(array_key_exists('alumnos_finalizado', $resultado)){
                        $session->set("alumnos_finalizado", true);
                        return $this->redirect($this->generateUrl('generico_inscripcion_agregar_alumno'));
                    }
                    else{
                        return $this->redirect($this->generateUrl('generico_inscripcion_agregar_alumno'));
                    }
                }
            }
            else{
                if(!$session->get('datos_facturacion')) {
                    if(!$session->get('alumnos_fact_procesados')){
                        $session->set("alumnos_fact_procesados", array());
                    }
                    foreach($session->get('alumnos_inscripcion') as $estudiante){
                        if ($estudiante->getTipoFacturacion()=='particular'){
                            if($estudiante->getId() ){

                            }
                        }
                        else{
                            print_r('pudrete');
                        }
                    }
                    $p = New TipoFactura();
                    $formulario = $this->createForm(new TipoFacturaType('Crear Tipo Factura'), $p);
                    $formulario-> handleRequest($request);

                    if($request->getMethod()=='POST') {
                        if ($formulario->isValid()) {
                            $p->setActivo(true);

                            foreach($p->getConceptosFactura() as $concepto_factura){
                                foreach($concepto_factura->getTipoMontoConceptos() as $tipo_monto_con){
                                    $tipo_monto_con->setConceptosFactura($concepto_factura);
                                }
                                $concepto_factura->setActivo(true);
                            }
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($p);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add(
                                'success', 'Tipo Factura creada con éxito');

                            if ($formulario->get('guardar')->isClicked()) {
                                return $this->redirect($this->generateUrl('inicial_homepage'));
                            }
                            if ($formulario->get('guardar_crear')->isClicked()){
                                return $this->redirect($this->generateUrl('inicial_agregar_tfactura'));
                            }
                        }
                    }
                    return $this->render('facturacionBundle:Default:crear_tipo_factura.html.twig', array('form'=>$formulario->createView(),
                        'accion'=>'Crear Tipo Factura',));
                exit;
                }
                else {
                    $session->remove('representante_inscripcion');
                    $session->remove('alumnos_inscripcion');
                    $session->remove('alumnos_finalizado');
                    $session->remove('representantes_adic_anteriores');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representante_inscripcion');
                    $session->remove('representantes_adic_nuevo_finalizado');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representantes_adic_nuevo_finalizado');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representantes_adic_finalizado');
                    $session->remove('representantes_adic_anteriores');
                    $session->remove('representantes');
                    $session->remove('representante_inscripcion');
                    $session->remove('alumnos_inscripcion');
                    $session->remove('alumnos_finalizado');
                    $session->remove('representantes_adic_anteriores');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representante_inscripcion');
                    $session->remove('representantes_adic_nuevo_finalizado');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representantes_adic_nuevo_finalizado');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representantes_adic_finalizado');

                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
            }
        }
        return $this->render('genericoBundle:Default:crear_generico.html.twig', $resultado);
    }
}
