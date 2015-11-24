<?php

namespace RosaMolas\usuariosBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\usuariosBundle\Form\UsuariosType;
use RosaMolas\usuariosBundle\Form\UsuariosTypeSimple;


class UsuariosFuncionesGenericas extends Controller
{
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function crear_representante_generico($request, $principal=false, $alumnos=null, $titulo=null)
    {
        $p = new Usuarios();
        if(!$titulo){
            $titulo= 'Crear Representante';
        }

        $formulario = $this->createForm(new UsuariosTypeSimple($titulo), $p);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');
        $tipo_usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:TipoUsuario')
            ->find(5);
        $p->setTipoUsuario($tipo_usuario);
        $p->setPrincipal($principal);

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                if($alumnos){
                    foreach($alumnos as $alumno) {
                        $alumno_query = $this->getDoctrine()
                            ->getRepository('alumnosBundle:Alumnos')
                            ->find($alumno->getId());
                        $p->addAlumno($alumno_query);
                    }
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante Creado con éxito');

                //return $this->redirect($this->generateUrl('inicial_homepage'));

                if ($formulario->get('guardar')->isClicked()) {
                    return array('representante'=>$p, 'representantes_finalizado'=>true);
                }
                if ($formulario->get('guardar_crear')->isClicked()) {
                    return array('representante'=>$p);
                }
            }
            else{
                return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
    }

    public function crear_usuario_generico($request, $tipo)
    {
        $p = new Usuarios();
        if($tipo == 'representante'){
            $formulario = $this->createForm(new UsuariosType('Crear Representante'), $p);
            $formulario -> remove('tipoUsuario');
            $formulario -> remove('principal');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('usuarioslBundle:TipoUsuario')
                ->find(5);
            $p->setTipoUsuario($tipo_usuario);
            $p->setPrincipal('true');
            $elemento = 'Representante';
        }
        else{
            $formulario = $this->createForm(new UsuariosType('Crear Usuario'), $p);
            $formulario -> remove('alumno');
            $formulario -> remove('principal');
            $formulario -> remove('representanteContacto');
            $elemento = 'Usuario';
        }
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', $elemento.' Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_usuario'));
                }
            }
            else{
                return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
    }
}
