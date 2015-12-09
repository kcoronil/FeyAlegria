<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
    private $activo = true;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tipoFactura;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    public $tipoMontoConceptos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tipoFactura = new ArrayCollection();
        $this->tipoMontoConceptos = new ArrayCollection();
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

    /**
     * Set tipoFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura
     * @return ConceptosFactura
     */
    public function setTipoFactura(\RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura = null)
    {
        $test_tipofact = New ArrayCollection();
        $test_tipofact->add($tipoFactura);
        $this->tipoFactura = $test_tipofact;

        return $this;
    }

    public function getTipoMontoConceptos()
    {
        return $this->tipoMontoConceptos;
    }

    /*public function setTipoMontoConceptos(TipoMontoConceptos $tipoMontoConceptos = null)
    {
        $this->tipoMontoConceptos[] = $tipoMontoConceptos;
    }*/

    /**
     * Add tipoMontoConceptos
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoMontoConceptos $tipoMontoConceptos
     * @return ConceptosFactura
     */

    public function addTipoMontoConceptos(TipoMontoConceptos $tipoMontoConceptos)
    {
        $tipoMontoConceptos->setConceptosFactura($this);
        $this->tipoMontoConceptos[] = $tipoMontoConceptos;
    }

    /**
     * Remove tipoMontoConceptos
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoMontoConceptos $tipoMontoConceptos
     */
    public function removePeriodoEscolarCursoAlumno(TipoMontoConceptos $tipoMontoConceptos)
    {
        $this->tipoMontoConceptos->removeElement($tipoMontoConceptos);
    }
}
