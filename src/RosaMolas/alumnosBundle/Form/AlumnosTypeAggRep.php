<?php

namespace RosaMolas\alumnosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class AlumnosTypeAggRep extends AbstractType
{
    public function __construct ($titulo, $lista_id)
    {
        $this->titulo = $titulo;
        $this->lista_id = $lista_id;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('representante','entity', array('label'=>false, 'required' => true,
                'class' => 'usuariosBundle:Usuarios','empty_data' => 'hola', 'multiple'=>true, 'expanded'=>true, 'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('usuario')
                        ->innerJoin('usuario.alumno', 'alumnos')
                        ->where('alumnos.id in (:id)')
                        ->andWhere('usuario.activo = true')
                        ->setParameter('id', $this->lista_id)
                        ->distinct();}))
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
        return 'alumnosbundle_alumnos_agg_rep';
    }
    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'RosaMolas\alumnosBundle\Entity\Alumnos',
        );
    }
}
