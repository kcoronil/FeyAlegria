<?php

namespace RosaMolas\usuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\AlumnosBundle\Entity\Alumnos;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Usuarios
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Test\inicialBundle\Entity\UsuariosRepository")
 */
class Usuarios
{
    /**
     * @ORM\ManyToMany(targetEntity="Alumnos", inversedBy="usuario", cascade={"persist"})
     * @ORM\JoinTable(name="alumnos_representantes",joinColumns={@ORM\JoinColumn(name="representante_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="alumno_id", referencedColumnName="id")}
     * )
     **/

    protected $alumno;

    public function __construct(){
        $this->alumno = new ArrayCollection();
        $this->representanteContacto = new ArrayCollection();
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
     * @Assert\Regex(pattern="[ a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @ORM\Column(name="apellidos", type="string", length=30)
     *
     * @Assert\NotBlank()
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="nombres", type="string", length=30)
     * @Assert\Length(min = 3, max = 30,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="[ 0-9a-zA-Z]*$", match=false,
     * message="el valor {{ value }} no es alfabético.")
     * @Assert\NotBlank()
     */
    private $nombres;

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
     * @Assert\Length(min = 3, max = 30,
     * minMessage = "Este campo debe tener al menos {{ limit }} carácteres",
     * maxMessage = "Este campo no debe superar los {{ limit }} carácteres")
     * @Assert\Regex(pattern="[ 0-9a-zA-Z]*$",
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuarios
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
     * @return Usuarios
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
        return $this->nombres;
    }
}
