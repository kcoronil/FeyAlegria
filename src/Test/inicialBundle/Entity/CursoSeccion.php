<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CursoSeccion
 */
class CursoSeccion
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
     * @var \Test\inicialBundle\Entity\Etapa
     */
    private $etapa;

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
     * @return CursoSeccion
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
     * Set etapa
     *
     * @param \Test\inicialBundle\Entity\Etapa $etapa
     * @return CursoSeccion
     */
    public function setEtapa(\Test\inicialBundle\Entity\Etapa $etapa = null)
    {
        $this->etapa = $etapa;

        return $this;
    }

    /**
     * Get etapa
     *
     * @return \Test\inicialBundle\Entity\Etapa 
     */
    public function getEtapa()
    {
        return $this->etapa;
    }

    /**
     * Set seccion
     *
     * @param \Test\inicialBundle\Entity\Seccion $seccion
     * @return CursoSeccion
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
     * @return CursoSeccion
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
    public function getNombre()
    {
        return $this->getCurso()->getNombre().$this->getSeccion()->getNombre();
    }

    public function __toString()
    {
        return $this->getCurso()->getNombre().$this->getSeccion()->getNombre();
    }
}
