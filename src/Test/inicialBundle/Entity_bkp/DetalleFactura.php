<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleFactura
 */
class DetalleFactura
{
    /**
     * @var string
     */
    private $monto;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\Factura
     */
    private $factura;

    /**
     * @var \Test\inicialBundle\Entity\ConceptosFactura
     */
    private $concepto;


    /**
     * Set monto
     *
     * @param string $monto
     * @return DetalleFactura
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return DetalleFactura
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set factura
     *
     * @param \Test\inicialBundle\Entity\Factura $factura
     * @return DetalleFactura
     */
    public function setFactura(\Test\inicialBundle\Entity\Factura $factura = null)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return \Test\inicialBundle\Entity\Factura 
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set concepto
     *
     * @param \Test\inicialBundle\Entity\ConceptosFactura $concepto
     * @return DetalleFactura
     */
    public function setConcepto(\Test\inicialBundle\Entity\ConceptosFactura $concepto = null)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return \Test\inicialBundle\Entity\ConceptosFactura 
     */
    public function getConcepto()
    {
        return $this->concepto;
    }
}
