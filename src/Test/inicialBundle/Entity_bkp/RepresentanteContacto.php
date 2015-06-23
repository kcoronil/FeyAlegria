<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepresentanteContacto
 */
class RepresentanteContacto
{
    /**
     * @var string
     */
    private $contacto;

    /**
     * @var boolean
     */
    private $principal;

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
    private $representante;

    /**
     * @var \Test\inicialBundle\Entity\TipoContacto
     */
    private $tipoContacto;


    /**
     * Set contacto
     *
     * @param string $contacto
     * @return RepresentanteContacto
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set principal
     *
     * @param boolean $principal
     * @return RepresentanteContacto
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Get principal
     *
     * @return boolean 
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return RepresentanteContacto
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
     * Set representante
     *
     * @param \Test\inicialBundle\Entity\Usuarios $representante
     * @return RepresentanteContacto
     */
    public function setRepresentante(\Test\inicialBundle\Entity\Usuarios $representante = null)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return \Test\inicialBundle\Entity\Usuarios 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * Set tipoContacto
     *
     * @param \Test\inicialBundle\Entity\TipoContacto $tipoContacto
     * @return RepresentanteContacto
     */
    public function setTipoContacto(\Test\inicialBundle\Entity\TipoContacto $tipoContacto = null)
    {
        $this->tipoContacto = $tipoContacto;

        return $this;
    }

    /**
     * Get tipoContacto
     *
     * @return \Test\inicialBundle\Entity\TipoContacto 
     */
    public function getTipoContacto()
    {
        return $this->tipoContacto;
    }
}
