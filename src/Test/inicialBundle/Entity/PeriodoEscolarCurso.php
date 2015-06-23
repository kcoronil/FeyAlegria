<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodoEscolarCurso
 */
class PeriodoEscolarCurso
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
}
