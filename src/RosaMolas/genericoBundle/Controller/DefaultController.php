<?php

namespace RosaMolas\genericoBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\alumnosBundle\Entity\AlumnoRepresentanteDatos;
use RosaMolas\alumnosBundle\Entity\Alumnos;
use RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggRepsDatos;
use RosaMolas\alumnosBundle\Form\AlumnosTypeInscripcion;
use RosaMolas\facturacionBundle\Entity\DetalleFactura;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggRep;
use RosaMolas\facturacionBundle\Entity\Factura;
use RosaMolas\facturacionBundle\Entity\MontosAlumnos;
use RosaMolas\facturacionBundle\Entity\TipoFactura;
use RosaMolas\facturacionBundle\Form\TipoFacturaType;
use RosaMolas\genericoBundle\Entity\Inscripcion;
use RosaMolas\genericoBundle\Entity\Pagos;
use RosaMolas\genericoBundle\Entity\Parentescos;
use RosaMolas\genericoBundle\Form\InscripcionType;
use RosaMolas\genericoBundle\Form\PagosType;
use RosaMolas\genericoBundle\Form\ParentescosType;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\usuariosBundle\Form\UsuariosTypeInscripcion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        //print_r($html);
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
        $p->setFechaRegistro(new \DateTime("now"));
        $formulario = $this->createForm(new PagosType('Agregar Pago'), $p);
        $formulario-> handleRequest($request);

