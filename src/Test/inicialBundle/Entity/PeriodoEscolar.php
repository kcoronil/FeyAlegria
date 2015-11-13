<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PeriodoEscolar
 */
class PeriodoEscolar
{

    /**
     * @ORM\OneToMany(targetEntity="PeriodoEscolarCurso", mappedBy="periodo")
     */

    /**
     * @var string
     *
     * @Assert\Length(min = 3, max = 40,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="[ 0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfanumérico.")
     */
    private $nombre;

    /**
     * @var boolean
     */
    private $activo = true;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set nombre
     *
     * @param string $nombre
     * @return PeriodoEscolar
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
     * @return PeriodoEscolar
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
    public function __toString()
    {
        return $this->nombre;
    }
}
