<?php

namespace RosaMolas\alumnosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class AlumnosTypeAggReps extends AbstractType
{
    public function __construct ($lista_id)
    {
        $this->lista_id = $lista_id;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('test','collection',array('type'=>new AlumnosTypeAggRep('Agregar Representante', $this->lista_id), 'allow_add' => true, 'allow_delete' => false,
                'by_reference' => true,'prototype' => false, 'label' => false, 'cascade_validation'=>true,
                'error_bubbling'=>false))
            ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'alumnosbundle_alumnos_agg_reps';
    }

}
