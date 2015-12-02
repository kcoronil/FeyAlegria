<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PeriodoEscolarAlumno
 */
class PeriodoEscolarAlumno
{
    /**
     * @ORM\ManyToOne(targetEntity="PeriodoEscolarCurso", inversedBy="periodoEscolarAlumno", cascade={"all"})
     * @ORM\JoinColumn(name="periodo_escolar_curso_id", referencedColumnName="id", nullable=False)
     */

    protected $periodoEscolarCurso;

    /**
     * @ORM\ManyToOne(targetEntity="Alumnos", inversedBy="periodoEscolarAlumno" , cascade={"all"})
     * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id", nullable=False)
     */

    protected $alumno;

    /**
     * @var boolean
     */
    private $activo = true;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set activo
     *
     * @param boolean $activo
     * @return PeriodoEscolarAlumno
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
     * @return PeriodoEscolarAlumno
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
     * Set periodoEscolarCurso
     *
     * @param \Test\inicialBundle\Entity\PeriodoEscolarCurso $periodoEscolarCurso
     * @return PeriodoEscolarAlumno
     */
    public function setPeriodoEscolarCurso(\Test\inicialBundle\Entity\PeriodoEscolarCurso $periodoEscolarCurso = null)
    {
        $this->periodoEscolarCurso = $periodoEscolarCurso;

        return $this;
    }

    /**
     * Get periodoEscolarCurso
     *
     * @return \Test\inicialBundle\Entity\PeriodoEscolarCurso 
     */
    public function getPeriodoEscolarCurso()
    {
        return $this->periodoEscolarCurso;
    }

    public function __toString()
    {
        return $this->getPeriodoEscolarCurso()->getCurso()->getNombre().$this->getPeriodoEscolarCurso()->getSeccion()->getNombre();
    }
}
