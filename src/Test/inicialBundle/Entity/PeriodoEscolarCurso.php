<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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

    /*public function getperiodoEscolarAlumno()
    {
        return $this->periodoEscolarAlumno;
    }


    public function addperiodoEscolarAlumno(PeriodoEscolarAlumno $periodoEscolarAlumno)
    {
        $periodoEscolarAlumno->setPeriodoEscolarCurso($this);

        $this->periodoEscolarAlumno = $periodoEscolarAlumno;
    }*/
    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */

    /**
     * @var \Test\inicialBundle\Entity\PeriodoEscolar
     */
    private $periodoEscolar;

    /**
     * @var \Test\inicialBundle\Entity\Seccion
     */
    private $seccion;

    /**
     * @var \Test\inicialBundle\Entity\Curso
     */
    private $curso;


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

    /**
     * Set seccion
     *
     * @param \Test\inicialBundle\Entity\Seccion $seccion
     * @return PeriodoEscolarCurso
     */
    public function setSeccion(\Test\inicialBundle\Entity\Seccion $seccion = null)
    {
        $this->seccion = $seccion;

        return $this;
    }

    /**
     * Get seccion
     *
     * @return \Test\inicialBundle\Entity\Seccion 
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * Set curso
     *
     * @param \Test\inicialBundle\Entity\Curso $curso
     * @return PeriodoEscolarCurso
     */
    public function setCurso(\Test\inicialBundle\Entity\Curso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \Test\inicialBundle\Entity\Curso 
     */
    public function getCurso()
    {
        return $this->curso;
    }

    public function __toString()
    {
        return $this->curso->getNombre().$this->seccion->getNombre();
    }
}
