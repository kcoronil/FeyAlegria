<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PerfilUsuario
 */
class PerfilUsuario
{
    /**
     * @var string
     */
    private $nombreUsuario = null;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $lugarNacimiento = null;

    /**
     * @var string
     */
    private $preguntaSecreta = null;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var string
     */
    private $respuesta = null;

    /**
     * @var boolean
     */
    private $activo = false;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\Usuarios
     */
    private $usuario;


    /**
     * Set nombreUsuario
     *
     * @param string $nombreUsuario
     * @return PerfilUsuario
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get nombreUsuario
     *
     * @return string 
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return PerfilUsuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set lugarNacimiento
     *
     * @param string $lugarNacimiento
     * @return PerfilUsuario
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
     * Set preguntaSecreta
     *
     * @param string $preguntaSecreta
     * @return PerfilUsuario
     */
    public function setPreguntaSecreta($preguntaSecreta)
    {
        $this->preguntaSecreta = $preguntaSecreta;

        return $this;
    }

    /**
     * Get preguntaSecreta
     *
     * @return string 
     */
    public function getPreguntaSecreta()
    {
        return $this->preguntaSecreta;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return PerfilUsuario
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
     * Set respuesta
     *
     * @param string $respuesta
     * @return PerfilUsuario
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return PerfilUsuario
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
     * @param \Test\inicialBundle\Entity\Usuarios $usuario
     * @return PerfilUsuario
     */
    public function setUsuario(\Test\inicialBundle\Entity\Usuarios $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Test\inicialBundle\Entity\Usuarios 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    public function __construct()
    {
        $this->fechaCreacion = date("Y-m-d");
    }
}
