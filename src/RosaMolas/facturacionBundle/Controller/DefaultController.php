<?php

namespace RosaMolas\facturacionBundle\Controller;

use RosaMolas\facturacionBundle\Entity\TipoMontoConceptos;
use RosaMolas\facturacionBundle\Entity\TipoMontos;
use RosaMolas\facturacionBundle\Form\TipoMontoConceptosType;
use RosaMolas\facturacionBundle\Form\TipoMontosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('facturacionBundle:Default:index.html.twig', array('name' => $name));
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
}
