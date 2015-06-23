<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alumnos
 */
class Alumnos
{
    /**
     * @var integer
     */
    private $cedula;

    /**
     * @var string
     */
    private $cedulaEstudiantil;

    /**
     * @var string
     */
    private $apellidos;

    /**
     * @var string
     */
    private $nombres;

    /**
     * @var \DateTime
     */
    private $fechaNacimiento;

    /**
     * @var string
     */
    private $lugarNacimiento;

    /**
     * @var boolean
     */
    private $principal;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\Sexo
     */
    private $sexo;


    /**
     * Set cedula
     *
     * @param integer $cedula
     * @return Alumnos
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set cedulaEstudiantil
     *
     * @param string $cedulaEstudiantil
     * @return Alumnos
     */
    public function setCedulaEstudiantil($cedulaEstudiantil)
    {
        $this->cedulaEstudiantil = $cedulaEstudiantil;

        return $this;
    }

    /**
     * Get cedulaEstudiantil
     *
     * @return string 
     */
    public function getCedulaEstudiantil()
    {
        return $this->cedulaEstudiantil;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Alumnos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return Alumnos
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Alumnos
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set lugarNacimiento
     *
     * @param string $lugarNacimiento
     * @return Alumnos
     */
    public function setLugarNacimiento($lugarNacimiento)
    {
        $this->lugarNacimiento = $lugarNacimiento;

        return $this;
    }

    /**
     * Get lugarNacimiento
     *
     * @return string 
     */
    public function getLugarNacimiento()
    {
        return $this->lugarNacimiento;
    }

    /**
     * Set principal
     *
     * @param boolean $principal
     * @return Alumnos
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Get principal
     *
     * @return boolean 
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Alumnos
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
     * Set sexo
     *
     * @param \Test\inicialBundle\Entity\Sexo $sexo
     * @return Alumnos
     */
    public function setSexo(\Test\inicialBundle\Entity\Sexo $sexo = null)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return \Test\inicialBundle\Entity\Sexo 
     */
    public function getSexo()
    {
        return $this->sexo;
    }
}
