<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoEscolarFinalizado
 */
class PeriodoEscolarFinalizado
{
    /**
     * @var string
     */
    private $proceso_hash;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var integer
     */
    private $estatus;

    /**
     * @var string
     */
    private $alumnosReprobados;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var boolean
     */
    private $finalizado;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\usuariosBundle\Entity\Usuarios
     */
    private $usuario;

    /**
     * @var \Test\inicialBundle\Entity\PeriodoEscolar
     */
    private $periodoEscolarFinalizado;

    /**
     * @var \Test\inicialBundle\Entity\PeriodoEscolar
     */
    private $periodoEscolarIniciado;


    /**
     * Set proceso_hash
     *
     * @param string $procesoHash
     * @return PeriodoEscolarFinalizado
     */
    public function setProcesoHash($procesoHash)
    {
        $this->proceso_hash = $procesoHash;

        return $this;
    }

    /**
     * Get proceso_hash
     *
     * @return string 
     */
    public function getProcesoHash()
    {
        return $this->proceso_hash;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return PeriodoEscolarFinalizado
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
     * Set estatus
     *
     * @param integer $estatus
     * @return PeriodoEscolarFinalizado
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get estatus
     *
     * @return integer 
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set alumnosReprobados
     *
     * @param string $alumnosReprobados
     * @return PeriodoEscolarFinalizado
     */
    public function setAlumnosReprobados($alumnosReprobados)
    {
        $this->alumnosReprobados = $alumnosReprobados;

        return $this;
    }

    /**
     * Get alumnosReprobados
     *
     * @return string 
     */
    public function getAlumnosReprobados()
    {
        return $this->alumnosReprobados;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return PeriodoEscolarFinalizado
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
     * Set finalizado
     *
     * @param boolean $finalizado
     * @return PeriodoEscolarFinalizado
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;

        return $this;
    }

    /**
     * Get finalizado
     *
     * @return boolean
     */
    public function getFinalizado()
    {
        return $this->finalizado;
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
     * Set usuario
     *
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $usuario
     * @return PeriodoEscolarFinalizado
     */
    public function setUsuario(\RosaMolas\usuariosBundle\Entity\Usuarios $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \RosaMolas\usuariosBundle\Entity\Usuarios
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set periodoEscolarIniciado
     *
     * @param \Test\inicialBundle\Entity\PeriodoEscolar $periodoEscolarIniciado
     * @return PeriodoEscolarFinalizado
     */
    public function setPeriodoEscolarIniciado(\Test\inicialBundle\Entity\PeriodoEscolar $periodoEscolarIniciado = null)
    {
        $this->periodoEscolarIniciado = $periodoEscolarIniciado;

        return $this;
    }

    /**
     * Get periodoEscolarIniciado
     *
     * @return \Test\inicialBundle\Entity\PeriodoEscolar
     */
    public function getPeriodoEscolarIniciado()
    {
        return $this->periodoEscolarIniciado;
    }

    /**
     * Set periodoEscolarFinalizado
     *
     * @param \Test\inicialBundle\Entity\PeriodoEscolar $periodoEscolarFinalizado
     * @return PeriodoEscolarFinalizado
     */
    public function setPeriodoEscolarFinalizado(\Test\inicialBundle\Entity\PeriodoEscolar $periodoEscolarFinalizado = null)
    {
        $this->periodoEscolarFinalizado = $periodoEscolarFinalizado;

        return $this;
    }

    /**
     * Get periodoEscolarFinalizado
     *
     * @return \Test\inicialBundle\Entity\PeriodoEscolar
     */
    public function getPeriodoEscolarFinalizado()
    {
        return $this->periodoEscolarFinalizado;
    }
}
