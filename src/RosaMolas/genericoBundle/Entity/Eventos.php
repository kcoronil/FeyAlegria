<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Eventos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Test\inicialBundle\Entity\EventosRepository")
 */
class Eventos
{
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
     * @ORM\Column(name="nombre", type="string", length=100)
     *
     * @Assert\Length(min = 3, max = 20,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="[ a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @Assert\NotBlank()
     *
     *
     */
    private $nombre;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Eventos
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
     * @return Eventos
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
