<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PerfilesRoles
 *
 * @ORM\Table(name="perfiles_roles", uniqueConstraints={@ORM\UniqueConstraint(name="usuarios_roles_usuario_id_rol_id_key", columns={"usuario_id", "rol_id"})}, indexes={@ORM\Index(name="usuarios_roles_rol_id", columns={"rol_id"}), @ORM\Index(name="usuarios_roles_usuario_id", columns={"usuario_id"})})
 * @ORM\Entity
 */
class PerfilesRoles
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="perfiles_roles_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\Roles
     *
     * @ORM\ManyToOne(targetEntity="Test\inicialBundle\Entity\Roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     * })
     */
    private $rol;

    /**
     * @var \Test\inicialBundle\Entity\PerfilUsuario
     *
     * @ORM\ManyToOne(targetEntity="Test\inicialBundle\Entity\PerfilUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;



    /**
     * Set activo
     *
     * @param boolean $activo
     * @return PerfilesRoles
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
     * Set rol
     *
     * @param \Test\inicialBundle\Entity\Roles $rol
     * @return PerfilesRoles
     */
    public function setRol(\Test\inicialBundle\Entity\Roles $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \Test\inicialBundle\Entity\Roles 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set usuario
     *
     * @param \Test\inicialBundle\Entity\PerfilUsuario $usuario
     * @return PerfilesRoles
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
