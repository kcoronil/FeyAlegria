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
            ->add('usuario')
            ->add('nombreUsuario')
            ->add('email')
            ->add('lugarNacimiento')
            ->add('preguntaSecreta')
            ->add('respuesta')
            ->add('rol')
            ->add('guardar', 'submit', array('attr' => array('posicion_boton' =>'data-first-button')))
            ->add('guardar_crear', 'submit', array('attr' => array('posicion_boton' =>'data-last-button')))
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
