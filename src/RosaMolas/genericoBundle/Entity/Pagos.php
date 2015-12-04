<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pagos
 */
class Pagos
{
    /**
     * @var \DateTime
     */
    private $fechaDeposito;

    /**
     * @var \DateTime
     */
    private $fechaRegistro;

    /**
     * @var string
     *
     * @Assert\Length(min = 20, max = 20,
     * minMessage = "Este campo debe tener {{ limit }} carácteres",
     * maxMessage = "Este campo debe tener {{ limit }} carácteres")
     * @Assert\Type(type="numeric",message="el valor {{ value }} no es númerico.")
     *
     */
    private $numeroDeposito;

    /**
     * @var string
     * @Assert\Type(type="numeric",message="el valor {{ value }} no es númerico.")
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
     * @var \RosaMolas\genericoBundle\Entity\Bancos
     */
    private $banco;


    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return Pagos
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaDeposito = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }



    /**
     * Set fechaDeposito
     *
     * @param \DateTime $fechaDeposito
     * @return Pagos
     */
    public function setFechaDeposito($fechaDeposito)
    {
        $this->fechaDeposito = $fechaDeposito;

        return $this;
    }

    /**
     * Get fechaDeposito
     *
     * @return \DateTime 
     */
    public function getFechaDeposito()
    {
        return $this->fechaDeposito;
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
     * @param \RosaMolas\genericoBundle\Entity\Bancos $banco
     * @return Pagos
     */
    public function setBanco(\RosaMolas\genericoBundle\Entity\Bancos $banco = null)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return \RosaMolas\genericoBundle\Entity\Bancos
     */
    public function getBanco()
    {
        return $this->banco;
    }
}
