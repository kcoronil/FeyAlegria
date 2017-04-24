<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\AlumnosBundle\Entity\Alumnos;
use RosaMolas\alumnosBundle\Entity\AlumnoRepresentanteDatos;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Usuarios
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RosaMolas\usuariosBundle\Entity\UsuariosRepository")
 * @UniqueEntity(fields={"cedula"}, message="Este número de cédula ya esta registrado")
 *
 */
class Usuarios
{
    /**
     * @ORM\ManyToMany(targetEntity="Alumnos", inversedBy="representante", cascade={"persist"})
     * @ORM\JoinTable(name="alumnos_representantes",joinColumns={@ORM\JoinColumn(name="representante_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="alumno_id", referencedColumnName="id")}
     * )
     **/

    protected $alumno;

    public function __construct(){
        $this->alumno = new ArrayCollection();
        $this->representanteContacto = new ArrayCollection();
        $this->alumnoRepresentanteDatos = new ArrayCollection();
    }

    public function getAlumno()
    {
        return $this->alumno;
    }

    public function addAlumno(Alumnos $alumno)
    {
        $this->alumno->add($alumno);
    }

    public function removeAlumno($alumno)
    {
        //optionally add a check here to see that $group exists before removing it.
        return $this->alumno->removeElement($alumno);
    }

    /**
     * @ORM\OneToMany(targetEntity="RepresentanteContacto", mappedBy="representante" , cascade={"persist"}, orphanRemoval=TRUE)
     */

    protected $representanteContacto;


    public function getRepresentanteContacto()
    {
        return $this->representanteContacto;
    }

    public function addRepresentanteContacto(RepresentanteContacto $representanteContacto){
        $representanteContacto->setRepresentante($this);
        $this->representanteContacto->add($representanteContacto);
        return $this;
    }

    public function removeRepresentanteContacto($representanteContacto)
    {
        //optionally add a check here to see that $group exists before removing it.
        return $this->representanteContacto->removeElement($representanteContacto);
    }

    protected $alumnoRepresentanteDatos;

    /**
     * Get alumnoRepresentanteDatos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlumnoRepresentanteDatos()
    {
        return $this->alumnoRepresentanteDatos;
    }

    public function addAlumnoRepresentanteDatos(AlumnoRepresentanteDatos $alumnoRepresentanteDatos)
    {
        $alumnoRepresentanteDatos->setRepresentante($this);
        $this->alumnoRepresentanteDatos[] = $alumnoRepresentanteDatos;
    }

    public function removeAlumnoRepresentanteDatos($alumnoRepresentanteDatos)
    {
        //optionally add a check here to see that $group exists before removing it.
        return $this->alumnoRepresentanteDatos->removeElement($alumnoRepresentanteDatos);
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
     * @ORM\Column(name="cedula", type="integer")
     * @Assert\Type(type="integer",message="el valor {{ value }} no es númerico.")
     * @Assert\NotBlank()
     */


    private $cedula;

    /**
     * @var string
     * @Assert\Length(min = 3, max = 30,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="/\d/", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @ORM\Column(name="apellidos", type="string", length=30)
     *
     * @Assert\NotBlank()
     */
    private $primerApellido;

    /**
     * @var string
     * @Assert\Regex(pattern="/\d/", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @ORM\Column(name="apellidos", type="string", length=30)
     *
     *
     */
    private $segundoApellido;


    /**
     * @var string
     *
     * @ORM\Column(name="nombres", type="string", length=30)
     * @Assert\Length(min = 3, max = 30,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="/\d/", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @Assert\NotBlank()
     */
    private $primerNombre;

    /**
     * @var string
     * @Assert\Regex(pattern="/\d/", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @ORM\Column(name="apellidos", type="string", length=30)
     *
     *
     */
    private $segundoNombre;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="date")
     *
     * @Assert\NotBlank()
     */
    private $fechaNacimiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="sexo_id", type="integer")
     *
     * @Assert\NotBlank()
     */

    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="text")
     * @Assert\Length(min = 3, max = 300,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="/^[a-z\-0-9 ]$/i",htmlPattern = "^[a-zA-Z0-9]*$",
     * match=false, message="el valor {{ value }} no es alfanumérico.")
     * @Assert\NotBlank()
     */

    private $direccion;

    /**
     * @var boolean
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
     * @var integer
     *
     * @ORM\Column(name="tipo_usuario", type="integer")
     */

    /**
     * @Assert\NotBlank()
     */

    private $tipoUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=256)
     * @Assert\Email(
     * message = "El correo '{{ value }}' no es un correo valido.",
     * checkMX = false)
     */
    private $email;

    /**
     * @var \RosaMolas\genericoBundle\Entity\Inscripcion
     */
    private $inscripcion;

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
     * @return Usuarios
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
     * Set primerApellido
     *
     * @param string $primerApellido
     * @return Usuarios
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
     * Set primerApellido
     *
     * @param string $segundoApellido
     * @return Usuarios
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
     * @return Usuarios
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get nombres
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
     * @return Usuarios
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
     * @return Usuarios
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
     * Set sexo
     *
     * @param integer $sexo
     * @return Usuarios
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
     * Set direccion
     *
     * @param string $direccion
     * @return Usuarios
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set principal
     *
     * @param boolean $principal
     * @return Usuarios
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
     * @return Usuarios
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
     * Set tipoUsuario
     *
     * @param integer $tipoUsuario
     * @return Usuarios
     */
    public function setTipoUsuario($tipoUsuario)
    {
        $this->tipoUsuario = $tipoUsuario;

        return $this;
    }

    /**
     * Get tipoUsuario
     *
     * @return integer
     */
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }
    public function __toString()
    {
        return $this->primerNombre.' '.$this->primerApellido;
    }


    /**
     * Set email
     *
     * @param string $email
     * @return Usuarios
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set inscripcion
     *
     * @param \RosaMolas\genericoBundle\Entity\inscripcion $inscripcion
     * @return Usuarios
     */
    public function setInscripcion(\RosaMolas\genericoBundle\Entity\inscripcion $inscripcion)
    {
        $this->inscripcion = $inscripcion;

        return $this;
    }

    /**
     * Get inscripcion
     *
     * @return \RosaMolas\genericoBundle\Entity\inscripcion
     */
    public function getInscripcion()
    {
        return $this->inscripcion;
    }
}
