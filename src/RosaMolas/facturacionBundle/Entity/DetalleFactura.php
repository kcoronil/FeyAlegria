<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DetalleFactura
 */
class DetalleFactura
{
    /**
     * @var string
     *
     * @Assert\Type(type="numeric",message="el valor {{ value }} no es númerico.")
     *
     * @ORM\Column(name="cedula", type="integer", nullable=true)
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
     * @var \RosaMolas\facturacionBundle\Entity\Factura
     */
    private $factura;

    /**
     * @var \RosaMolas\facturacionBundle\Entity\ConceptosFactura
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
     * @param \RosaMolas\facturacionBundle\Entity\Factura $factura
     * @return DetalleFactura
     */
    public function setFactura(\RosaMolas\facturacionBundle\Entity\Factura $factura = null)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return \RosaMolas\facturacionBundle\Entity\Factura
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set concepto
     *
     * @param \RosaMolas\facturacionBundle\Entity\ConceptosFactura $concepto
     * @return DetalleFactura
     */
    public function setConcepto(\RosaMolas\facturacionBundle\Entity\ConceptosFactura $concepto = null)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return \RosaMolas\facturacionBundle\Entity\ConceptosFactura
     */
    public function getConcepto()
    {
        return $this->concepto;
    }
}
