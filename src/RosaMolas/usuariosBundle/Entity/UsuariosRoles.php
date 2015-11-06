<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuariosRoles
 */
class UsuariosRoles
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
     * @var \Test\inicialBundle\Entity\Usuarios
     */
    private $usuario;

    /**
     * @var \Test\inicialBundle\Entity\Roles
     */
    private $rol;


    /**
     * Set activo
     *
     * @param boolean $activo
     * @return UsuariosRoles
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
     * @return UsuariosRoles
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

    /**
     * Set rol
     *
     * @param \Test\inicialBundle\Entity\Roles $rol
     * @return UsuariosRoles
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
}
