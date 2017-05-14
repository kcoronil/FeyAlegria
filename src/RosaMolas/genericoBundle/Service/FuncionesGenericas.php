<?php

namespace RosaMolas\genericoBundle\Service;

use RosaMolas\facturacionBundle\Entity\DetalleFactura;
use RosaMolas\facturacionBundle\Entity\Factura;
use RosaMolas\genericoBundle\Entity\Pagos;
use RosaMolas\genericoBundle\Form\PagosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;
use Test\inicialBundle\Entity\TrazaEventosUsuarios;


class FuncionesGenericas extends Controller
{
    public function __construct($container)
    {
        $this->container = $container;
    }


    public function crear_generico($request, $modelo, $formulario_base, $objeto, $clase, $titulo, $url_redireccion= null, $url_editar= null, $url_borrar= null, $datos = null, $remover = null)
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
            $query = $this->getDoctrine()->getRepository($clase)
                ->createQueryBuilder(strtolower($objeto))
                ->where(strtolower($objeto).'.activo = true')
                ->getQuery();


            $datos = $query->getArrayResult();
        }
        if($request->getMethod()=='POST') {
            if(!$url_redireccion) {
                $url_redireccion = 'inicial_agregar_' . strtolower($objeto);
            }
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', strtolower($titulo).' creado con éxito'
                );
                if(array_key_exists('guardar_crear', $formulario)){
                    if ($formulario->get('guardar')->isClicked()) {
                        return array('resulado'=>'exito', 'url'=> 'inicial_homepage', 'objeto_creado' => $p);
                    }
                    if ($formulario->get('guardar_crear')->isClicked()) {
                        return array('resulado'=>'exito', 'url'=> $url_redireccion, 'objeto_creado' => $p);
                    }
                }
                else {
                    return array('resulado'=>'exito', 'url'=> $url_redireccion, 'objeto_creado' => $p);

                }
            }
            else{
                return array('form'=>$formulario->createView(),
                    'datos'=>$datos, 'accion'=>'Crear '.$titulo, 'url_editar'=>$url_editar,
                    'url_borrar'=>$url_borrar, 'operaciones_datos'=>true);
            }
        }
        if(!$url_editar) {
            $url_editar = 'inicial_editar_' . strtolower($objeto);
        }
        if(!$url_borrar) {
            $url_borrar = 'inicial_borrar_' . strtolower($objeto);
        }
        return array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>'Crear '.$titulo, 'url_editar'=>$url_editar,
            'url_borrar'=>$url_borrar, 'operaciones_datos'=>true);
    }
    public function editar_generico($id, $request, $formulario_base, $clase, $titulo, $url_redireccion, $remover = null)
    {

        $p = $this->getDoctrine()
            ->getRepository($clase)
            ->find($id);
        if (!$p)
        {
            $this->get('session')->getFlashBag()->add(
                'warning', 'No hay registros con este identificador '. $id);
            return array('resulado'=>'fallido', 'url'=> $url_redireccion);
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
                    'success', strtolower($titulo).' editado con éxito');
                return array('resulado'=>'exito', 'url'=> $url_redireccion);
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Editar '.$titulo);
    }

    public function borrar_generico($id, $request, $formulario_base, $clase, $objeto, $titulo, $url_redireccion, $remover = null)
    {
        $p = $this->getDoctrine()
            ->getRepository($clase)
            ->find($id);
        if (!$p)
        {
            $this->get('session')->getFlashBag()->add(
                'warning', 'No hay registros con este identificador '. $id);
            return array('resulado'=>'fallido', 'url'=> $url_redireccion);
        }
        $formulario = $this->createForm($formulario_base, $p);
        $formulario -> remove('nombre');
        if($remover){
            foreach($remover as $campo){
                $formulario->remove($campo);
            }
        }
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository($clase)
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
                    'warning', $titulo.' borrado con éxito');
                return array('resulado'=>'exito', 'url'=> $url_redireccion);
            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = $url_redireccion;
        return array('form'=>$formulario->createView(),'datos'=>$datos, 'accion'=>'Borrar '.$titulo, 'atajo'=>$atajo);
    }
    public function registro_traza_usuario($modelo, $nombre_evento, $objeto){
        //$tableName = $em->getClassMetadata('StoreBundle:User')->getTableName();

        $evento = $this->getDoctrine()
            ->getRepository('genericoBundle:Eventos')
            ->findOneBy(array('nombre'=>$nombre_evento));

        $session = $this->getRequest()->getSession();
        $usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:Usuarios')
            ->find($session->get('usuario_id'));


        $manager = $this->getDoctrine()->getManager();
        $manager2 = $this->getDoctrine()->getManager();
        $nombre_tabla = $manager2->getClassMetadata($modelo)->getTableName();
        $elemento = $this->getDoctrine()
            ->getRepository('genericoBundle:Elementos')
            ->findOneBy(array('nombre'=>$nombre_tabla));
        $id_objeto = $objeto->getId();
        $nombre_entidad = $manager2->getClassMetadata($modelo)->getReflectionClass()->getName();
        $detalle = $nombre_tabla.','.$nombre_entidad;

        $p = new TrazaEventosUsuarios();
        $p->setElemento($elemento);
        $p->setUsuario($usuario);
        $p->setidEvento($evento);
        $p->setidObjeto($id_objeto);
        $p->setDetalles($detalle);
        $p->setFecha(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($p);
        $manager->flush();

        return array('resultado' => true);
    }
    public function crear_factura($estudiante_id, $tipo_factura)
    {
        $nueva_fact = New Factura();
        $monto_factura = 0;
        $nueva_fact->setActivo(true);
        $estudiante = $this->getDoctrine()
            ->getRepository('alumnosBundle:Alumnos')
            ->find($estudiante_id);
        $periodo_alumno = $this->getDoctrine()
            ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->findOneBy(array('alumno' => $estudiante, 'activo'=> 'true'));
        $nueva_fact->setPeriodoEscolarCursoAlumnos($periodo_alumno);
        $nueva_fact->setTipoFactura($tipo_factura);
        $nueva_fact->setFecha(new \DateTime(date('Y-m-d H:i:s')));
        $nueva_fact->setPagada(false);
        foreach ($nueva_fact->getTipoFactura()->getConceptosFactura() as $concepto_tipo) {
            if($concepto_tipo->getActivo()) {
                $nueva_fact_detalle = New DetalleFactura();
                $nueva_fact_detalle->setActivo(true);
                $nueva_fact_detalle->setConcepto($concepto_tipo);
                $nueva_fact_detalle->setFactura($nueva_fact);
                if(strtolower($estudiante->getTipoFacturacion()->getNombre()) == 'particular' and !$tipo_factura->getInscripcion()) {
                    $p = $this->getDoctrine()
                        ->getRepository('facturacionBundle:MontosAlumnos')
                        ->findOneBy(array('alumno' => $estudiante, 'conceptoFactura' => $concepto_tipo, 'activo'=>true));
                    $nueva_fact_detalle->setMonto($p->getMonto());
                    //print_r($nueva_fact_detalle->getMonto().'<br>');
                }
                else {
                    $concepto_monto = $this->getDoctrine()
                        ->getRepository('facturacionBundle:TipoMontoConceptos')
                        ->findOneBy(array('conceptosFactura' => $concepto_tipo, 'activo'=>true));
                    $nueva_fact_detalle->setMonto($concepto_monto->getMonto());
                    //print_r($nueva_fact_detalle->getMonto() . '<br>');
                }

                $monto_factura = floatval($monto_factura) + floatval($nueva_fact_detalle->getMonto());
                $nueva_fact->addDetalleFactura($nueva_fact_detalle);
            }
        }
        $nueva_fact->setMonto($monto_factura);
        $em = $this->getDoctrine()->getManager();
        $em->persist($nueva_fact);
        $em->flush();
        return $nueva_fact;
    }
    public function agregar_pago_generico($facturas, $request)
    {
        /*$factura = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->find($id);*/
        //$estudiante = $factura->getPeriodoEscolarCursoAlumnos()->getAlumno();

        $p = new Pagos();

        //$p->addFactura($factura);
        //$p->setFechaRegistro(new \DateTime("now"));
        $alumnos_facturas = [];
        foreach($facturas as $factura){
            $p->addFactura($factura);
            $alumno_fact = $this->getDoctrine()
                ->getRepository('alumnosBundle:Alumnos')
                ->find($factura->getPeriodoEscolarCursoAlumnos()->getAlumno()->getId());
            //print_r($factura->getPeriodoEscolarCursoAlumnos()->getAlumno()->getId());
            $alumnos_facturas[$factura->getId()]= $alumno_fact;
        }
        //print_r($alumnos_facturas);
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
                    return array('pago'=>$p,'factura'=>$p->getFactura());
                }
                /*if ($formulario->get('guardar_crear')->isClicked()){
                    return $this->redirect($this->generateUrl('generico_agregar_pago'));
                }*/
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Agregar Pago', 'facturas'=>$facturas,
            'alumnos_facturas'=>$alumnos_facturas);
    }
    public function email_inscripcion($representante, $estudiantes)
    {
        $fecha_actual = new \DateTime("now");
        $html = $this->renderView('genericoBundle:Default:email_inscripcion.html.twig', array('accion'=>'Listado de Alumnos', 'fecha'=>$fecha_actual, 'representante' => $representante, 'estudiantes'=>$estudiantes));
        $mensaje_email = \Swift_Message::newInstance()
            ->setSubject('Inscripcion Colegio Fe y Alegria')
            ->setFrom('ed.acevedo.programacion@gmail.com')
            ->setTo($representante->getEmail())
            ->setBody($html, 'text/html');
        $this->get('mailer')->send($mensaje_email);
        return true;
    }
    public function emitir_recibo($id, $request){
        $facturas = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->find($id);

        $pago = $this->getDoctrine()
            ->getRepository('genericoBundle:Pagos')
            ->findOneBy(array('factura'=>$id));

        $fecha_actual = new \DateTime("now");
        $html = $this->renderView('genericoBundle:Default:index.html.twig', array('accion'=>'Listado de Alumnos', 'fecha'=>$fecha_actual, 'facturas' => $facturas, 'pago'=>$pago));
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array('Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="file.pdf"'));
    }
}

