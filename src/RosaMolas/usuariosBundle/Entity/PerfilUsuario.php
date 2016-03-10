<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PerfilUsuario
 */
class PerfilUsuario
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre_usuario", type="string", length=30)
     *
     * pattern="[0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfanumérico.")
     * @Assert\Length(min = 6, max = 30,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     *
     */
    private $nombreUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\Email(
     * message = "El correo '{{ value }}' no es un correo valido.",
     * checkMX = true)
     *
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="lugar_nacimiento", type="string", length=60)
     * @Assert\Length(min = 3, max = 60,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     *
     * @Assert\Regex(
     * pattern="[ 0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfanumérico.")
     *
     */
    private $lugarNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="pregunta_secreta", type="string", length=20)
     * @Assert\Length(min = 3, max = 20,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * pattern="[ 0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfanumérico.")
     *
     */
    private $preguntaSecreta;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var string
     *
     * @Assert\Length(min = 3, max = 20,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * pattern="[ 0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfanumérico.")
     * @ORM\Column(name="respuesta", type="string", length=20)
     *
     */
    private $respuesta;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\usuariosBundle\Entity\Usuarios
     */
    private $usuario;

    /**
     * @var \RosaMolas\usuariosBundle\Entity\Roles
     */
    private $rol;


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
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $usuario
     * @return PerfilUsuario
     */
    public function setUsuario(\RosaMolas\usuariosBundle\Entity\Usuarios $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \RosaMolas\usuariosBundle\Entity\Usuarios 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set rol
     *
     * @param \RosaMolas\usuariosBundle\Entity\Roles $rol
     * @return PerfilUsuario
     */
    public function setRol(\RosaMolas\usuariosBundle\Entity\Roles $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \RosaMolas\usuariosBundle\Entity\Roles 
     */
    public function getRol()
    {
        return $this->rol;
    }
}