//        $query_factura = $this->getDoctrine()->getRepository('facturacionBundle:Factura')
//            ->createQueryBuilder('factura')
//            ->select('factura.fecha', 'factura.monto', 'factura.id', 'factura.periodoEscolarCursoAlumnos')
//            ->where('factura.periodoEscolarCursoAlumnos = :periodo_alumno')
//            ->andwhere('factura.pagada = false')
//            ->andwhere('factura.activo = true')
//            ->setParameter('periodo_alumno',$factura->getPeriodoEscolarCursoAlumnos()->getId())
//            ->getQuery();
//        $facturas = $query_factura->getArrayResult();
        $facturas = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->findBy(array(
                'periodoEscolarCursoAlumnos'=> $factura->getPeriodoEscolarCursoAlumnos()->getId(),
                'pagada'=>false,
                'activo'=>true
            ));

        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {


                $facturaSeleccionadas = $request->request->get('facturas');

                $pagoFacturas = $this->getDoctrine()
                    ->getRepository('facturacionBundle:Factura')
                    ->findBy(array('id'=> $facturaSeleccionadas));

                $p->setActivo(true);
                $monto_facturas_seleccionadas = 0;

                foreach($pagoFacturas as $factura){
                    $factura->setPagada(true);
                    $p->addFactura($factura);
                    $monto_facturas_seleccionadas = $monto_facturas_seleccionadas + $factura->getMonto();
                }
                if($p->getMonto()<$monto_facturas_seleccionadas){
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Monto del pago insuficiente para cubrir las facturas seleccionadas');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
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
        return $this->render('genericoBundle:Default:agregar_pago.html.twig',
            array(
                'form'=>$formulario->createView(),
                'accion'=>'Agregar Pago',
                'factura'=>$factura,
                'estudiante'=>$estudiante,
                'facturas'=>$facturas

            ));
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

    public function inscripcion_completaAction($id_rep, $id_agg_rep, Request $request)
    {
        $session = $this->getRequest()->getSession();
        $inscripcion = $this->getDoctrine()
            ->getRepository('genericoBundle:Inscripcion')
            ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
        if(empty($inscripcion)){
            $inscripcion = new Inscripcion();
            $inscripcion->setFecha(new \DateTime("now"));
            $inscripcion->setIscripcionHash(md5($inscripcion->getFecha()->format('Y-m-d-H-i-s').$this->getUser()->getUsername()));
            $inscripcion->setActivo(true);
            $inscripcion->setEstatus(1);
            $inscripcion->setUsuario($this->getUser()->getUsuario());
            $em = $this->getDoctrine()->getManager();
            $em->persist($inscripcion);
            $em->flush();

        }
        if(!empty($id_agg_rep)){
            $representante = $this->getDoctrine()
                ->getRepository('usuariosBundle:Usuarios')
                ->find($id_agg_rep);
            if (!$representante)
            {
                throw $this -> createNotFoundException('No existe representante con este id: '.$id_agg_rep);
            }
            else{
                $representantes = $inscripcion->getRepresentantes();
                $ids =explode(',', $representantes);
                if(!in_array($id_agg_rep, $ids)){
                    if(empty($representantes)){
                        $representantes = $id_agg_rep;
                    }
                    else{
                        $representantes = $representantes.','.$id_agg_rep;
                    }
                    $inscripcion->setRepresentantes($representantes);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Representante Asignado con éxito');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
                else {
                    $this->get('session')->getFlashBag()->add(
                        'warning', 'Representante ya esta asignado a esta inscripción');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
            }
        }
        $session->set("inscripcion", $inscripcion);

        $representantes = null;
        $representantes_ids = $inscripcion->getRepresentantes();
        if(!empty($representantes_ids)){
            $ids =explode(',', $representantes_ids);
            $representantes = $this->getDoctrine()
                ->getRepository('usuariosBundle:Usuarios')
                ->findBy(array('id'=> $ids));
        }
        $alumnosInscripcion = null;
        $alumnosInscripcion_ids = $inscripcion->getAlumnos();
        if(!empty($alumnosInscripcion_ids)){
            $ids =explode(',', $alumnosInscripcion_ids);
            $alumnosInscripcion = $this->getDoctrine()
                ->getRepository('alumnosBundle:Alumnos')
                ->findBy(array('id'=> $ids));
        }
        $facturasInscripcion = null;
        $facturasInscripcion_ids = $inscripcion->getFacturas();
        if(!empty($facturasInscripcion_ids)){
            $ids =explode(',', $facturasInscripcion_ids);
            $facturasInscripcion = $this->getDoctrine()
                ->getRepository('facturacionBundle:Factura')
                ->findBy(array('id'=> $ids));
        }
        $pagosFacturas = null;
        $pagosFacturas_ids = $inscripcion->getPagos();
        if(!empty($pagosFacturas_ids)){
            $ids = explode(',', $pagosFacturas_ids);
            $pagosFacturas = $this->getDoctrine()
                ->getRepository('facturacionBundle:Factura')
                ->findBy(array('id'=> $ids));
        }
        $alumnosRepresentantesDatos = new ArrayCollection();
        if(!empty($alumnosInscripcion) and !empty($representantes)){
            foreach($alumnosInscripcion as $alumnos_tmp){
                foreach($representantes as $rep){
                    $alumnoRepDatos = $this->getDoctrine()
                        ->getRepository('alumnosBundle:AlumnoRepresentanteDatos')
                        ->findOneBy(array('representante'=> $rep->getId(), 'alumno'=>$alumnos_tmp->getId()));
                    if(empty($alumnoRepDatos)){
                        $alumnoRepDatos = new AlumnoRepresentanteDatos();
                        $alumnoRepDatos->setAlumno($alumnos_tmp);
                        $alumnoRepDatos->setRepresentante($rep);
                    }
                    $alumnosRepresentantesDatos->add($alumnoRepDatos);
                }
            }
        }
        $alumnosRepDatosForm =$this->createForm( new AlumnosTypeAggRepsDatos('Datos de parentesco'), array('test' => $alumnosRepresentantesDatos));
        $alumnosRepDatosForm-> handleRequest($request);
        $formInscripcion = $this->createForm(new InscripcionType('test'), $inscripcion);
        $formInscripcion-> handleRequest($request);

        $estudiante_monto_particular = new ArrayCollection();
        if($inscripcion->getEstatus() == 2) {
            $montosParticulares = $inscripcion->getMontosParticulares();
            $ids =explode(',', $montosParticulares);
            foreach($alumnosInscripcion as $estudiante){
                if ($estudiante->getTipoFacturacion()=='particular'){
                    if(!in_array($estudiante->getId(), $ids)){
                        if(empty($montosParticulares )){
                            $montosParticulares = $estudiante->getId();
                        }
                        else{
                            $montosParticulares = $montosParticulares .','.$estudiante->getId();
                        }
                        $estudiante_monto_particular->add($estudiante);
                        $em = $this->getDoctrine()->getManager();
                        $inscripcion->setMontosParticulares($montosParticulares);
                        $em->flush();
                    }
                }
            }
            if(empty($estudiante_monto_particular)){
                $em = $this->getDoctrine()->getManager();
                $inscripcion->setEstatus(3);
                $em->flush();
            }
        }

        if($inscripcion->getEstatus() == 3 and count($alumnosInscripcion) != count($inscripcion->getFacturas())) {
            $tipo_factura = $this->getDoctrine()->getRepository('facturacionBundle:TipoFactura')->find(1);

            if(empty($facturasInscripcion)){
                foreach ($alumnosInscripcion as $estudiante) {
                    $fact = $this->get('funciones_genericas')->crear_factura($estudiante->getId(), $tipo_factura);
                    if(empty($facturasInscripcion_ids)){
                        $facturasInscripcion_ids = $fact->getId();
                    }
                    else{
                        $facturasInscripcion_ids = $facturasInscripcion_ids.','.$fact->getId();
                    }
                }
                $inscripcion->setFacturas($facturasInscripcion_ids);
                $inscripcion->setEstatus(4);
                $em = $this->getDoctrine()->getManager();

                $em->flush();
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        if($inscripcion->getEstatus() == 4 and count(explode(',',$inscripcion->getAlumnos())) > count(explode(',',$inscripcion->getPagos()))) {
            $p = new Pagos();
            $facturasPorPagos = [];
            $totalFacturado = 0;

            foreach ($facturasInscripcion as $factura) {
                if(!$factura->getPagada()){
                    $totalFacturado = $totalFacturado + $factura->getMonto();
                    $facturasPorPagos[] = $factura;
                }
            }

            $formularioPagos = $this->createForm(new PagosType('Agregar Pago'), $p);
            $formularioPagos->handleRequest($request);

            if ($request->getMethod() == 'POST') {
                if ($formularioPagos->isValid()) {
                    $facturaSeleccionadas = $request->request->get('facturas');

                    $pagoFacturas = $this->getDoctrine()
                        ->getRepository('facturacionBundle:Factura')
                        ->findBy(array('id'=> $facturaSeleccionadas));

                    $p->setActivo(true);
                    $monto_facturas_seleccionadas = 0;

                    foreach($pagoFacturas as $factura){
                        $factura->setPagada(true);
                        $p->addFactura($factura);
                        $monto_facturas_seleccionadas = $monto_facturas_seleccionadas + $factura->getMonto();
                    }
                    if($p->getMonto()<$monto_facturas_seleccionadas){
                        $this->get('session')->getFlashBag()->add(
                            'success', 'Monto del pago insuficiente para cubrir las facturas seleccionadas');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($p);
                    $pagosFacturaIds = $inscripcion->getPagos();
                    if(empty($pagosFacturaIds )){
                        $pagosFacturaIds = $p->getId();
                    }
                    else{
                        $pagosFacturaIds = $pagosFacturaIds .','.$p->getId();
                    }
                    $inscripcion->setPagos($pagosFacturaIds);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Pago creado con éxito'
                    );
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
            }
        }
        elseif($inscripcion->getEstatus() == 4 and count(explode(',',$inscripcion->getAlumnos())) == count(explode(',',$inscripcion->getPagos()))) {
            $em = $this->getDoctrine()->getManager();
            $inscripcion->setEstatus(5);
            $em->flush();
        }
        if ($inscripcion->getEstatus() == 5 and $inscripcion->getActivo()){
            $accion = 'Resumen Inscripción';
            $periodos_alumnos=[];
            foreach($alumnosInscripcion as $estudiante) {
                $periodo_alumno = $this->getDoctrine()
                    ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
                    ->findby(array('alumno' => $estudiante->getId(), 'activo' => 'true'));
                array_push($periodos_alumnos, $periodo_alumno);
            }

        }
        if($request->getMethod()=='POST') {
            if ($alumnosRepDatosForm->get('guardar')->isClicked()) {

                if ($alumnosRepDatosForm->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    foreach ($alumnosRepresentantesDatos as $alumnoRepDatos) {
                        $em->persist($alumnoRepDatos);
                    }
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Datos Actualizados con éxito');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
            }
            if ($formInscripcion->get('guardar')->isClicked()) {
                if($inscripcion->getEstatus()==1){
                    if (count($alumnosRepresentantesDatos) == count($alumnosInscripcion) * count($representantes)) {
                        $em = $this->getDoctrine()->getManager();
                        $inscripcion->setEstatus(2);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                            'success', 'Datos Actualizados con éxito');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    else {
                        $this->get('session')->getFlashBag()->add(
                            'warning', 'Debe completar los datos de parentezco');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                }
                elseif($inscripcion->getEstatus()==2) {
                    if (count($inscripcion->getMontosParticulares()) == count($inscripcion->getMontosParticularesProcesados())) {
                        $em = $this->getDoctrine()->getManager();
                        $inscripcion->setEstatus(3);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                            'success', 'Datos Actualizados con éxito');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    } else {
                        $this->get('session')->getFlashBag()->add(
                            'warning', 'Agregar todos los montos particulares');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                }
                elseif($inscripcion->getEstatus()==3){
                    if(count($inscripcion->getFacturas())== count($inscripcion->getPagos())){
                        $em = $this->getDoctrine()->getManager();
                        $inscripcion->setEstatus(4);
                        $inscripcion->setActivo(false);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                            'success', 'Datos Actualizados con éxito');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    else {
                        $this->get('session')->getFlashBag()->add(
                            'warning', 'Agregar todos los montos particulares');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                }
                elseif($inscripcion->getEstatus()==5){

                }
            }
        }

        if($inscripcion->getEstatus() == 1){
            $template = 'genericoBundle:Default:crear_generico2.html.twig';
        }
        elseif($inscripcion->getEstatus()==2){
            $template = 'genericoBundle:Default:crear_generico4.html.twig';
        }
        elseif($inscripcion->getEstatus()>=4){
            $template = 'genericoBundle:Default:crear_generico3.html.twig';
        }

        return $this->render($template,
            array(
                'representantes'=>$representantes,
                'inscripcion'=>$inscripcion,
                'alumnosInscripcion' => $alumnosInscripcion,
                'alumnosRepDatosForm' =>$alumnosRepDatosForm ? $alumnosRepDatosForm->createView(): '',
                'formInscripcion' => $formInscripcion->createView(),
                'formPagos' => isset($formularioPagos) ? $formularioPagos->createView():'',
                'facturas' => isset($facturasInscripcion) ? $facturasInscripcion :'',
                'facturasPorPagos' => isset($facturasPorPagos) ? $facturasPorPagos :'',
                'totalFacturado' => isset($totalFacturado) ? $totalFacturado : '',
                'accion'=>isset($accion) ? $accion : '',
                'periodos_alumnos' => isset($periodos_alumnos) ? $periodos_alumnos : ''
            )
        );
    }

    public function formulario_crear_representante_genericoAction(Request $request){
        $p = new Usuarios();
        $titulo= 'Crear Representante';
        $formulario = $this->createForm(new UsuariosTypeInscripcion($titulo), $p);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');

        $tipo_usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:TipoUsuario')
            ->find(5);
        $p->setTipoUsuario($tipo_usuario);
        $p->setPrincipal(false);

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {

                $inscripcion = $this->getDoctrine()
                    ->getRepository('genericoBundle:Inscripcion')
                    ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));

                $em = $this->getDoctrine()->getManager();
                $p->setInscripcion($inscripcion);
                $em->persist($p);

                $representantes = $inscripcion->getRepresentantes();
                if(empty($representantes)){
                    $representantes = $p->getId();
                }
                else{
                    $representantes = $representantes.','.$p->getId();
                }
                $inscripcion->setRepresentantes($representantes);
                $em->flush();
//                $session->set("inscripcion", $inscripcion);
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante Creado con éxito');
//                return array('representante'=>$p,);
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
//        return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
        return $this->render('genericoBundle:Default/parts:crear_representante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Representante'));
    }

    public function formulario_actualizar_representante_genericoAction(Request $request, $id){
        $p = $this->getDoctrine()
            ->getRepository('usuariosBundle:Usuarios')
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe Estudiante con este id: '.$id);
        }

        $titulo= 'Crear Representante';
        $formulario = $this->createForm(new UsuariosTypeInscripcion($titulo), $p);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');

        $tipo_usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:TipoUsuario')
            ->find(5);
        $p->setTipoUsuario($tipo_usuario);
        $p->setPrincipal(false);

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante Actualizado con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:crear_representante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Actualizar Representante'));
    }

    public function quitar_representante_inscripcionAction(Request $request, $id){
        $inscripcion = $this->getDoctrine()
            ->getRepository('genericoBundle:Inscripcion')
            ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
        $representantes = $inscripcion->getRepresentantes();
        if(!empty($representantes)) {
            $representantes = str_replace($id, '', $representantes);
            $representantes = str_replace(',,', ',', $representantes);
            $representantes = ltrim($representantes, ',');
            $representantes = rtrim($representantes, ',');
            $inscripcion->setRepresentantes($representantes);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'warning', 'Representante removido con éxito');
        }
        return new JsonResponse(true, 200);
    }
    public function quitar_alumno_inscripcionAction(Request $request, $id){
        $inscripcion = $this->getDoctrine()
            ->getRepository('genericoBundle:Inscripcion')
            ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
        $alumnos = $inscripcion->getAlumnos();
        if(!empty($alumnos)) {
            $alumno = $this->getDoctrine()
                ->getRepository('alumnosBundle:Alumnos')
                ->find($id);
            if (!$alumno)
            {
                throw $this -> createNotFoundException('No existe Estudiante con este id: '.$id);
            }
            $alumnos = str_replace($id, '', $alumnos);
            $alumnos = str_replace(',,', ',', $alumnos);
            $alumnos = ltrim($alumnos, ',');
            $alumnos = rtrim($alumnos, ',');
            $em = $this->getDoctrine()->getManager();
            $inscripcion->setAlumnos($alumnos);
            $em->flush();

            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();
            $em->remove($alumno);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'warning', 'Estudiante eliminado con éxito');
        }
        return new JsonResponse(true, 200);
    }

    public function formulario_crear_estudiante_genericoAction(Request $request){
        $alumno = new Alumnos();

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

        $formulario = $this->createForm(new AlumnosTypeInscripcion('Crear Estudiante', null, $cant_seccion), $alumno);

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $inscripcion = $this->getDoctrine()
                    ->getRepository('genericoBundle:Inscripcion')
                    ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
                $alumno->setActivo(true);

                $periodo_activo = $this->getDoctrine()
                    ->getRepository('inicialBundle:PeriodoEscolar')
                    ->findOneBy(array('activo'=>true));

                foreach($alumno->getPeriodoEscolarCursoAlumno() as $periodo_alumno){
                    $periodo_alumno->setPeriodoEscolar($periodo_activo);
                    $periodo_alumno->setActivo(true);
                }

                $em = $this->getDoctrine()->getManager();
                $alumno->setInscripcion($inscripcion);
                $em->persist($alumno);

                $alumnosInscripcionIds = $inscripcion->getAlumnos();
                if(empty($alumnosInscripcionIds )){
                    $alumnosInscripcion = $alumno->getId();
                }
                else{
                    $alumnosInscripcion = $alumnosInscripcionIds .','.$alumno->getId();
                }
                $inscripcion->setAlumnos($alumnosInscripcion);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Estudiante Creado con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:crear_estudiante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante'));
    }

    public function formulario_actualizar_estudiante_genericoAction(Request $request, $id)
    {

        $alumno = $this->getDoctrine()
            ->getRepository('alumnosBundle:Alumnos')
            ->find($id);
        if (!$alumno)
        {
            throw $this -> createNotFoundException('No existe Estudiante con este id: '.$id);
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

        $formulario = $this->createForm(new AlumnosTypeInscripcion('Actualizar Estudiante', null, $cant_seccion), $alumno);
        $formulario -> remove('alumnoRepresentanteDatos');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Estudiante Actualizado con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:editar_estudiante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Actualizar Estudiante'));
    }

    public function inscripcion_lista_agregar_representanteAction(Request $request)
    {
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

        return $this->render('genericoBundle:Default/parts:agregar_representante_existente.html.twig', array('accion'=>$elemento, 'lista_representante'=>$datos));
    }

    public function formulario_agregar_monto_estudiante_genericoAction(Request $request, $id){
        $p = $this->getDoctrine()
            ->getRepository('facturacionBundle:TipoFactura')
            ->findBy(array('activo' => 'true', 'inscripcion'=>false));

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
            ->find($id);
        if($request->getMethod()=='POST') {
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
                $inscripcion = $this->getDoctrine()
                    ->getRepository('genericoBundle:Inscripcion')
                    ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));

                $alumnosMontoParticularIds = $inscripcion->getAlumnos();
                if(empty($alumnosMontoParticularIds )){
                    $alumnosInscripcion = $estudiante->getId();
                }
                else{
                    $alumnosInscripcion = $alumnosMontoParticularIds .','.$estudiante->getId();
                }
                $inscripcion->setMontosParticularesProcesados($alumnosInscripcion);

                $em = $this->getDoctrine()->getManager();
                foreach ($p as $tipo_factura) {
                    $em->persist($tipo_factura);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Montos particulares del estudiante creados con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:crear_montos_estudiante.html.twig',
            array('form'=>$formulario->createView(),
                'accion'=>'Crear Montos para Estudiante',
                'estudiante'=>$estudiante
            )
        );
    }
    public function finalizarAnioAction(Request $request){

    }


}
