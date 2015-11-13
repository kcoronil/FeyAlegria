<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Sexo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RosaMolas\genericoBundle\Entity\SexoRepository")
 */
class Sexo
{
    /**
     * @ORM\OneToMany(targetEntity="RosaMolas\alumnosBundle\entity\Alumnos", mappedBy="sexo")
     */


    /**
     * @ORM\OneToMany(targetEntity="RosaMolas\usuariosBundle\entity\Usuarios", mappedBy="sexo")
     */
    protected $usuario;

    public function __construct(){
        $this->usuario = new ArrayCollection();
    }
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
     * @ORM\Column(name="nombre", type="string", length=9)
     */
    private $nombre;


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
     * @return Sexo
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
    public function __toString()
    {
        return $this->nombre;
    }
}
