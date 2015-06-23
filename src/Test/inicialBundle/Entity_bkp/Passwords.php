<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passwords
 */
class Passwords
{
    /**
     * @var string
     */
    private $password;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\PerfilUsuario
     */
    private $usuario;


    /**
     * Set password
     *
     * @param string $password
     * @return Passwords
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Passwords
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Passwords
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
     * Set usuario
     *
     * @param \Test\inicialBundle\Entity\PerfilUsuario $usuario
     * @return Passwords
     */
    public function setUsuario(\Test\inicialBundle\Entity\PerfilUsuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Test\inicialBundle\Entity\PerfilUsuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
