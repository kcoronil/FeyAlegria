<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MontosAlumnos
 */
class MontosAlumnos
{
    /**
     * @var string
     *
     * @Assert\Type(type="numeric",message="el valor {{ value }} no es númerico.")
     *
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
     * @var \RosaMolas\alumnosBundle\Entity\Alumnos
     */
    private $alumno;

    /**
     * @var \Test\inicialBundle\Entity\ConceptosFactura
     */
    private $conceptoFactura;


    /**
     * Set monto
     *
     * @param string $monto
     * @return MontosAlumnos
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
     * @return MontosAlumnos
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
     * Set alumno
     *
     * @param \Test\inicialBundle\Entity\Alumnos $alumno
     * @return MontosAlumnos
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
     * Set conceptoFactura
     *
     * @param \Test\inicialBundle\Entity\ConceptosFactura $conceptoFactura
     * @return MontosAlumnos
     */
    public function setConceptoFactura(\Test\inicialBundle\Entity\ConceptosFactura $conceptoFactura = null)
    {
        $this->conceptoFactura = $conceptoFactura;

        return $this;
    }

    /**
     * Get conceptoFactura
     *
     * @return \Test\inicialBundle\Entity\ConceptosFactura 
     */
    public function getConceptoFactura()
    {
        return $this->conceptoFactura;
    }
}
