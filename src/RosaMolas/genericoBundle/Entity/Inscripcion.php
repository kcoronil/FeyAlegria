<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscripcion
 */
class Inscripcion
{
    /**
     * @var string
     */
    private $iscripcion_hash;

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
    private $representantes;

    /**
     * @var string
     */
    private $alumnos;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\usuariosBundle\Entity\Usuarios
     */
    private $usuario;


    /**
     * Set iscripcion_hash
     *
     * @param string $iscripcionHash
     * @return Inscripcion
     */
    public function setIscripcionHash($iscripcionHash)
    {
        $this->iscripcion_hash = $iscripcionHash;

        return $this;
    }

    /**
     * Get iscripcion_hash
     *
     * @return string 
     */
    public function getIscripcionHash()
    {
        return $this->iscripcion_hash;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Inscripcion
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
     * @return Inscripcion
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
     * Set representantes
     *
     * @param string $representantes
     * @return Inscripcion
     */
    public function setRepresentantes($representantes)
    {
        $this->representantes = $representantes;

        return $this;
    }

    /**
     * Get representantes
     *
     * @return string 
     */
    public function getRepresentantes()
    {
        return $this->representantes;
    }

    /**
     * Set alumnos
     *
     * @param string $alumnos
     * @return Inscripcion
     */
    public function setAlumnos($alumnos)
    {
        $this->alumnos = $alumnos;

        return $this;
    }

    /**
     * Get alumnos
     *
     * @return string
     */
    public function getAlumnos()
    {
        return $this->alumnos;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Inscripcion
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
     * Set usuario
     *
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $usuario
     * @return Inscripcion
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
}
