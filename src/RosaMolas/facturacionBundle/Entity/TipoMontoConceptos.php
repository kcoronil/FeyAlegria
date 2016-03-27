<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoMontoConceptos
 */
class TipoMontoConceptos
{
    /**
     * @var string
     */
    private $monto;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\facturacionBundle\Entity\TipoMontos
     */
    private $tipoMonto;

    /**
     * @var \RosaMolas\facturacionBundle\Entity\ConceptosFactura
     */
    private $conceptosFactura;

    /**
     * @var boolean
     */
    private $activo;


    /**
     * Set monto
     *
     * @param string $monto
     * @return TipoMontoConceptos
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipoMonto
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoMontos $tipoMonto
     * @return TipoMontoConceptos
     */
    public function setTipoMonto(\RosaMolas\facturacionBundle\Entity\TipoMontos $tipoMonto = null)
    {
        $this->tipoMonto = $tipoMonto;
        return $this;
    }

    /**
     * Get tipoMonto
     *
     * @return \RosaMolas\facturacionBundle\Entity\TipoMontos 
     */
    public function getTipoMonto()
    {
        return $this->tipoMonto;
    }

    /**
     * Set conceptosFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptosFactura
     * @return TipoMontoConceptos
     */
    public function setConceptosFactura(\RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptosFactura = null)
    {
        $this->conceptosFactura = $conceptosFactura;

        return $this;
    }

    /**
     * Get conceptosFactura
     *
     * @return \RosaMolas\facturacionBundle\Entity\ConceptosFactura 
     */
    public function getConceptosFactura()
    {
        return $this->conceptosFactura;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return TipoMontoConceptos
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

}
