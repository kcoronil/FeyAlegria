<?php

namespace RosaMolas\genericoBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;
use Test\inicialBundle\Entity\TrazaEventosUsuarios;


class FuncionesGenericas extends Controller
{
    public function __construct($container)
    {
        $this->container = $container;
    }


    public function crear_generico($request, $modelo, $formulario_base, $objeto, $clase, $titulo, $url_redireccion= null, $url_editar= null, $url_borrar= null, $datos = null, $remover = null)
    {
        $p = $modelo;
        $formulario = $this->createForm($formulario_base, $p);
        if($remover){
            foreach($remover as $campo){
                $formulario->remove($campo);
            }
        }
        $formulario-> handleRequest($request);
        if($datos) {
            $query = $this->getDoctrine()->getRepository($clase)
                ->createQueryBuilder(strtolower($objeto))
                ->where(strtolower($objeto).'.activo = true')
                ->getQuery();


            $datos = $query->getArrayResult();
        }
        if($request->getMethod()=='POST') {
            if(!$url_redireccion) {
                $url_redireccion = 'inicial_agregar_' . strtolower($objeto);
            }
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', strtolower($titulo).' creado con éxito'
                );
                if(array_key_exists('guardar_crear', $formulario)){
                    if ($formulario->get('guardar')->isClicked()) {
                        return array('resulado'=>'exito', 'url'=> 'inicial_homepage');
                    }
                    if ($formulario->get('guardar_crear')->isClicked()) {
                        return array('resulado'=>'exito', 'url'=> $url_redireccion);
                    }
                }
                else {
                    return array('resulado'=>'exito', 'url'=> $url_redireccion);

                }
            }
            else{
                return array('form'=>$formulario->createView(),
                    'datos'=>$datos, 'accion'=>'Crear '.$titulo, 'url_editar'=>$url_editar,
                    'url_borrar'=>$url_borrar, 'operaciones_datos'=>true);
            }
        }
        if(!$url_editar) {
            $url_editar = 'inicial_editar_' . strtolower($objeto);
        }
        if(!$url_borrar) {
            $url_borrar = 'inicial_borrar_' . strtolower($objeto);
        }
        return array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>'Crear '.$titulo, 'url_editar'=>$url_editar,
            'url_borrar'=>$url_borrar, 'operaciones_datos'=>true);
    }
    public function editar_generico($id, $request, $formulario_base, $clase, $titulo, $url_redireccion, $remover = null)
    {

        $p = $this->getDoctrine()
            ->getRepository($clase)
            ->find($id);
        if (!$p)
        {
            $this->get('session')->getFlashBag()->add(
                'warning', 'No hay registros con este identificador '. $id);
            return array('resulado'=>'fallido', 'url'=> $url_redireccion);
        }
        $formulario = $this->createForm($formulario_base, $p);
        $formulario -> remove('guardar_crear');
        $formulario -> remove('activo');
        if($remover){
            foreach($remover as $campo){
                $formulario->remove($campo);
            }
        }
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', strtolower($titulo).' editado con éxito');
                return array('resulado'=>'exito', 'url'=> $url_redireccion);
            }
        }
        return array('form'=>$formulario->createView(), 'accion'=>'Editar '.$titulo);
    }

    public function borrar_generico($id, $request, $formulario_base, $clase, $objeto, $titulo, $url_redireccion)
    {
        $p = $this->getDoctrine()
            ->getRepository($clase)
            ->find($id);
        if (!$p)
        {
            $this->get('session')->getFlashBag()->add(
                'warning', 'No hay registros con este identificador '. $id);
            return array('resulado'=>'fallido', 'url'=> $url_redireccion);
        }
        $formulario = $this->createForm($formulario_base, $p);
        $formulario -> remove('nombre');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository($clase)
            ->createQueryBuilder(strtolower($objeto))
            ->where(strtolower($objeto).'.id = :id')
            ->andWhere(strtolower($objeto).'.activo = true')
            ->setParameter('id', $id)
            ->getQuery();


        $datos = $query->getArrayResult();
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $p->setActivo('false');
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'warning', $titulo.' borrado con éxito');
                return array('resulado'=>'exito', 'url'=> $url_redireccion);
            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = $url_redireccion;
        return array('form'=>$formulario->createView(),'datos'=>$datos, 'accion'=>'Borrar '.$titulo, 'atajo'=>$atajo);
    }
    public function registro_traza_usuario($modelo, $nombre_evento, $objeto){
        //$tableName = $em->getClassMetadata('StoreBundle:User')->getTableName();

        $evento = $this->getDoctrine()
            ->getRepository('genericoBundle:Eventos')
            ->findOneBy(array('nombre'=>$nombre_evento));

        $session = $this->getRequest()->getSession();
        $usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:Usuarios')
            ->find($session->get('usuario_id'));


        $manager = $this->getDoctrine()->getManager();
        $manager2 = $this->getDoctrine()->getManager();
        $nombre_tabla = $manager2->getClassMetadata($modelo)->getTableName();
        $elemento = $this->getDoctrine()
            ->getRepository('genericoBundle:Elementos')
            ->findOneBy(array('nombre'=>$nombre_tabla));
        $id_objeto = $objeto->getId();
        $nombre_entidad = $manager2->getClassMetadata($modelo)->getReflectionClass()->getName();
        $detalle = $nombre_tabla.','.$nombre_entidad;

        $p = new TrazaEventosUsuarios();
        $p->setElemento($elemento);
        $p->setUsuario($usuario);
        $p->setidEvento($evento);
        $p->setidObjeto($id_objeto);
        $p->setDetalles($detalle);
        $p->setFecha(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($p);
        $manager->flush();

        return array('resultado' => true);
    }
}
