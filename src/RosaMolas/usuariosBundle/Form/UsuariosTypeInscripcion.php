<?php

namespace RosaMolas\usuariosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class UsuariosTypeInscripcion extends AbstractType
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
            ->add('tipoUsuario', 'entity', array('required' => false,
                'class' => 'usuariosBundle:TipoUsuario','empty_value' => 'Seleccione Tipo', 'multiple'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->where('u.id!=1');},))
            ->add('principal', 'checkbox', array('required'=>false))
            ->add('cedula')
            ->add('apellidos')
            ->add('nombres')
            ->add('fechaNacimiento','date', array('widget'=>'single_text', 'format'=>'d-M-y', 'attr'=>array('class'=>'fecha_nacimiento') ) )
            ->add('sexo', 'entity', array('required' => true,
                'class' => 'genericoBundle:Sexo','empty_data' => 'hola', 'multiple'=>false, 'expanded'=>true))
            ->add('direccion')
            ->add('representanteContacto', 'collection', array('type'=>new RepresentanteContactoType("Crear Contacto"), 'allow_add' => true, 'allow_delete' => true,
                'by_reference' => false,'prototype' => true, 'label' => false, 'cascade_validation'=>true,
                'error_bubbling'=>false))
            ->add('activo', 'checkbox', array('required'=>false))
            ->add('guardar_crear', 'submit', array('label'=>'Agregar Otro', 'attr'=>array('class'=>'data-first-button btn-default')))
            ->add('guardar', 'submit', array('label'=>'Continuar', 'attr'=>array('class'=>'btn-default')))
            ->add('omitir', 'submit', array('validation_groups' => false,'label'=>'Omitir', 'attr'=>array('onclick'=>'for(var f=document.forms,i=f.length;i--;)f[i].setAttribute("novalidate",i)', 'class'=>'data-last-button btn-default')))

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
            'data_class' => 'RosaMolas\usuariosBundle\Entity\Usuarios'
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
