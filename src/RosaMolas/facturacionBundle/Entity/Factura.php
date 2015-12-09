<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Factura
 */
class Factura
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
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var boolean
     */
    private $pagada;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $detalleFactura;

    /**
     * @var \RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno
     */
    private $periodoEscolarCursoAlumnos;

    /**
     * @var \RosaMolas\facturacionBundle\Entity\TipoFactura
     */
    private $tipoFactura;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detalleFactura = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Factura
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set pagada
     *
     * @param boolean $pagada
     * @return Factura
     */
    public function setPagada($pagada)
    {
        $this->pagada = $pagada;

        return $this;
    }

    /**
     * Get pagada
     *
     * @return boolean 
     */
    public function getPagada()
    {
        return $this->pagada;
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
     * Add detalleFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\DetalleFactura $detalleFactura
     * @return Factura
     */
    public function addDetalleFactura(\RosaMolas\facturacionBundle\Entity\DetalleFactura $detalleFactura)
    {
        $this->detalleFactura[] = $detalleFactura;

        return $this;
    }

    /**
     * Remove detalleFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\DetalleFactura $detalleFactura
     */
    public function removeDetalleFactura(\RosaMolas\facturacionBundle\Entity\DetalleFactura $detalleFactura)
    {
        $this->detalleFactura->removeElement($detalleFactura);
    }

    /**
     * Get detalleFactura
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDetalleFactura()
    {
        return $this->detalleFactura;
    }

    /**
     * Set periodoEscolarCursoAlumnos
     *
     * @param \RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno $periodoEscolarCursoAlumnos
     * @return Factura
     */
    public function setPeriodoEscolarCursoAlumnos(\RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno $periodoEscolarCursoAlumnos = null)
    {
        $this->periodoEscolarCursoAlumnos = $periodoEscolarCursoAlumnos;

        return $this;
    }

    /**
     * Get periodoEscolarCursoAlumnos
     *
     * @return \RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno 
     */
    public function getPeriodoEscolarCursoAlumnos()
    {
        return $this->periodoEscolarCursoAlumnos;
    }

    /**
     * Set tipoFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura
     * @return Factura
     */
    public function setTipoFactura(\RosaMolas\facturacionBundle\Entity\TipoFactura $tipoFactura = null)
    {
        $this->tipoFactura = $tipoFactura;

        return $this;
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
}
