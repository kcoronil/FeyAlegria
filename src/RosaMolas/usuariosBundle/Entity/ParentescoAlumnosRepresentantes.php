<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParentescoAlumnosRepresentantes
 */
class ParentescoAlumnosRepresentantes
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\genericoBundle\Entity\Parentescos
     */
    private $parentesco;

    /**
     * @var \RosaMolas\alumnosBundle\Entity\Alumnos
     */
    private $alumno;

    /**
     * @var \RosaMolas\usuariosBundle\Entity\Usuarios
     */
    private $representante;


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
     * @return ParentescoAlumnosRepresentantes
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
     * Set alumno
     *
     * @param \RosaMolas\alumnosBundle\Entity\Alumnos $alumno
     * @return ParentescoAlumnosRepresentantes
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

    /**
     * Set representante
     *
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $representante
     * @return ParentescoAlumnosRepresentantes
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
}
