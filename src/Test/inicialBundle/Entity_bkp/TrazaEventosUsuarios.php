<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrazaEventosUsuarios
 */
class TrazaEventosUsuarios
{
    /**
     * @var integer
     */
    private $idObjeto;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Test\inicialBundle\Entity\Eventos
     */
    private $idEvento;

    /**
     * @var \Test\inicialBundle\Entity\Usuarios
     */
    private $usuario;

    /**
     * @var \Test\inicialBundle\Entity\Elementos
     */
    private $elemento;


    /**
     * Set idObjeto
     *
     * @param integer $idObjeto
     * @return TrazaEventosUsuarios
     */
    public function setIdObjeto($idObjeto)
    {
        $this->idObjeto = $idObjeto;

        return $this;
    }

    /**
     * Get idObjeto
     *
     * @return integer 
     */
    public function getIdObjeto()
    {
        return $this->idObjeto;
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
     * Set idEvento
     *
     * @param \Test\inicialBundle\Entity\Eventos $idEvento
     * @return TrazaEventosUsuarios
     */
    public function setIdEvento(\Test\inicialBundle\Entity\Eventos $idEvento = null)
    {
        $this->idEvento = $idEvento;

        return $this;
    }

    /**
     * Get idEvento
     *
     * @return \Test\inicialBundle\Entity\Eventos 
     */
    public function getIdEvento()
    {
        return $this->idEvento;
    }

    /**
     * Set usuario
     *
     * @param \Test\inicialBundle\Entity\Usuarios $usuario
     * @return TrazaEventosUsuarios
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
     * Set elemento
     *
     * @param \Test\inicialBundle\Entity\Elementos $elemento
     * @return TrazaEventosUsuarios
     */
    public function setElemento(\Test\inicialBundle\Entity\Elementos $elemento = null)
    {
        $this->elemento = $elemento;

        return $this;
    }

    /**
     * Get elemento
     *
     * @return \Test\inicialBundle\Entity\Elementos 
     */
    public function getElemento()
    {
        return $this->elemento;
    }
}
