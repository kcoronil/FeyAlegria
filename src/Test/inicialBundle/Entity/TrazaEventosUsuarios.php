<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrazaEventosUsuarios
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Test\inicialBundle\Entity\TrazaEventosUsuariosRepository")
 */
class TrazaEventosUsuarios
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
     * @var integer
     *
     * @ORM\Column(name="elemento", type="integer")
     */
    private $elemento;

    /**
     * @var integer
     *
     * @ORM\Column(name="usuario", type="integer")
     */
    private $usuario;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_evento", type="integer")
     */
    private $idEvento;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_objeto", type="integer")
     */
    private $idObjeto;

    /**
     * @var string
     */
    private $detalles;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetimetz", nullable=true)
     */
    private $fecha;


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
     * Set elemento
     *
     * @param integer $elemento
     * @return TrazaEventosUsuarios
     */
    public function setElemento($elemento)
    {
        $this->elemento = $elemento;

        return $this;
    }

    /**
     * Get elemento
     *
     * @return integer 
     */
    public function getElemento()
    {
        return $this->elemento;
    }

    /**
     * Set usuario
     *
     * @param integer $usuario
     * @return TrazaEventosUsuarios
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return integer 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set evento
     *
     * @param integer $idEvento
     * @return TrazaEventosUsuarios
     */
    public function setidEvento($idEvento)
    {
        $this->idEvento = $idEvento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return integer 
     */
    public function getidEvento()
    {
        return $this->idEvento;
    }

    /**
     * Set objeto
     *
     * @param integer $idObjeto
     * @return TrazaEventosUsuarios
     */
    public function setidObjeto($idObjeto)
    {
        $this->idObjeto = $idObjeto;

        return $this;
    }

    /**
     * Get objeto
     *
     * @return integer 
     */
    public function getidObjeto()
    {
        return $this->idObjeto;
    }


    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Curso
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
        return $this->detalles;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TrazaEventosUsuarios
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
