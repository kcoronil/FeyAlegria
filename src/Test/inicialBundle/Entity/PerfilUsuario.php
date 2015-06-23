<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PerfilUsuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Test\inicialBundle\Entity\PerfilUsuarioRepository")
 */
class PerfilUsuario
{

    /**
     * @ORM\OneToOne(targetEntity="Usuarios", inversedBy="perfil")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario;

    public function getUsuario(){
        return $this->usuario;
    }

    public function setUsuario($usuario){
        return $this->usuario = $usuario;
    }


    /**
     * @ORM\ManyToOne(targetEntity="Sexo", inversedBy="usuario")
     * @ORM\JoinColumn(name="sexo_id", referencedColumnName="id")
     */


    /**
     * @ORM\ManyToOne(targetEntity="Roles", inversedBy="perfil")
     * @ORM\JoinColumn(name="sexo_id", referencedColumnName="id")
     */

    protected $roles;

    public function __construct() {
        $this->roles = new ArrayCollection();
    }

    public function getRoles(){
        return $this->roles->toArray();
    }

    public function setRoles($roles){
        $this->roles = $roles;
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
     */
    private $nombreUsuario = null;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="lugar_nacimiento", type="string", length=60)
     */
    private $lugarNacimiento = null;

    /**
     * @var string
     *
     * @ORM\Column(name="pregunta_secreta", type="string", length=20)
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
     *
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
    private $activo;


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
