<?php
namespace RosaMolas\genericoBundle\Controller;
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

class InscripcionController extends Controller
{
    public function generar_facturasAction(request $request){


        return $this->render('facturacionBundle:Default:crear_factura.html.twig', array('form'=>$formulario->createView(),
            'accion'=>'Crear Factura',));
    }
}