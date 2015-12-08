<?php
namespace RosaMolas\facturacionBundle\Controller;
use RosaMolas\alumnosBundle\Entity\Alumnos;
use RosaMolas\facturacionBundle\Entity\ConceptosFactura;
use RosaMolas\facturacionBundle\Entity\DetalleFactura;
use RosaMolas\facturacionBundle\Entity\Factura;
use RosaMolas\facturacionBundle\Entity\TipoFacturacion;
use RosaMolas\facturacionBundle\Entity\TipoMontoConceptos;
use RosaMolas\facturacionBundle\Entity\TipoMontos;
use RosaMolas\facturacionBundle\Form\FacturaType;
use RosaMolas\facturacionBundle\Form\TipoFacturacionType;
use RosaMolas\facturacionBundle\Form\TipoMontoConceptosType;
use RosaMolas\facturacionBundle\Form\TipoMontosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\facturacionBundle\Entity\TipoFactura;
use RosaMolas\facturacionBundle\Form\TipoFacturaType;
use Doctrine\Common\Collections\Criteria;

class DefaultController extends Controller
{
    public function generar_facturasAction(request $request){

        $formulario = $this->createForm(new FacturaType('Selecionar Tipo Factura'));
        $formulario->handleRequest($request);
        if($request->getMethod()=='POST'){

            $tipo_factura=$formulario["tipoFactura"]->getData();
            $alumnos_activos = $this->getDoctrine()->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
                ->findBy(array('activo' => true), array('cursoSeccion' => 'ASC'));
            $p = $this->getDoctrine()
                ->getRepository('facturacionBundle:TipoFactura')
                ->find($tipo_factura->getId());

            /*$hasTipoMonto = function($tipoMonto) {
                return function(TipoMontoConceptos $tipoMontoConceptos) use ($tipoMonto) {
                    return null !== $tipoMontoConceptos->getTipoMonto($tipoMonto);
                };
            };

            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("id", "1"));*/

            foreach ($alumnos_activos as $alumnos_act) {
                $nueva_fact = New Factura();
                $monto_factura = 0;
                $nueva_fact->setActivo(true);
                $nueva_fact->setPeriodoEscolarCursoAlumnos($alumnos_act);
                $nueva_fact->setTipoFactura($tipo_factura);
                $nueva_fact->setFecha(new \DateTime(date('Y-m-d H:i:s')));
                $nueva_fact->setPagada(false);
                foreach($nueva_fact->getTipoFactura()->getConceptosFactura() as $concepto_tipo){
                    $nueva_fact_detalle = New DetalleFactura();
                    $nueva_fact_detalle->setActivo(true);
                    $nueva_fact_detalle->setConcepto($concepto_tipo);
                    $nueva_fact_detalle->setFactura($nueva_fact);
                    //$a = new ConceptosFactura();
                    //print_r($concepto_tipo->getTipoMontoConceptos()->first()->getMonto());
                    //$a->getTipoMontoConceptos()->filter($hasTipoMonto('regular'));
                    $nueva_fact_detalle->setMonto($concepto_tipo->getTipoMontoConceptos()->first()->getMonto());
                    $monto_factura = floatval($monto_factura) + floatval($nueva_fact_detalle->getMonto());
                    $nueva_fact->addDetalleFactura($nueva_fact_detalle);
                }
                $nueva_fact->setMonto($monto_factura);
                $em = $this->getDoctrine()->getManager();
                $em->persist($nueva_fact);
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add(
                'success', 'Facturación procesada con éxito');
            return $this->redirect($this->generateUrl('inicial_homepage'));
        }
        return $this->render('facturacionBundle:Default:crear_factura.html.twig', array('form'=>$formulario->createView(),
            'accion'=>'Crear Factura',));
    }
    public function lista_facturasAction($id, request $request){

        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno as estudiante', 'periodo_alumno as periodo_estudiante', 'cursos as curso', 'secciones as seccion',
                'periodos as periodo')
            ->where('alumno.id = :id')
            ->andwhere('alumno.activo = true')
            ->innerJoin('alumnosBundle:PeriodoEscolarCursoAlumno', 'periodo_alumno', 'WITH', 'alumno.id = periodo_alumno.alumno')
            ->innerJoin('inicialBundle:PeriodoEscolar', 'periodos', 'WITH', 'periodo_alumno.periodoEscolar = periodos.id')
            ->innerJoin('inicialBundle:CursoSeccion', 'periodo_curso', 'WITH', 'periodo_alumno.cursoSeccion = periodo_curso.id')
            ->innerJoin('inicialBundle:Curso', 'cursos', 'WITH', 'periodo_curso.curso = cursos.id')
            ->innerJoin('inicialBundle:Seccion', 'secciones', 'WITH', 'periodo_curso.seccion = secciones.id')
            ->setParameter('id',$id)
            ->getQuery();
        $datos = $query->getArrayResult();

        $query_factura = $this->getDoctrine()->getRepository('facturacionBundle:Factura')
            ->createQueryBuilder('factura')
            ->select('factura.fecha', 'factura.monto', 'factura.id')
            ->where('factura.periodoEscolarCursoAlumnos = :periodo_alumno')
            ->andwhere('factura.pagada = false')
            ->andwhere('factura.activo = true')
            ->setParameter('periodo_alumno',$datos[1]['periodo_estudiante']['id'])
            ->getQuery();
        $facturas = $query_factura->getArrayResult();


        print_r($datos);
        return $this->render('genericoBundle:Default:agregar_pago.html.twig', array('accion'=>'Listado de Facturas Pendientes', 'datos'=>$datos, 'facturas'=>$facturas));
    }

