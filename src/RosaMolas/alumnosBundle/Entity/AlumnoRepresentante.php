<?php

namespace RosaMolas\alumnosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlumnoRepresentante
 */
class AlumnoRepresentante
{
    /**
     * @var boolean
     */
    private $principal;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\genericoBundle\Entity\Parentescos
     */
    private $parentesco;

    /**
     * @var \RosaMolas\usuariosBundle\Entity\Usuarios
     */
    private $representante;

    /**
     * @var \RosaMolas\alumnosBundle\Entity\Alumnos
     */
    private $alumno;


    /**
     * Set principal
     *
     * @param boolean $principal
     * @return AlumnoRepresentante
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
     * @return AlumnoRepresentante
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
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $representante
     * @return AlumnoRepresentante
     */
    public function setRepresentante(\RosaMolas\usuariosBundle\Entity\Usuarios $representante = null)
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
     * @param \RosaMolas\alumnosBundle\Entity\Alumnos $alumno
     * @return AlumnoRepresentante
     */
    public function setAlumno(\RosaMolas\alumnosBundle\Entity\Alumnos $alumno = null)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return \RosaMolas\alumnosBundle\Entity\Alumnos 
     */
    public function getAlumno()
    {
        return $this->alumno;
    }
}
