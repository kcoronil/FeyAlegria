<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passwords
 *
 * @ORM\Table(name="passwords", indexes={@ORM\Index(name="passwords_usuario_id", columns={"perfil_id"})})
 * @ORM\Entity
 */
class Passwords
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="passwords_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=40, nullable=false)
     */
    public $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=40, nullable=false)
     */
    public $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=false)
     */
    public $fechaCreacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=false)
     */
    public $activo;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PerfilUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="perfil_id", referencedColumnName="id")
     * })
     */
    public $perfil;

    public function __construct() {
        if(!$this->salt){
            $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        }
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
     * Set password
     *
     * @param string $salt
     * @return string
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get dalt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
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
     * Set perfil
     *
     * @param integer $perfil
     * @return Passwords
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return integer
     */
    public function getPerfil()
    {
        return $this->perfil;
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



}