    public function tipo_facturaAction(request $request){
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
    }
    public function editar_tfacturaAction($id, request $request){
        $p = $this->getDoctrine()
            ->getRepository('facturacionBundle:TipoFactura')
            ->find($id);
        $formulario = $this->createForm(new TipoFacturaType('editar Tipo Factura'), $p);
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
                    'success', 'Tipo Factura creada con éxito'
                );
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
    }
    public function crear_tipo_facturaAction(Request $request){
        $modelo = New TipoFactura();
        $form = new TipoFacturaType('Crear Tipo Factura');
        $objeto = 'TipoFactura';
        $clase = 'facturacionBundle:TipoFactura';
        $titulo = 'Tipo de Factura';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_factura';
        $url_editar = 'inicial_editar_tipo_factura';
        $url_borrar = 'inicial_borrar_tipo_factura';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Editar Tipo Factura');
        $clase = 'facturacionBundle:TipoFactura';
        $titulo = 'Tipo Factura';
        $url_redireccion = 'inicial_agregar_tipo_factura';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Borrar Tipo Factura');
        $objeto = 'TipoFactura';
        $clase = 'facturacionBundle:TipoFactura';
        $titulo = 'Tipo de Factura';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_factura';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_tipo_montoAction(Request $request){
        $modelo = New TipoMontos();
        $form = new TipoMontosType('Crear Montos');
        $objeto = 'TipoMontos';
        $clase = 'facturacionBundle:TipoMontos';
        $titulo = 'Montos para Facturacion';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'facturacion_agregar_tipo_montos';
        $url_editar = 'facturacion_editar_tipo_montos';
        $url_borrar = 'facturacion_borrar_tipo_montos';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_tipo_montoAction($id, Request $request){
        $form = new TipoMontosType('Editar Montos');
        $clase = 'facturacionBundle:TipoMontos';
        $titulo = 'Tipos de Montos para Facturacion';
        $url_redireccion = 'facturacion_agregar_tipo_montos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_tipo_montoAction($id, Request $request){
        $form = new TipoMontosType('Borrar Montos');
        $objeto = 'TipoMontos';
        $clase = 'facturacionBundle:TipoMontos';
        $titulo = 'Tipos de Montos para Facturacion';
        $remover = null;
        $url_redireccion = 'facturacion_agregar_tipo_montos';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_tipo_monto_conceptosAction(Request $request){
        $modelo = New TipoMontoConceptos();
        $form = new TipoMontoConceptosType('Crear Montos');
        $objeto = 'TipoMontos';
        $clase = 'facturacionBundle:TipoMontoConceptos';
        $titulo = 'Montos para Facturacion';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'facturacion_agregar_tipo_montos_conceptos';
        $url_editar = 'facturacion_editar_tipo_montos_conceptos';
        $url_borrar = 'facturacion_borrar_tipo_montos_conceptos';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_tipo_monto_conceptosAction($id, Request $request){
        $form = new TipoMontoConceptosType('Editar Montos');
        $clase = 'facturacionBundle:TipoMontoConceptos';;
        $titulo = 'Montos para Facturacion';
        $url_redireccion = 'facturacion_agregar_tipo_montos_conceptos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function crear_tipo_facturacionAction(Request $request){
        $modelo = New TipoFacturacion();
        $form = new TipoFacturacionType('Crear Tipo de Facturación');
        $objeto = 'TipoFacturacion';
        $clase = 'facturacionBundle:TipoFacturacion';
        $titulo = 'Tipo de Facturación';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_facturacion';
        $url_editar = 'inicial_editar_tipo_facturacion';
        $url_borrar = 'inicial_borrar_tipo_facturacion';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_tipo_facturacionAction($id, Request $request){
        $form = new TipoFacturacionType('Editar Tipo de Facturación');
        $clase = 'facturacionBundle:TipoFacturacion';
        $titulo = 'Tipo de Facturación';
        $url_redireccion = 'inicial_agregar_tipo_facturacion';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_tipo_facturacionAction($id, Request $request){
        $form = new TipoFacturacionType('Borrar Tipo Facturación');
        $objeto = 'TipoFacturacion';
        $clase = 'facturacionBundle:TipoFacturacion';
        $titulo = 'Tipo de Facturación';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_facturacion';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }

}