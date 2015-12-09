<?php

namespace RosaMolas\alumnosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoEscolarCurso
 */
class PeriodoEscolarCursoAlumno
{
    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\PeriodoEscolar
     */
    private $periodoEscolar;

    /**
     * @var \Test\inicialBundle\Entity\CursoSeccion
     */
    private $cursoSeccion;

    /**
     * @ORM\ManyToOne(targetEntity="Alumnos", inversedBy="periodoEscolarCursoAlumno" , cascade={"all"})
     * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id", nullable=False)
     */
    private $alumno;


    /**
     * Set activo
     *
     * @param boolean $activo
     * @return PeriodoEscolarCursoAlumno
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
     * Set periodoEscolar
     *
     * @param \Test\inicialBundle\Entity\PeriodoEscolar $periodoEscolar
     * @return PeriodoEscolarCursoAlumno
     */
    public function setPeriodoEscolar(\Test\inicialBundle\Entity\PeriodoEscolar $periodoEscolar = null)
    {
        $this->periodoEscolar = $periodoEscolar;

        return $this;
    }

    /**
     * Get periodoEscolar
     *
     * @return \Test\inicialBundle\Entity\PeriodoEscolar
     */
    public function getPeriodoEscolar()
    {
        return $this->periodoEscolar;
    }

    /**
     * Set cursoSeccion
     *
     * @param \Test\inicialBundle\Entity\CursoSeccion $cursoSeccion
     * @return PeriodoEscolarCursoAlumno
     */
    public function setCursoSeccion(\Test\inicialBundle\Entity\CursoSeccion $cursoSeccion = null)
    {
        $this->cursoSeccion = $cursoSeccion;

        return $this;
    }

    /**
     * Get cursoSeccion
     *
     * @return \Test\inicialBundle\Entity\CursoSeccion
     */
    public function getCursoSeccion()
    {
        return $this->cursoSeccion;
    }

    /**
     * Set alumno
     *
     * @param \RosaMolas\alumnosBundle\Entity\Alumnos $alumno
     * @return PeriodoEscolarCursoAlumno
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
    public function __toString()
    {
        return $this->getCursoSeccion()->getCurso()->getNombre().$this->getCursoSeccion()->getSeccion()->getNombre();
    }
}
