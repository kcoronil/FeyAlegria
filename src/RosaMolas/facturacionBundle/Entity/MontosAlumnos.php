<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var \RosaMolas\facturacionBundle\Entity\ConceptosFactura
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
     * @param \RosaMolas\alumnosBundle\Entity\Alumnos $alumno
     * @return MontosAlumnos
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
     * Set conceptoFactura
     *
     * @param \RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptoFactura
     * @return MontosAlumnos
     */
    public function setConceptoFactura(\RosaMolas\facturacionBundle\Entity\ConceptosFactura $conceptoFactura = null)
    {
        $this->conceptoFactura = $conceptoFactura;

        return $this;
    }

    /**
     * Get conceptoFactura
     *
     * @return \RosaMolas\facturacionBundle\Entity\ConceptosFactura
     */
    public function getConceptoFactura()
    {
        return $this->conceptoFactura;
    }
}
