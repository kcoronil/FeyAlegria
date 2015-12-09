<?php

namespace RosaMolas\genericoBundle\Controller;

use RosaMolas\facturacionBundle\Entity\DetalleFactura;
use RosaMolas\facturacionBundle\Entity\Factura;
use RosaMolas\genericoBundle\Entity\Pagos;
use RosaMolas\genericoBundle\Form\PagosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
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

        $html = $this->renderView('genericoBundle:Default:index.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos));

        print_r($html);
        exit;
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
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

    public function inscripcion_completaAction(Request $request)
    {
        $tipo_factura = $this->getDoctrine()->getRepository('FacturacionBundle:Factura')
            ->find(1);
        $session = $this->getRequest()->getSession();
        if (!$session->get('representante_inscripcion')) {
            $remover = array('guardar_crear', 'omitir');
            $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request, true, $remover, null, 'Crear Representante Principal');

            if (array_key_exists('representante', $resultado)) {
                $session->set("representante_inscripcion", $resultado['representante']);
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        else {
            if (!$session->get('alumnos_finalizado')) {
                $remover = array('usuario');
                $resultado = $this->get('alumnos_funciones_genericas')->crear_alumno_generico($request, $remover, $session->get('representante_inscripcion'));
                if (array_key_exists('alumnos', $resultado)){
                    if(!$session->get('alumnos_inscripcion')){
                        $session->set("alumnos_inscripcion", array());
                    }
                    $array_alumnos = $session->get('alumnos_inscripcion');
                    array_push($array_alumnos, $resultado['alumnos']);
                    $session->set("alumnos_inscripcion",$array_alumnos);

                    $nueva_fact = New Factura();
                    $monto_factura = 0;
                    $nueva_fact->setActivo(true);
                    $nueva_fact->setPeriodoEscolarCursoAlumnos($resultado['alumnos']);
                    $nueva_fact->setTipoFactura($tipo_factura);
                    $nueva_fact->setFecha(new \DateTime(date('Y-m-d H:i:s')));
                    $nueva_fact->setPagada(false);
                    foreach($nueva_fact->getTipoFactura()->getConceptosFactura() as $concepto_tipo){
                        $nueva_fact_detalle = New DetalleFactura();
                        $nueva_fact_detalle->setActivo(true);
                        $nueva_fact_detalle->setConcepto($concepto_tipo);
                        $nueva_fact_detalle->setFactura($nueva_fact);
                        $nueva_fact_detalle->setMonto($concepto_tipo->getTipoMontoConceptos()->first()->getMonto());
                        $monto_factura = floatval($monto_factura) + floatval($nueva_fact_detalle->getMonto());
                        $nueva_fact->addDetalleFactura($nueva_fact_detalle);
                    }
                    $nueva_fact->setMonto($monto_factura);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($nueva_fact);
                    $em->flush();

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
                if (!$session->get('representantes_adic_finalizado')) {
                    $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request, false, null, $session->get('alumnos_inscripcion'));
                    if (array_key_exists('representante', $resultado)) {
                        if($resultado['representante']!='') {
                            if (!$session->get('representantes_adic_inscripcion')) {
                                $session->set("representantes_adic_inscripcion", array());
                            }
                            $array_representantes_adic = $session->get('alumnos_inscripcion');
                            array_push($array_representantes_adic, $resultado['representante']);
                            $session->set("representantes_adic_inscripcion", $array_representantes_adic);
                        }
                        if(array_key_exists('representantes_finalizado', $resultado)){
                            $session->set("representantes_adic_finalizado", true);
                            return $this->redirect($this->generateUrl('generico_inscripcion_completa'));

                        }
                    else{
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                        }
                    }
                }
                else{
                    $session->remove('representante_inscripcion');
                    $session->remove('alumnos_inscripcion');
                    $session->remove('alumnos_finalizado');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representantes_adic_finalizado');
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Inscripción realizada con éxito');
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
            }
        }
        return $this->render('genericoBundle:Default:crear_generico.html.twig', $resultado);
    }
    public function agregar_alumno_inscripcionAction(Request $request)
    {
        $session = $this->getRequest()->getSession();
        if (!$session->get('representante_inscripcion')) {
            /*$remover = array('guardar_crear');
            $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request, true, $remover, null, 'Crear Representante Principal');
            */
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id=5')
                ->orderBy('usuario.id', 'DESC')
                ->getQuery();
            $datos = $query->getArrayResult();

            $elemento = 'Seleccione Representante';

            return $this->render('genericoBundle:Default:crear_generico.html.twig', array('accion'=>$elemento, 'lista_representante'=>$datos));

            /*if (array_key_exists('representante', $resultado)) {
                $session->set("representante_inscripcion", $resultado['representante']);
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }*/
        }
        else {
            if (!$session->get('alumnos_finalizado')) {
                $remover = array('usuario');
                $resultado = $this->get('alumnos_funciones_genericas')->crear_alumno_generico($request, $remover, $session->get('representante_inscripcion'));
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
                if (!$session->get('representantes_adic_finalizado')) {
                    $resultado = $this->get('usuarios_funciones_genericas')->crear_representante_generico($request, false, null, $session->get('alumnos_inscripcion'));
                    if (array_key_exists('representante', $resultado)) {
                        if(!$session->get('representantes_adic_inscripcion')){
                            $session->set("representantes_adic_inscripcion", array());
                        }
                        $array_representantes_adic = $session->get('alumnos_inscripcion');
                        array_push($array_representantes_adic, $resultado['representante']);
                        $session->set("representantes_adic_inscripcion",$array_representantes_adic);

                        if(array_key_exists('representantes_finalizado', $resultado)){
                            $session->set("representantes_adic_finalizado", true);
                            return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                        }
                        else{
                            return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                        }
                    }
                }
                else{
                    $session->remove('representante_inscripcion');
                    $session->remove('alumnos_inscripcion');
                    $session->remove('alumnos_finalizado');
                    $session->remove('representantes_adic_inscripcion');
                    $session->remove('representantes_adic_finalizado');
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
            }
        }
        return $this->render('genericoBundle:Default:crear_generico.html.twig', $resultado);
    }
}
