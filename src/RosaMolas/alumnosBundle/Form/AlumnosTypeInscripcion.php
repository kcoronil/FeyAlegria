<?php

namespace RosaMolas\alumnosBundle\Form;

use RosaMolas\facturacionBundle\Form\MontosAlumnosType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class AlumnosTypeInscripcion extends AbstractType
{
    public function __construct ($titulo, $lista_id = null, $secciones)
    {
        $this->titulo = $titulo;
        $this->lista_id = $lista_id;
        $this->secciones = $secciones;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cedula')
            ->add('primerApellido')
            ->add('segundoApellido')
            ->add('primerNombre')
            ->add('segundoNombre')
            ->add('fechaNacimiento','date', array('widget'=>'single_text', 'format'=>'d-M-y', 'attr'=>array('class'=>'fecha_nacimiento') ))
            ->add('lugarNacimiento')
            ->add('sexo', 'entity', array('required' => true,
                'class' => 'genericoBundle:Sexo','empty_data' => 'hola', 'multiple'=>false, 'expanded'=>true))
            ->add('alumnoRepresentanteDatos', 'collection',array('type'=>new AlumnoRepresentanteDatosType('Agregar Representante', $this->lista_id), 'allow_add' => true, 'allow_delete' => false,
                'by_reference' => true,'prototype' => false, 'label' => false, 'cascade_validation'=>true,
                'error_bubbling'=>false))
            ->add('periodoEscolarCursoAlumno', 'collection', array('type'=>new PeriodoEscolarCursoAlumnoType('Seleccionar Curso'), 'allow_add' => true, 'allow_delete' => true,
                'by_reference' => true,'prototype' => true, 'label' => false, 'cascade_validation'=>true,
                'error_bubbling'=>false))
            ->add('montosAlumnos', 'collection', array('type'=>new MontosAlumnosType(), 'allow_add' => true, 'allow_delete' => true,
                'by_reference' => true,'prototype' => true, 'label' => false, 'cascade_validation'=>true,
                'error_bubbling'=>false))
//            ->add('tipoFacturacion','entity', array('required' => true,
//                'class' => 'facturacionBundle:TipoFacturacion','empty_data' => 'Crear Tipo Facturacion', 'multiple'=>false, 'expanded'=>false, 'by_reference' => true,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')
//                        ->where('u.activo=true');}))

            ->add('guardar', 'submit', array('attr'=>array('class'=>'data-first-button btn-default')))
        ;
        /*if($this->lista_id){
            $builder->add('representante','entity', array('label'=>'Representantes', 'required' => true,
                'class' => 'usuariosBundle:Usuarios','empty_data' => 'hola', 'multiple'=>true, 'expanded'=>true, 'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('usuario')
                        ->where('usuario.id in (:ids)')
                        ->andWhere('usuario.tipoUsuario=5')
                        ->andWhere('usuario.activo = true')
                        ->setParameter('ids', $this->lista_id)
                        ->distinct();}));
        }
        else{
            $builder->add('representante','entity', array('label'=>'Representantes', 'required' => true,
                'class' => 'usuariosBundle:Usuarios','empty_data' => 'hola', 'multiple'=>true, 'expanded'=>false, 'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.tipoUsuario=5')
                        ->andWhere('u.activo = true');}));
        }*/
    }
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['datos_secciones'] = $this->secciones;
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
        return 'test_inicialbundle_alumnos_simple';
    }
}
