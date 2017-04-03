<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InscripcionAlumnoRepresentanteDatos
 */
class InscripcionAlumnoRepresentanteDatos
{
    /**
     * @var boolean
     */
    private $principal = false;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\genericoBundle\Entity\Parentescos
     */
    private $parentesco;

    /**
     * @var \RosaMolas\genericoBundle\Entity\InscripcionUsuarios
     */
    private $representante;

    /**
     * @var \RosaMolas\genericoBundle\Entity\InscripcionAlumnos
     */
    private $alumno;


    /**
     * Set principal
     *
     * @param boolean $principal
     * @return InscripcionAlumnoRepresentanteDatos
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Get principal
     *
     * @return boolean 
     */
    public function getPrincipal()
    {
        return $this->principal;
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
     * Set parentesco
     *
     * @param \RosaMolas\genericoBundle\Entity\Parentescos $parentesco
     * @return InscripcionAlumnoRepresentanteDatos
     */
    public function setParentesco(\RosaMolas\genericoBundle\Entity\Parentescos $parentesco = null)
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    /**
     * Get parentesco
     *
     * @return \RosaMolas\genericoBundle\Entity\Parentescos 
     */
    public function getParentesco()
    {
        return $this->parentesco;
    }

    /**
     * Set representante
     *
     * @param \RosaMolas\genericoBundle\Entity\InscripcionUsuarios $representante
     * @return InscripcionAlumnoRepresentanteDatos
     */
    public function setRepresentante(\RosaMolas\genericoBundle\Entity\InscripcionUsuarios $representante = null)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return \RosaMolas\usuariosBundle\Entity\Usuarios 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * Set alumno
     *
     * @param \RosaMolas\genericoBundle\Entity\InscripcionAlumnos $alumno
     * @return InscripcionAlumnoRepresentanteDatos
     */
    public function setAlumno(\RosaMolas\genericoBundle\Entity\InscripcionAlumnos $alumno = null)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return \RosaMolas\genericoBundle\Entity\InscripcionAlumnos
     */
    public function getAlumno()
    {
        return $this->alumno;
    }
}
