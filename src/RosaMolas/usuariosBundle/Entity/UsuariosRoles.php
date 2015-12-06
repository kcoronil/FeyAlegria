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
     * @var \RosaMolas\usuariosBundle\Entity\Usuarios
     */
    private $usuario;

    /**
     * @var \RosaMolas\usuariosBundle\Entity\Roles
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
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $usuario
     * @return UsuariosRoles
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
     * @return UsuariosRoles
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
