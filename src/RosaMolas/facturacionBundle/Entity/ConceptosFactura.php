<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConceptosFactura
 */
class ConceptosFactura
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tipoFactura;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tipoFactura = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return ConceptosFactura
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
     * @return ConceptosFactura
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
     * Add tipoFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura
     * @return ConceptosFactura
     */
    public function addTipoFactura(\RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura)
    {
        $this->tipoFactura[] = $tipoFactura;

        return $this;
    }

    /**
     * Remove tipoFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura
     */
    public function removeTipoFactura(\RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura)
    {
        $this->tipoFactura->removeElement($tipoFactura);
    }

    /**
     * Get tipoFactura
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipoFactura()
    {
        return $this->tipoFactura;
    }
}
