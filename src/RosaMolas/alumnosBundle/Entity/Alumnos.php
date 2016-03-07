<?php

namespace RosaMolas\alumnosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno;
use RosaMolas\alumnosBundle\Entity\AlumnoRepresentante;

/**
 * Alumnos
 */
class Alumnos
{
    /**
     * @var integer
     */
    private $cedula;

    /**
     * @var string
     */
    private $cedulaEstudiantil;

    /**
     * @var string
     */
    private $primerApellido;

    /**
     * @var string
     */
    private $primerNombre;

    /**
     * @var \DateTime
     */
    private $fechaNacimiento;

    /**
     * @var string
     */
    private $lugarNacimiento;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var string
     */
    private $segundoNombre;

    /**
     * @var string
     */
    private $segundoApellido;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \RosaMolas\genericoBundle\Entity\Sexo
     */
    private $sexo;

    /**
     * @var \RosaMolas\facturacionBundle\Entity\TipoFacturacion
     */
    private $tipoFacturacion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $representante;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $periodoEscolarCursoAlumno;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->representante = new ArrayCollection();
        $this->alumno_representante = new ArrayCollection();
        $this->periodoEscolarCursoAlumno = new ArrayCollection();
        //$this->periodoEscolarCurso = new ArrayCollection();
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sexo
     *
     * @param \RosaMolas\genericoBundle\Entity\Sexo $sexo
     * @return Alumnos
     */
    public function setSexo(\RosaMolas\genericoBundle\Entity\Sexo $sexo = null)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return \RosaMolas\genericoBundle\Entity\Sexo
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set tipoFacturacion
     *
     * @param \RosaMolas\facturacionBundle\Entity\TipoFacturacion $tipoFacturacion
     * @return Alumnos
     */
    public function setTipoFacturacion(\RosaMolas\facturacionBundle\Entity\TipoFacturacion $tipoFacturacion = null)
    {
        $this->tipoFacturacion = $tipoFacturacion;

        return $this;
    }

    /**
     * Get tipoFacturacion
     *
     * @return \RosaMolas\facturacionBundle\Entity\TipoFacturacion
     */
    public function getTipoFacturacion()
    {
        return $this->tipoFacturacion;
    }

    /**
     * Add representante
     *
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $representante
     * @return Alumnos
     */
    public function addRepresentante(\RosaMolas\usuariosBundle\Entity\Usuarios $representante)
    {
        $this->representante[] = $representante;

        return $this;
    }


    public $alumnorepresentante;


    public function getAlumnoRepresentante()
    {
        return $this->alumnorepresentante;
    }


    public function addAlumnoRepresentante(AlumnoRepresentante $alumnorepresentante)
    {
        $alumnorepresentante->setAlumno($this);
        $this->alumno_representante[] = $alumnorepresentante;
    }

    public function removeAlumnoRepresentante($alumnorepresentante)
    {
        //optionally add a check here to see that $group exists before removing it.
        return $this->alumnorepresentante->removeElement($alumnorepresentante);
    }


    /**
     * Get usuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuario()
    {
        return $this->alumnorepresentante;
    }

    /**
     * Remove representante
     *
     * @param \RosaMolas\usuariosBundle\Entity\Usuarios $representante
     */
    public function removeRepresentante(\RosaMolas\usuariosBundle\Entity\Usuarios $representante)
    {
        $this->representante->removeElement($representante);
    }

    /**
     * Get representante
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }


    public function getPeriodoEscolarCursoAlumno()
    {
        return $this->periodoEscolarCursoAlumno->toArray();
    }

    public function setPeriodoEscolarCursoAlumno(PeriodoEscolarCursoAlumno $periodoEscolarCursoAlumno = null)
    {
        $this->periodoEscolarCursoAlumno = $periodoEscolarCursoAlumno;
    }

    /**
     * Add periodoEscolarCursoAlumno
     *
     * @param \RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno $periodoEscolarCursoAlumno
     * @return Alumnos
     */

    public function addPeriodoEscolarCursoAlumno(PeriodoEscolarCursoAlumno $periodoEscolarCursoAlumno)
    {
        $periodoEscolarCursoAlumno->setAlumno($this);

        $this->periodoEscolarCursoAlumno[] = $periodoEscolarCursoAlumno;
    }


    public function getEdad()
    {
        $fecha_actual = new \DateTime("now");
        $diff = $fecha_actual->diff($this->fechaNacimiento);
        $edad = $diff->y;
        return $edad;
    }

    public function getNombreApellido()
    {
        return $this->primerNombre.' '.$this->primerApellido;
    }


    /**
     * Remove representante
     *
     * @param \RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno $periodoEscolarCursoAlumno
     */
    public function removePeriodoEscolarCursoAlumno(PeriodoEscolarCursoAlumno $periodoEscolarCursoAlumno)
    {
        $this->periodoEscolarCursoAlumno->removeElement($periodoEscolarCursoAlumno);
    }
}
