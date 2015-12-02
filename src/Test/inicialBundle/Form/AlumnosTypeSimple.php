<?php

namespace Test\inicialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Test\inicialBundle\Entity\PeriodoEscolarAlumno;

class AlumnosTypeSimple extends AbstractType
{
    public function __construct ($titulo)
    {
        $this->titulo = $titulo;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario','entity', array('label'=>'Representante', 'required' => true,
                'class' => 'inicialBundle:Usuarios','empty_data' => 'hola', 'multiple'=>true, 'expanded'=>false, 'by_reference' => false))
            ->add('cedula')
            ->add('cedulaEstudiantil')
            ->add('apellidos')
            ->add('nombres')
            ->add('fechaNacimiento', 'date')
            ->add('lugarNacimiento')
            ->add('sexo', 'entity', array('required' => true,
                'class' => 'inicialBundle:Sexo','empty_data' => 'hola', 'multiple'=>false, 'expanded'=>true))
            ->add('periodoEscolarCurso','entity', array('required' => true,
                'class' => 'inicialBundle:PeriodoEscolarCurso','empty_value' => 'Seleccione grado', 'multiple'=>true, 'expanded'=>false))
            ->add('guardar', 'submit', array('attr'=>array('class'=>'data-first-button btn-default')))
            ->add('guardar_crear', 'submit', array('attr'=>array('label'=>'Guardar y Crear Otro', 'class'=>'data-last-button btn-default')))
        ;
    }
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars['titulo'] = $this->titulo;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Test\inicialBundle\Entity\Alumnos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'test_inicialbundle_alumnos_simple';
    }
}
