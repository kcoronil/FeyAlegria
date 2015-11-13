<?php

namespace RosaMolas\alumnosBundle\Form;

use Proxies\__CG__\Test\inicialBundle\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class AlumnosType extends AbstractType
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
        return 'test_inicialbundle_alumnos';
    }
}
