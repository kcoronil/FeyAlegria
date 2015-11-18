<?php

namespace RosaMolas\facturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoMontos
 */
class TipoMontos
{
    /**
     * @var string
     */
    private $nombre;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TipoMontos
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
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
     * Set activo
     *
     * @param boolean $activo
     * @return TipoMontos
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
        return $this->nombre;
    }
}
