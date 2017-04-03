<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Sexo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RosaMolas\facturacionBundle\Entity\TipoDatoVencimientoRepository")
 */
class TipoDatoVencimiento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_vencimiento", type="string", length=9)
     */
    private $tipoVencimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="dias", type="string", length=9)
     */
    private $dias;


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
     * Set tipoVencimiento
     *
     * @param string $tipoVencimiento
     * @return TipoDatoVencimiento
     */
    public function setTipoVencimiento($tipoVencimiento)
    {
        $this->tipoVencimiento = $tipoVencimiento;

        return $this;
    }

    /**
     * Get tipoVencimiento
     *
     * @return string 
     */
    public function getTipoVencimiento()
    {
        return $this->tipoVencimiento;
    }

    /**
     * Set Dias
     *
     * @param string $dias
     * @return TipoDatoVencimiento
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get Dias
     *
     * @return string
     */
    public function getDias()
    {
        return $this->dias;
    }


    public function __toString()
    {
        return $this->tipoVencimiento;
    }
}
