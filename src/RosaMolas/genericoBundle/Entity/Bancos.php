<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bancos
 */
class Bancos
{
    /**
     * @var string
     *
     * @Assert\Length(min = 3, max = 40,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="/^[a-z\-0-9 ]$/i",htmlPattern = "^[a-zA-Z0-9]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @Assert\NotBlank()
     *
     */
    private $nombre;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Bancos
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
     * Set activo
     *
     * @param boolean $activo
     * @return Bancos
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
}
