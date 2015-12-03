<?php

namespace RosaMolas\facturacionBundle\Controller;

use RosaMolas\facturacionBundle\Entity\ConceptosFactura;
use RosaMolas\facturacionBundle\Entity\TipoMontoConceptos;
use RosaMolas\facturacionBundle\Entity\TipoMontos;
use RosaMolas\facturacionBundle\Form\TipoMontoConceptosType;
use RosaMolas\facturacionBundle\Form\TipoMontosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\facturacionBundle\Entity\TipoFactura;
use RosaMolas\facturacionBundle\Form\TipoFacturaType;


class DefaultController extends Controller
{
    public function crear_tipo_facturaAction(Request $request){
        $modelo = New TipoFactura();
        $concepto = New ConceptosFactura();
        $modelo->addConceptosFacturon($concepto);
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
}
