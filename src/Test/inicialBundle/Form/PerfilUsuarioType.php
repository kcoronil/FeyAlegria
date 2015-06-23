<?php

namespace Test\inicialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PerfilUsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nombreUsuario')
            ->add('email')
            ->add('lugarNacimiento')
            ->add('preguntaSecreta')
            ->add('respuesta')
            ->add('fechaCreacion','date', array('widget'=>'single_text', 'format'=>'y-M-d', 'attr'=>array('class'=>'datepick')))
            ->add('activo')
            ->add('roles')
            ->add('guardar', 'submit')
            ->add('guardar_crear', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Test\inicialBundle\Entity\PerfilUsuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'test_inicialbundle_perfilusuario';
    }
}
