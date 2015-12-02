<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecuperarPasswordTmp
 *
 * @ORM\Table(name="recuperar_password_tmp", indexes={@ORM\Index(name="IDX_5DD4248EB052C3AA", columns={"id_perfil"})})
 * @ORM\Entity
 */
class RecuperarPasswordTmp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="recuperar_password_tmp_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", nullable=true)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetimetz", nullable=true)
     */
    private $fecha;

    /**
     * @var \PerfilUsuario
     *
     * @ORM\ManyToOne(targetEntity="PerfilUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_perfil", referencedColumnName="id")
     * })
     */
    private $idPerfil;


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
     * Set idPerfil
     *
     * @param integer $idPerfil
     * @return RecuperarPasswordTmp
     */
    public function setidPerfil($idPerfil)
    {
        $this->idPerfil = $idPerfil;

        return $this;
    }

    /**
     * Get idPerfil
     *
     * @return integer
     */
    public function getidPerfil()
    {
        return $this->idPerfil;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return RecuperarPasswordTmp
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

    /**
     * Set token
     *
     * @param string $token
     * @return RecuperarPasswordTmp
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }



}
