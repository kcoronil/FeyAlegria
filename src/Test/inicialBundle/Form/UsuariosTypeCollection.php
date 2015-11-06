<?php

namespace Test\inicialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class UsuariosTypeCollection extends AbstractType
{
    public function __construct ($titulo)
    {
        $this->titulo = $titulo;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoUsuario', 'entity', array('required' => true,
                'class' => 'inicialBundle:TipoUsuario', 'multiple'=>false,
            'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('u')
            ->where('u.id=5');},))
            ->add('principal', 'choice', array('multiple'=>false, 'expanded'=>true,
                'choices' => array(true => 'Si', false => 'No'),  'attr'=>array('class'=>'radio-inline'),
                'label'=>'Representante Principal?',))
            ->add('cedula')
            ->add('apellidos')
            ->add('nombres')
            ->add('fechaNacimiento','date', array('widget'=>'single_text',
                'format'=>'y-M-d', 'attr'=>array('class'=>'datepick')))
            ->add('direccion')
            ->add('sexo', 'entity', array('required' => true,'class' => 'genericoBundle:Sexo','empty_data' => 'hola',
                'multiple'=>false, 'expanded'=>true,  'attr'=>array('class'=>'radio-inline')))
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
            'data_class' => 'Test\inicialBundle\Entity\Usuarios'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'test_inicialbundle_usuarios';
    }
}
