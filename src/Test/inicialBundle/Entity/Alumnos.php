<?php

namespace Test\inicialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Alumnos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Test\inicialBundle\Entity\AlumnosRepository")
 */
class Alumnos
{
    /**
     * @ORM\ManyToMany(targetEntity="Usuarios", mappedBy="alumno", cascade={"persist"})
     * @ORM\JoinTable(name="alumnos_representantes", joinColumns={@ORM\JoinColumn(name="alumno_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="representante_id", referencedColumnName="id")}
     * )
     **/

    public $usuario;

    public function __construct() {
        $this->usuario = new ArrayCollection();
        $this->PeriodoEscolarAlumno = new ArrayCollection();
    }

    public function addUsuario(Usuarios $usuario)
    {
        $usuario->addAlumno($this);
        $this->usuario[] = $usuario;
    }

    public function removeUsuario($usuario)
    {
        //optionally add a check here to see that $group exists before removing it.
        return $this->usuario->removeElement($usuario);
    }

    /**
     * @ORM\ManyToOne(targetEntity="Sexo", inversedBy="alumno")
     * @ORM\JoinColumn(name="sexo_id", referencedColumnName="id")
     */


    /**
     * @ORM\OneToMany(targetEntity="PeriodoEscolarAlumno", mappedBy="alumno" , cascade={"persist"})
     */

    protected $PeriodoEscolarAlumno;

    public function getPeriodoEscolarAlumno()
    {
        return $this->PeriodoEscolarAlumno;
    }


    public function addPeriodoEscolarAlumno(PeriodoEscolarAlumno $PeriodoEscolarAlumno)
    {
        $PeriodoEscolarAlumno->setAlumno($this);
        $this->PeriodoEscolarAlumno[] = $PeriodoEscolarAlumno;
    }

    public function removePeriodoescolaralumno($PeriodoEscolarAlumno)
    {
        return $this->PeriodoEscolarAlumno->removeElement($PeriodoEscolarAlumno);
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
     * @ORM\Column(name="cedula", type="integer", nullable=true)
     */
    private $cedula;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula_estudiantil", type="string", nullable=true, length=20)
     */
    private $cedulaEstudiantil;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=30)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="nombres", type="string", length=30)
     */
    private $nombres;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="date")
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="lugar_nacimiento", type="string", length=60)
     */
    private $lugarNacimiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="sexo", type="integer")
     */
    private $sexo;

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
     * Set cedula
     *
     * @param integer $cedula
     * @return Alumnos
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set cedulaEstudiantil
     *
     * @param string $cedulaEstudiantil
     * @return Alumnos
     */
    public function setCedulaEstudiantil($cedulaEstudiantil)
    {
        $this->cedulaEstudiantil = $cedulaEstudiantil;

        return $this;
    }

    /**
     * Get cedulaEstudiantil
     *
     * @return string 
     */
    public function getCedulaEstudiantil()
    {
        return $this->cedulaEstudiantil;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Alumnos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return Alumnos
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Alumnos
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set lugarNacimiento
     *
     * @param string $lugarNacimiento
     * @return Alumnos
     */
    public function setLugarNacimiento($lugarNacimiento)
    {
        $this->lugarNacimiento = $lugarNacimiento;

        return $this;
    }

    /**
     * Get lugarNacimiento
     *
     * @return string 
     */
    public function getLugarNacimiento()
    {
        return $this->lugarNacimiento;
    }

    /**
     * Set sexo
     *
     * @param integer $sexo
     * @return Alumnos
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return integer 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Alumnos
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
        return $this->nombres;
    }
}
