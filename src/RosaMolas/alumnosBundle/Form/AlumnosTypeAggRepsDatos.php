<?php

namespace RosaMolas\alumnosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class AlumnosTypeAggRepsDatos extends AbstractType
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
            ->add('test','collection',array('type'=>new AlumnoRepresentanteDatosType('Agregar Datos Representante'), 'allow_add' => false, 'allow_delete' => false,
                'by_reference' => false,'prototype' => false, 'label' => false, 'cascade_validation'=>false,
                'error_bubbling'=>false))
            ->add('guardar', 'submit', array('attr'=>array('class'=>'data-first-button btn-default')))
            ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['titulo'] = $this->titulo;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'alumnosbundle_alumnos_agg_reps_datos';
    }

}
