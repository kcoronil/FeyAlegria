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
    private $tipoMontoConceptos;

    /**
     * @var \RosaMolas\facturacionBundle\Entity\TipoFactura
     */
    private $tipoFactura;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $montosAlumnos;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tipoMontoConceptos = new \Doctrine\Common\Collections\ArrayCollection();
//        $this->tipoFactura = new \Doctrine\Common\Collections\ArrayCollection();
        $this->montosAlumnos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add tipoMontoConceptos
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoMontoConceptos $tipoMontoConceptos
     * @return ConceptosFactura
     */
    public function addTipoMontoConcepto(\RosaMolas\facturacionBundle\Entity\TipoMontoConceptos $tipoMontoConceptos)
    {
        $this->tipoMontoConceptos[] = $tipoMontoConceptos;

        return $this;
    }

    /**
     * Remove tipoMontoConceptos
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoMontoConceptos $tipoMontoConceptos
     */
    public function removeTipoMontoConcepto(\RosaMolas\facturacionBundle\Entity\TipoMontoConceptos $tipoMontoConceptos)
    {
        $this->tipoMontoConceptos->removeElement($tipoMontoConceptos);
    }

    /**
     * Get tipoMontoConceptos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipoMontoConceptos()
    {
        return $this->tipoMontoConceptos;
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
     * @return \RosaMolas\facturacionBundle\Entity\TipoFactura
     */
    public function getTipoFactura()
    {
        return $this->tipoFactura;
    }

    /**
     * Add montosAlumnos
     *
     * @param \RosaMolas\facturacionBundle\Entity\MontosAlumnos $montosAlumnos
     * @return ConceptosFactura
     */
    public function addMontosAlumno(\RosaMolas\facturacionBundle\Entity\MontosAlumnos $montosAlumnos)
    {
        $this->montosAlumnos[] = $montosAlumnos;

        return $this;
    }

    /**
     * Remove montosAlumnos
     *
     * @param \RosaMolas\facturacionBundle\Entity\MontosAlumnos $montosAlumnos
     */
    public function removemontosAlumno(\RosaMolas\facturacionBundle\Entity\MontosAlumnos $montosAlumnos)
    {
        $this->montosAlumnos->removeElement($montosAlumnos);
    }

    /**
     * Get montosAlumnos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMontosAlumnos()
    {
        return $this->montosAlumnos;
    }

    public function __toString()
    {
        return $this->nombre;
    }

}
