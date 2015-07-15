<?php

namespace Test\inicialBundle\Form;

use Proxies\__CG__\Test\inicialBundle\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AlumnosTypeUsuario extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cedula')
            ->add('cedulaEstudiantil')
            ->add('apellidos')
            ->add('nombres')
            ->add('fechaNacimiento','date', array('widget'=>'single_text', 'format'=>'y-M-d', 'attr'=>array('class'=>'datepick') ))
            ->add('lugarNacimiento')
            ->add('sexo', 'entity', array('required' => true,
                'class' => 'inicialBundle:Sexo','empty_data' => 'hola', 'multiple'=>false, 'expanded'=>true))
            ->add('usuario', 'collection', array('type'=>new UsuariosTypeCollection(), 'allow_add' => true, 'allow_delete' => true,
                'by_reference' => false,'prototype' => true, 'label' => false, 'cascade_validation'=>true,
                'error_bubbling'=>false))
            ->add('guardar', 'submit')
            ->add('guardar_crear', 'submit', array('label'=>'Guardar y Crear Otro'))
        ;
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
        return 'test_inicialbundle_alumnos';
    }
}
