<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PerfilUsuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RosaMolas\usuariosBundle\Entity\PerfilUsuarioRepository")
 */
class PerfilUsuario implements UserInterface
{
    /**
     * @ORM\OneToOne(targetEntity="Usuarios", inversedBy="perfil")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", unique=true)
     */

    public $usuario;

    /**
     * @ORM\OneToMany(targetEntity="Passwords", mappedBy="perfil")
     * @ORM\JoinColumn(name="perfil_id", referencedColumnName="id", unique=true)
     */

    public $password;

    public function getPassword()
    {
        return $this->password;
    }


    /*public function getPublishedImages()
    {
        return $this->images->filter( function( $image ) {
            return ( $image->isPublished() );
        });
    }*/

    public function getSalt()
    {
        return $this->password->getSalt();
    }

    public function eraseCredentials()
    {
    }

    /**
     * @ORM\OneToMany(targetEntity="RecuperarPasswordTmp", inversedBy="perfil")
     * @ORM\JoinColumn(name="id_perfil", referencedColumnName="id")
     */


    /**
     * @ORM\ManyToOne(targetEntity="Roles", inversedBy="perfil")
     * @ORM\JoinColumn(name="sexo_id", referencedColumnName="id")
     */

    public $rol;

       public function __construct() {
           $this->rol = new ArrayCollection();
           $this->isActive = true;
           $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
       }

       public function getRoles(){
           return $this->rol->toArray();
       }

       public function setRoles($rol){
           $this->rol = $rol;
           return $this;
       }


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $id;

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
     */
    private $nombreUsuario = null;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\Email(
     * message = "El correo '{{ value }}' no es un correo valido.",
     * checkMX = true)
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
    private $lugarNacimiento = null;

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
    private $preguntaSecreta = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    private $fechaCreacion;

    /**
     * @var string
     * @Assert\Length(min = 3, max = 20,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * pattern="[ 0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfanumérico.")
     * @ORM\Column(name="respuesta", type="string", length=20)
     */
    private $respuesta = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="usuario_id", type="integer")
     */
    private $usuarioId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;


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
     * Get nombreUsuario
     *
     * @return string
     */
    public function getUsername()
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
     * Set usuarioId
     *
     * @param integer $usuarioId
     * @return PerfilUsuario
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * Get usuarioId
     *
     * @return integer 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
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
    public function __toString()
    {
        return $this->nombreUsuario;
    }
}
