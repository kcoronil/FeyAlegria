<?php

namespace Test\inicialBundle\Entity;

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
     * @var \Test\inicialBundle\Entity\Usuarios
     */
    private $usuario;

    /**
     * @var \Test\inicialBundle\Entity\Permisos
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
     * @param \Test\inicialBundle\Entity\Usuarios $usuario
     * @return UsuariosPermisos
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
     * Set permiso
     *
     * @param \Test\inicialBundle\Entity\Permisos $permiso
     * @return UsuariosPermisos
     */
    public function setPermiso(\Test\inicialBundle\Entity\Permisos $permiso = null)
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
