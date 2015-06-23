<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pagos
 */
class Pagos
{
    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $numeroDeposito;

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
     * @var \Test\inicialBundle\Entity\Bancos
     */
    private $banco;


    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Pagos
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
     * Set numeroDeposito
     *
     * @param string $numeroDeposito
     * @return Pagos
     */
    public function setNumeroDeposito($numeroDeposito)
    {
        $this->numeroDeposito = $numeroDeposito;

        return $this;
    }

    /**
     * Get numeroDeposito
     *
     * @return string 
     */
    public function getNumeroDeposito()
    {
        return $this->numeroDeposito;
    }

    /**
     * Set monto
     *
     * @param string $monto
     * @return Pagos
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
     * @return Pagos
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
     * @return Pagos
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
     * Set banco
     *
     * @param \Test\inicialBundle\Entity\Bancos $banco
     * @return Pagos
     */
    public function setBanco(\Test\inicialBundle\Entity\Bancos $banco = null)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return \Test\inicialBundle\Entity\Bancos 
     */
    public function getBanco()
    {
        return $this->banco;
    }
}
