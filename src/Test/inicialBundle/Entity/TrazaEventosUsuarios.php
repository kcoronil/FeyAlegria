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
     * @ORM\Column(name="evento", type="integer")
     */
    private $evento;

    /**
     * @var integer
     *
     * @ORM\Column(name="objeto", type="integer")
     */
    private $objeto;


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
     * @param integer $evento
     * @return TrazaEventosUsuarios
     */
    public function setEvento($evento)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return integer 
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Set objeto
     *
     * @param integer $objeto
     * @return TrazaEventosUsuarios
     */
    public function setObjeto($objeto)
    {
        $this->objeto = $objeto;

        return $this;
    }

    /**
     * Get objeto
     *
     * @return integer 
     */
    public function getObjeto()
    {
        return $this->objeto;
    }
}
