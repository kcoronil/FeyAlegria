<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlumnosRepresentantes
 */
class AlumnosRepresentantes
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\Alumnos
     */
    private $alumno;

    /**
     * @var \Test\inicialBundle\Entity\Usuarios
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
     * Set alumno
     *
     * @param \Test\inicialBundle\Entity\Alumnos $alumno
     * @return AlumnosRepresentantes
     */
    public function setAlumno(\Test\inicialBundle\Entity\Alumnos $alumno = null)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return \Test\inicialBundle\Entity\Alumnos 
     */
    public function getAlumno()
    {
        return $this->alumno;
    }

    /**
     * Set representante
     *
     * @param \Test\inicialBundle\Entity\Usuarios $representante
     * @return AlumnosRepresentantes
     */
    public function setRepresentante(\Test\inicialBundle\Entity\Usuarios $representante = null)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return \Test\inicialBundle\Entity\Usuarios 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }
}
