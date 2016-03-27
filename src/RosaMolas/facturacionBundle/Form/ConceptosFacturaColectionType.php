<?php

namespace RosaMolas\facturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ConceptosFacturaColectionType extends AbstractType
{
    public function __construct ($titulo, $tipo_panel = null, $monto_particular = null)
    {
        $this->titulo = $titulo;
        $this->monto_particular = $monto_particular;
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
            ->add('nombre' ,'text',  array('label' => 'Nombre Concepto','attr'=>array('class'=>'campo_unico')));
        if(!$this->monto_particular) {
            $builder->add('tipoMontoConceptos', 'collection', array('type' => new TipoMontoConceptosCollectionType('Crear Concepto de Factura'), 'allow_add' => true, 'allow_delete' => true,
                'by_reference' => false, 'prototype' => true, 'label' => false, 'cascade_validation' => true, 'error_bubbling' => false, 'prototype_name' => 'tipomonto_nombre', 'attr'=>array('class'=>'campo_unico')));
        }
        else{
            $builder->add('montosAlumnos', 'collection', array('type' => new MontosAlumnosType('Crear Concepto de Factura'), 'allow_add' => true, 'allow_delete' => true,
                'by_reference' => false, 'prototype' => true, 'label' => false, 'cascade_validation' => true, 'error_bubbling' => false, 'prototype_name' => 'tipomonto_nombre', 'attr'=>array('class'=>'campo_unico')));
        }
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
            'data_class' => 'RosaMolas\facturacionBundle\Entity\ConceptosFactura'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'test_inicialbundle_conceptosfactura';
    }
}
