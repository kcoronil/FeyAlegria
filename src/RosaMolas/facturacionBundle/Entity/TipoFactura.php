<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoFactura
 */
class TipoFactura
{
    /**
     * @var string
     */
    private $nombre;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\facturacionBundle\Entity\TipoDatoVencimiento
     */
    private $tipoDatoVencimiento;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $conceptosFactura;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conceptosFactura = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TipoFactura
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return TipoFactura
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
     * Add conceptosFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptosFactura
     * @return TipoFactura
     */
    public function addConceptosFacturon(\RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptosFactura)
    {
        $this->conceptosFactura[] = $conceptosFactura;

        return $this;
    }

    /**
     * Remove conceptosFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptosFactura
     */
    public function removeConceptosFacturon(\RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptosFactura)
    {
        $this->conceptosFactura->removeElement($conceptosFactura);
    }

    /**
     * Get conceptosFactura
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConceptosFactura()
    {
        return $this->conceptosFactura;
    }

    /**
     * Set tipoDatoVencimiento
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoDatoVencimiento $tipoDatoVencimiento
     * @return TipoFactura
     */
    public function setTipoDatoVencimiento(\RosaMolas\facturacionBundle\Entity\TipoDatoVencimiento $tipoDatoVencimiento = null)
    {
        $this->tipoDatoVencimiento = $tipoDatoVencimiento;

        return $this;
    }

    /**
     * Get tipoDatoVencimiento
     *
     * @return \RosaMolas\facturacionBundle\Entity\TipoDatoVencimiento
     */
    public function getTipoDatoVencimiento()
    {
        return $this->tipoDatoVencimiento;
    }





    public function __toString(){
        return $this->nombre;
    }
}
