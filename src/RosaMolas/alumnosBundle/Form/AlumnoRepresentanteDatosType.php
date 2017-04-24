<?php

namespace RosaMolas\alumnosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class AlumnoRepresentanteDatosType extends AbstractType
{
    public function __construct($titulo)
    {
        $this->titulo = $titulo;
//        $this->lista_id = $lista_id;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parentesco')
            ->add('principal', 'checkbox', array('required'=>false, 'label'=>false, 'attr'=>array('class'=>'representante_principal')))
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
            'data_class' => 'RosaMolas\alumnosBundle\Entity\AlumnoRepresentanteDatos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rosamolas_alumnosbundle_alumnorepresentantedatos';
    }
}
