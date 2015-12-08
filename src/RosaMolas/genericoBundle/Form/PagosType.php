<?php

namespace RosaMolas\genericoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PagosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaDeposito')
            ->add('numeroDeposito')
            ->add('monto')
            ->add('fechaRegistro')
            ->add('activo')
            ->add('banco')
            ->add('factura')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RosaMolas\genericoBundle\Entity\Pagos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rosamolas_genericobundle_pagos';
    }
}
