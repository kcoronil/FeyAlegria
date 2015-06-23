<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Usuarios
 */
class Usuarios
{

    /**
     * @ORM\OneToMany(targetEntity="Noticias", mappedBy="categoria")
     */

    protected $noticias;

    public function __construct(){
        $this->$noticias = new ArrayCollection();
    }
    /**
     * @ORM\Column(type="integer")
     */
    private $cedula;

    /**
     * @ORM\Column(type="string", length=100)
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
    private $direccion;

    /**
     * @var boolean
     */
    private $principal = false;

    /**
     * @var boolean
     */
    private $activo = true;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\Sexo
     */
    private $sexo;

    /**
     * @var \Test\inicialBundle\Entity\TipoUsuario
     */
    private $tipoUsuario;


    /**
     * Set cedula
     *
     * @param integer $cedula
     * @return Usuarios
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuarios
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
     * @return Usuarios
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
     * @return Usuarios
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
     * Set direccion
     *
     * @param string $direccion
     * @return Usuarios
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set principal
     *
     * @param boolean $principal
     * @return Usuarios
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
     * @return Usuarios
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
     * @return Usuarios
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

    /**
     * Set tipoUsuario
     *
     * @param \Test\inicialBundle\Entity\TipoUsuario $tipoUsuario
     * @return Usuarios
     */
    public function setTipoUsuario(\Test\inicialBundle\Entity\TipoUsuario $tipoUsuario = null)
    {
        $this->tipoUsuario = $tipoUsuario;

        return $this;
    }

    /**
     * Get tipoUsuario
     *
     * @return \Test\inicialBundle\Entity\TipoUsuario 
     */
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }
}
