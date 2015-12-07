<?php

namespace RosaMolas\alumnosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class AlumnosTypeInscripcion extends AbstractType
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
            ->add('primerApellido')
            ->add('segundoApellido')
            ->add('primerNombre')
            ->add('segundoNombre')
            ->add('fechaNacimiento','date', array('widget'=>'single_text', 'format'=>'d-M-y', 'attr'=>array('class'=>'fecha_nacimiento') ))
            ->add('lugarNacimiento')
            ->add('sexo', 'entity', array('required' => true,
                'class' => 'genericoBundle:Sexo','empty_data' => 'hola', 'multiple'=>false, 'expanded'=>true))
            ->add('periodoEscolarCursoAlumno', 'collection', array('type'=>new PeriodoEscolarCursoAlumnoType('Seleccionar Curso'), 'allow_add' => true, 'allow_delete' => true,
                'by_reference' => false,'prototype' => true, 'label' => false, 'cascade_validation'=>true,
                'error_bubbling'=>false))
            ->add('tipoFacturacion','entity', array('required' => true,
                'class' => 'facturacionBundle:TipoFacturacion','empty_data' => 'Crear Tipo Facturacion', 'multiple'=>false, 'expanded'=>false, 'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.activo=true');}))
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
            'data_class' => 'RosaMolas\alumnosBundle\Entity\Alumnos'
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
