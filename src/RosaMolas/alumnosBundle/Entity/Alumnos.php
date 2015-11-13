<?php

namespace RosaMolas\alumnosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Alumnos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RosaMolas\alumnosBundle\Entity\AlumnosRepository")
 */
class Alumnos
{
    /**
     * @ORM\ManyToMany(targetEntity="Usuarios", mappedBy="alumno", cascade={"persist"})
     * @ORM\JoinTable(name="alumnos_representantes", joinColumns={@ORM\JoinColumn(name="alumno_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="representante_id", referencedColumnName="id")}
     * )
     **/

    protected $usuario;

    public function __construct() {
        $this->usuario = new ArrayCollection();
        $this->periodoEscolarAlumno = new ArrayCollection();
        $this->periodoEscolarCurso = new ArrayCollection();
    }

    public function getUsuario()
    {
        return $this->usuario;
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
     * @ORM\OneToMany(targetEntity="PeriodoEscolarAlumno", mappedBy="alumno" , cascade={"persist"}, orphanRemoval=TRUE)
     */

    protected $periodoEscolarAlumno;

    protected $periodoEscolarCurso;

    /*------------------- gestion de grado------------------- */


    public function getPeriodoEscolarCurso()
    {
        $periodoEscolarCurso = New ArrayCollection();
        foreach($this->periodoEscolarAlumno as $pe_alumno){
            $periodoEscolarCurso[] =$pe_alumno->getPeriodoEscolarCurso();
        }
        return $periodoEscolarCurso;
    }

    public function setPeriodoEscolarCurso($periodoEscolarCurso)
    {
        foreach($periodoEscolarCurso as $pe_curso){

            $pe_alumno = New PeriodoEscolarAlumno();

            $pe_alumno->setAlumno($this);
            $pe_alumno->setPeriodoEscolarCurso($pe_curso);

            $this->addPeriodoEscolarAlumno($pe_alumno);
        }
    }

    public function getAlumno(){
        return $this;
    }

    public function getPeriodoEscolarAlumno()
    {
        return $this->periodoEscolarAlumno->toArray();
    }

    public function addPeriodoEscolarAlumno(PeriodoEscolarAlumno $periodoEscolarAlumno)
    {
        $periodoEscolarAlumno->setAlumno($this);

        $this->periodoEscolarAlumno[] = $periodoEscolarAlumno;
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
     * @Assert\Type(type="integer",message="el valor {{ value }} no es númerico.")
     *
     * @ORM\Column(name="cedula", type="integer", nullable=true)
     */
    private $cedula;

    /**
     * @var string
     * @ORM\Column(name="cedula_estudiantil", type="string", nullable=true, length=20)
     *
     * @Assert\Length(min = 3, max = 20,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="[ 0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfanumérico.")
     */
    private $cedulaEstudiantil;

    /**
     * @var string
     *
     * @Assert\Regex(
     * pattern="[ a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @Assert\Length(min = 3, max = 50,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     *
     * @ORM\Column(name="primer_apellidos", type="string", length=30)
     */
    private $primerApellido;

    /**
     * @var string
     *
     * @Assert\Regex(pattern="[ a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @Assert\Length(min = 3, max = 50,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     *
     * @ORM\Column(name="segundo_apellidos", type="string", length=30)
     */

    private $segundoApellido;

    /**
     * @var string
     *
     * @Assert\Length(min = 3, max = 50,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="[ a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     *
     * @ORM\Column(name="primer_nombre", type="string", length=30)
     */

    private $primerNombre;

    /**
     * @var string
     *
     * @Assert\Length(min = 3, max = 50,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="[ a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     *
     * @ORM\Column(name="segundo_nombre", type="string", length=30)
     */

    private $segundoNombre;

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
     * @Assert\Length(min = 3, max = 60,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="[ a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     */
    private $lugarNacimiento;

    /**
     * @var integer
     * @ORM\Column(name="sexo", type="integer")
     *
     * @Assert\NotBlank()
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
     * Set primerApellido
     *
     * @param string $primerApellido
     * @return Alumnos
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string 
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     * @return Alumnos
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set primerNombre
     *
     * @param string $primerNombre
     * @return Alumnos
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get primerNombre
     *
     * @return string
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Set segundoNombre
     *
     * @param string $segundoNombre
     * @return Alumnos
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    /**
     * Get segundoNombre
     *
     * @return string 
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
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
        return $this->primerNombre.' '.$this->primerApellido;
    }
}
