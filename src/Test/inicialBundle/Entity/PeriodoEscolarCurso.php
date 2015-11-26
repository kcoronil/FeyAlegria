<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\alumnosBundle\Entity\PeriodoEscolarAlumno;


/**
 * PeriodoEscolarCurso
 */
class PeriodoEscolarCurso
{
    /**
     * @ORM\ManyToOne(targetEntity="PeriodoEscolar", inversedBy="grado")
     * @ORM\JoinColumn(name="periodo_escolar_id", referencedColumnName="id")
     */

    /**
     * @ORM\ManyToOne(targetEntity="Seccion", inversedBy="grado")
     * @ORM\JoinColumn(name="seccion_id", referencedColumnName="id")
     */

    /**
     * @ORM\ManyToOne(targetEntity="Curso", inversedBy="grado")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     */

    /**
     * @ORM\OneToMany(targetEntity="PeriodoEscolarAlumno", mappedBy="periodoEscolarCurso", cascade={"all"}, orphanRemove=TRUE)
     */

    protected $periodoEscolarAlumno;

    public function __construct() {
        $this->periodoEscolarAlumno = new ArrayCollection();
    }

    public function getPeriodoEscolarAlumno()
    {
        return $this->periodoEscolarAlumno->toArray();
    }

    public function addPeriodoEscolarAlumno(PeriodoEscolarAlumno $periodoEscolarAlumno)
    {
        if(!$this->periodoEscolarAlumno->contains($periodoEscolarAlumno)){
            $this->periodoEscolarAlumno->add($periodoEscolarAlumno);
            $periodoEscolarAlumno->setPeriodoEscolarCurso($this);
        }
        return $this;
    }

    public function removePeriodoEscolarAlumno(PeriodoEscolarAlumno $periodoEscolarAlumno)
    {
        if($this->periodoEscolarAlumno->contains($periodoEscolarAlumno)){
            $this->periodoEscolarAlumno->removeElement($periodoEscolarAlumno);
            $periodoEscolarAlumno->setPeriodoEscolarCurso(null);
        }
        return $this;
    }

    public function getAlumno()
    {
        return array_map(
            function($periodoEscolarAlumno){
                return $periodoEscolarAlumno->getAlumno();
            },
            $this->periodoEscolarAlumno->toArray()
        );
    }
    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\CursoSeccion
     */
    private $cursoSeccion;

    /**
     * @var \Test\inicialBundle\Entity\PeriodoEscolar
     */
    private $periodoEscolar;


    /**
     * Set activo
     *
     * @param boolean $activo
     * @return PeriodoEscolarCurso
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
     * Set cursoSeccion
     *
     * @param \Test\inicialBundle\Entity\CursoSeccion $cursoSeccion
     * @return PeriodoEscolarCurso
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
     * Set periodoEscolar
     *
     * @param \Test\inicialBundle\Entity\PeriodoEscolar $periodoEscolar
     * @return PeriodoEscolarCurso
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
    public function __toString()
    {
        return $this->cursoSeccion->getCurso()->getNombre().$this->cursoSeccion->getSeccion()->getNombre();
    }
}
