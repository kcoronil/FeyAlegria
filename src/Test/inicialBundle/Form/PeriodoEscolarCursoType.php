<?php

namespace Test\inicialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class PeriodoEscolarCursoType extends AbstractType
{
    public function __construct ($titulo, $tipo_panel = null)
    {
        $this->titulo = $titulo;
        if($tipo_panel){
            $this->tipo_panel = $tipo_panel;
        }
        else{
            $this->tipo_panel = null;
        }
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seccion', 'entity', array('required' => true,'attr'=>array('class'=>'campo_unico'),
                'class' => 'inicialBundle:Seccion','empty_value' => 'Seleccione Seccion', 'multiple'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.activo=true');},))

            ->add('periodoEscolar', 'entity', array('required' => true, 'attr'=>array('class'=>'campo_unico'),
                'class' => 'inicialBundle:PeriodoEscolar','empty_value' => 'Seleccione Periodo', 'multiple'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.activo=true');},))

            ->add('curso', 'entity', array('required' => true, 'attr'=>array('class'=>'campo_unico'),
                'class' => 'inicialBundle:Curso','empty_value' => 'Seleccione Curso', 'multiple'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.activo=true')->andWhere('u.id!=5');},))
            ->add('guardar', 'submit', array('label'=>'Guardar', 'attr'=>array('class'=>'btn-default data-first-button data-last-button')))
        ;

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['titulo'] = $this->titulo;
        if($this->tipo_panel){
            $view->vars['tipo_panel'] = $this->tipo_panel;
        }
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Test\inicialBundle\Entity\PeriodoEscolarCurso'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'test_inicialbundle_periodoescolarcurso';
    }
}
