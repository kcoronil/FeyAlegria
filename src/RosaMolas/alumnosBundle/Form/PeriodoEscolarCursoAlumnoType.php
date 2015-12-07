<?php

namespace RosaMolas\alumnosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PeriodoEscolarCursoAlumnoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cursoSeccion','entity', array('label'=>'Grado', 'required' => true,
                'class' => 'inicialBundle:CursoSeccion','empty_data' => 'Debe Crear Grados', 'empty_value' => 'Seleccione Grado', 'multiple'=>false, 'expanded'=>false, 'by_reference' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.activo=true');}))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rosamolas_alumnosbundle_periodoescolarcursoalumno';
    }
}
