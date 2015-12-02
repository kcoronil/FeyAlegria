<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Factura
 */
class Factura
{
    /**
     * @var string
     */
    private $nombre;

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
     * @var \Test\inicialBundle\Entity\PeriodoEscolarAlumno
     */
    private $periodoEscolarAlumnos;

    /**
     * @var \Test\inicialBundle\Entity\TipoFactura
     */
    private $tipoFactura;


    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Factura
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
     * Set monto
     *
     * @param string $monto
     * @return Factura
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
     * @return Factura
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
     * Set periodoEscolarAlumnos
     *
     * @param \Test\inicialBundle\Entity\PeriodoEscolarAlumno $periodoEscolarAlumnos
     * @return Factura
     */
    public function setPeriodoEscolarAlumnos(\Test\inicialBundle\Entity\PeriodoEscolarAlumno $periodoEscolarAlumnos = null)
    {
        $this->periodoEscolarAlumnos = $periodoEscolarAlumnos;

        return $this;
    }

    /**
     * Get periodoEscolarAlumnos
     *
     * @return \Test\inicialBundle\Entity\PeriodoEscolarAlumno 
     */
    public function getPeriodoEscolarAlumnos()
    {
        return $this->periodoEscolarAlumnos;
    }

    /**
     * Set tipoFactura
     *
     * @param \Test\inicialBundle\Entity\TipoFactura $tipoFactura
     * @return Factura
     */
    public function setTipoFactura(\Test\inicialBundle\Entity\TipoFactura $tipoFactura = null)
    {
        $this->tipoFactura = $tipoFactura;

        return $this;
    }

    /**
     * Get tipoFactura
     *
     * @return \Test\inicialBundle\Entity\TipoFactura 
     */
    public function getTipoFactura()
    {
        return $this->tipoFactura;
    }
}
