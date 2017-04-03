<?php

namespace RosaMolas\genericoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * InscripcionRepresentanteContacto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RosaMolas\usuariosBundle\Entity\InscripcionRepresentanteContactoRepository")
 * @UniqueEntity(fields={"contacto"}, message="Este contacto ya esta registrado")
 */
class InscripcionRepresentanteContacto
{
    /**
     * @ORM\ManyToOne(targetEntity="Usuarios", inversedBy="representante", cascade={"persist"})
     * @ORM\JoinColumn(name="representante_id", referencedColumnName="id")
     */

    private $representante;

    public function addRepresentante(InscripcionUsuarios $representante)
    {
        if (!$this->representante->contains($representante)) {
            $this->representante->add($representante);
        }
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
     * @var integer
     *
     * @ORM\Column(name="tipo_contacto", type="integer")
     */
    private $tipoContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="contacto", type="string", length=100)
     * @Assert\Length(min = 3, max = 100,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Type(type="alnum",message="el valor {{ value }} no es alfanumérico.")
     */
    private $contacto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="principal", type="boolean")
     */
    private $principal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;


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
     * @param integer $representante
     * @return InscripcionRepresentanteContacto
     */
    public function setRepresentante($representante)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return integer 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * Set tipoContacto
     *
     * @param integer $tipoContacto
     * @return InscripcionRepresentanteContacto
     */
    public function setTipoContacto($tipoContacto)
    {
        $this->tipoContacto = $tipoContacto;

        return $this;
    }

    /**
     * Get tipoContacto
     *
     * @return integer 
     */
    public function getTipoContacto()
    {
        return $this->tipoContacto;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     * @return InscripcionRepresentanteContacto
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
     * @return InscripcionRepresentanteContacto
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
     * @return InscripcionRepresentanteContacto
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
}
