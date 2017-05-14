<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuariosPermisos
 */
class UsuariosPermisos
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
     * @var \RosaMolas\usuariosBundle\Entity\Permisos
     */
    private $permiso;


    /**
     * Set activo
     *
     * @param boolean $activo
     * @return UsuariosPermisos
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
     * @return UsuariosPermisos
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
     * Set permiso
     *
     * @param \RosaMolas\usuariosBundle\Entity\Permisos $permiso
     * @return UsuariosPermisos
     */
    public function setPermiso(\RosaMolas\usuariosBundle\Entity\Permisos $permiso = null)
    {
        $this->permiso = $permiso;

        return $this;
    }

    /**
     * Get permiso
     *
     * @return \Test\inicialBundle\Entity\Permisos 
     */
    public function getPermiso()
    {
        return $this->permiso;
    }
}
