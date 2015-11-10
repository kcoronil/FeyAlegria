<?php

namespace Test\inicialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\genericoBundle\Entity\Bancos;
use Test\inicialBundle\Entity\ConceptosFactura;
use RosaMolas\genericoBundle\Entity\Elementos;
use RosaMolas\genericoBundle\Entity\Eventos;
use Test\inicialBundle\Entity\PeriodoEscolarCurso;
use RosaMolas\usuariosBundle\Entity\Permisos;
use RosaMolas\usuariosBundle\Entity\RepresentanteContacto;
use Test\inicialBundle\Entity\Seccion;
use RosaMolas\usuariosBundle\Entity\TipoContacto;
use Test\inicialBundle\Entity\TipoFactura;
use RosaMolas\usuariosBundle\Entity\TipoUsuario;
use RosaMolas\genericoBundle\Form\BancosType;
use Test\inicialBundle\Entity\PeriodoEscolar;
use Test\inicialBundle\Form\ConceptosFacturaType;
use Test\inicialBundle\Form\ElementosType;
use Test\inicialBundle\Form\EventosType;
use Test\inicialBundle\Form\PeriodoEscolarCursoType;
use Test\inicialBundle\Form\PeriodoEscolarType;
use RosaMolas\usuariosBundle\Form\PermisosType;
use RosaMolas\usuariosBundle\Form\RepresentanteContactoType;
use Test\inicialBundle\Form\SeccionType;
use RosaMolas\usuariosBundle\Form\TipoContactoType;
use Test\inicialBundle\Form\TipoFacturaType;
use RosaMolas\usuariosBundle\Form\TipoUsuarioType;
use RosaMolas\usuariosBundle\Entity\Roles;
use RosaMolas\usuariosBundle\Form\RolesType;
use RosaMolas\usuariosBundle\Entity\PerfilUsuario;
use RosaMolas\usuariosBundle\Form\PerfilUsuarioType;
use Test\inicialBundle\Entity\Curso;
use Test\inicialBundle\Form\CursoType;



class DefaultController extends Controller
{
    public function indexAction(request $request)
    {
        $session = $this->getRequest()->getSession();
        if($request->getMethod()=='POST'){
            $session->clear();
            $username=$request->get('usuario');
            $password= $request->get('password');
            //$em = $this->getDoctrine()->getManager();


            $query = $this->getDoctrine()->getRepository('usuariosBundle:PerfilUsuario')
                ->createQueryBuilder('perfil')
                ->select('perfil', 'usuario', 'tipo_usuario')
                ->innerJoin('usuariosBundle:Usuarios', 'usuario', 'WITH', 'perfil.usuario = usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('perfil.nombreUsuario = :user')
                ->setParameter('user', $username)

                ->getQuery();

            //$user = $query->getOneOrNullResult();

            $user = $query->getArrayResult();


            $passwords = $this->getDoctrine()
                ->getRepository('usuariosBundle:Passwords')
                ->findOneBy(array('perfil'=>$user[0]['id'],'activo'=>true));

            if ($user){
                $factory = $this->get('security.encoder_factory');
                $codificador = $factory->getEncoder($passwords);
                $validador = $codificador->isPasswordValid($passwords->getPassword(), $password, $passwords->getSalt());
                if($validador) {
                    $session = $request->getSession();
                    $session->set("email", $user[0]['email']);
                    $session->set("perfil_activo", $user[0]['activo']);
                    $session->set("pass_activo", $passwords->getActivo());

                    if ($session->get('perfil_activo') == 1) {

                        if ($session->get('pass_activo') == 1) {
                            $session->set("id", $user[0]['id']);
                            $session->set("autenticado", true);
                            $session->set("nombre_usuario", $user[0]['nombreUsuario']);
                            $session->set("nombres", $user[1]['nombres']);
                            $session->set("tipo_usuario", $user[2]['nombre']);
                            $session->set("id_tipo_usuario", $user[2]['id']);
                            return $this->render('inicialBundle:Default:index.html.twig');
                        } else {
                            $this->get('session')->getFlashBag()->add(
                                'warning', 'Clave Inactiva debe actualizar su clave');
                        }
                    } else {
                        $this->get('session')->getFlashBag()->add(
                            'danger', 'Usuario Inactivo Contactar con el administrador del sistema');
                        return $this->render('inicialBundle:Default:index.html.twig');
                    }
                }
                else{
                    $this -> get('session') -> getFlashBag() -> add(
                        'danger', 'Datos incorrectos'
                    );
                    return $this->render('inicialBundle:Default:index.html.twig');
                }
            }
            else{
                    $this -> get('session') -> getFlashBag() -> add(
                        'danger', 'Datos incorrectos'
                    );
                return $this->render('inicialBundle:Default:index.html.twig');
            }
        }
        return $this->render('inicialBundle:Default:index.html.twig');
    }
    public function logoutAction(Request $request)
    {
        $session = $request ->getSession();
        $session -> clear();
        $this -> get('session') -> getFlashBag() -> add(
            'info', 'Sesión Cerrada'
        );
        return $this->redirect($this->generateUrl('inicial_homepage'));
    }

    public function consultaAction()
    {
        $query = $this->getDoctrine()->getRepository('inicialBundle:PeriodoEscolar')
            ->createQueryBuilder('periodoescolar')
            ->select('periodoescolar')
            ->orderBy('periodoescolar.id')
            ->getQuery();

        $datos = $query->getArrayResult();

        return $this->render('inicialBundle:Default:lista_periodo.html.twig', array('accion'=>'Listado de Periodo', 'datos'=>$datos));

    }
    public function crear_perfilAction(Request $request)
    {
        $p = new PerfilUsuario();
        $formulario = $this->createForm(new PerfilUsuarioType('Crear Perfil'),$p);
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $p->setFechaCreacion(new \DateTime(date('Y-m-d H:i:s')));

                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Rol Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_perfil'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_perfil.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Perfil de Usuario'));
    }

    public function borrar_generico($id, $request, $formulario_base, $objeto, $accion, $url_redireccion, $plantilla)
    {
        $p = $this->getDoctrine()
            ->getRepository('inicialBundle:'.$objeto)
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe concepto de Factura con este id: '.$id);
        }
        $formulario = $this->createForm($formulario_base, $p);
        $formulario -> remove('nombre');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('inicialBundle:'.$objeto)
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
                    'warning', $objeto.' borrado con éxito'
                );

                return $this->redirect($this->generateUrl($url_redireccion));
            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = $url_redireccion;
        return $this->render('inicialBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>$accion, 'atajo'=>$atajo));
    }
    public function crear_cursoAction(Request $request){
        $modelo = New Curso();
        $form = New CursoType('Crear Curso');
        $objeto = 'Curso';
        $clase = 'inicialBundle:Curso';
        $titulo = 'Curso';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_cursoAction($id, Request $request){
        $form = New CursoType('Editar Curso');
        $objeto = 'Curso';
        $clase = 'inicialBundle:Curso';
        $titulo = 'Curso';
        $url_redireccion = 'inicial_agregar_curso';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_cursoAction($id, Request $request){
        $form = New CursoType('Borrar Curso');
        return $this->borrar_generico($id, $request, $form,'Curso', 'Borrar Curso', 'inicial_agregar_curso', 'borrar', 'true');
    }
    public function crear_conceptos_facturaAction(Request $request){
        $modelo = New ConceptosFactura();
        $form = new ConceptosFacturaType('Crear Concepto Factura');
        $objeto = 'ConceptosFactura';
        $clase = 'inicialBundle:ConceptosFactura';
        $titulo = 'Conceptos de Factura';
        $url_redireccion = 'inicial_agregar_conceptos_factura';
        $url_editar = 'inicial_editar_conceptos_factura';
        $url_borrar = 'inicial_borrar_conceptos_factura';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_conceptos_facturaAction($id, Request $request){
        $form = New ConceptosFacturaType('Editar Concepto Factura');
        $objeto = 'ConceptosFactura';
        $clase = 'inicialBundle:ConceptosFactura';
        $titulo = 'Conceptos de Factura';
        $url_redireccion = 'inicial_agregar_conceptos_factura';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_conceptos_facturaAction($id, Request $request){
        $form = New ConceptosFacturaType('Borrar Concepto Factura');
        return $this->borrar_generico($id, $request, $form, 'ConceptosFactura', 'Borrar Concepto Factura', 'inicial_agregar_conceptos_factura', 'borrar', 'true');
    }
    public function crear_bancoAction(Request $request){
        $modelo = New Bancos();
        $form = new BancosType('Crear Banco');
        $objeto = 'Bancos';
        $clase = 'genericoBundle:Bancos';
        $titulo = 'Bancos';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_bancoAction($id, Request $request){
        $form = New BancosType('Editar Banco');
        $objeto = 'Bancos';
        $clase = 'genericoBundle:Bancos';
        $titulo = 'Banco';
        $url_redireccion = 'inicial_agregar_bancos';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_bancoAction($id, Request $request){
        $form = New BancosType('Borrar Banco');
        return $this->borrar_generico($id, $request, $form, 'Bancos', 'Borrar Banco', 'inicial_agregar_banco', 'borrar', 'true');
    }
    public function crear_rolAction(Request $request){
        $modelo = New Roles();
        $form = new RolesType('Crear Rol');
        $objeto = 'Roles';
        $clase = 'usuariosBundle:Roles';
        $titulo = 'Rol';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_rolAction($id, Request $request){
        $form = New RolesType('Editar Rol');
        $objeto = 'Roles';
        $clase = 'usuariosBundle:Roles';
        $titulo = 'Rol';
        $url_redireccion = 'inicial_agregar_roles';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_rolAction($id, Request $request){
        $form = New RolesType('Borrar Rol');
        return $this->borrar_generico($id, $request, $form, 'Roles', 'Borrar Rol', 'inicial_agregar_rol', 'borrar', 'true');
    }
    public function crear_periodoAction(Request $request){
        $modelo = New PeriodoEscolar();
        $form = new PeriodoEscolarType('Crear Periodo Escolar');
        $objeto = 'PeriodoEscolar';
        $clase = 'inicialBundle:PeriodoEscolar';
        $titulo = 'Periodo Escolar';
        $url_redireccion = 'inicial_agregar_periodo';
        $url_editar = 'inicial_editar_periodo';
        $url_borrar = 'inicial_borrar_periodo';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_periodoAction($id, Request $request){
        $form = New PeriodoEscolarType('Editar Periodo Escolar');
        $objeto = 'PeriodoEscolar';
        $clase = 'inicialBundle:PeriodoEscolar';
        $titulo = 'Periodo Escolar';
        $url_redireccion = 'inicial_agregar_periodo';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_periodoAction($id, Request $request){
        $form = New PeriodoEscolarType('Borrar Periodo Escolar');
        return $this->borrar_generico($id, $request, $form, 'PeriodoEscolar', 'Borrar Periodo Escolar', 'inicial_agregar_periodo', 'borrar', 'true');
    }
    public function crear_elementoAction(Request $request){
        $modelo = New Elementos();
        $form = new ElementosType('Crear elemento del sistema');
        $objeto = 'Elementos';
        $clase = 'genericoBundle:Elementos';
        $titulo = 'Elemento';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_elementoAction($id, Request $request){
        $form = New ElementosType('Editar elemento del sistema');
        $objeto = 'Elementos';
        $clase = 'genericoBundle:Elementos';
        $titulo = 'Elemento del Sistema';
        $url_redireccion = 'inicial_agregar_elementos';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_elementoAction($id, Request $request){
        $form = New ElementosType('Borrar elemento del sistema');
        return $this->borrar_generico($id, $request, $form, 'Elementos', 'Borrar Elemento del Sistema', 'inicial_agregar_elemento', 'borrar', 'true');
    }
    public function crear_eventoAction(Request $request){
        $modelo = New Eventos();
        $form = new EventosType('Crear evento del sistema');
        $objeto = 'Eventos';
        $clase = 'genericoBundle:Eventos';
        $titulo = 'Evento del Sistema';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_eventoAction($id, Request $request){
        $form = New EventosType('Editar evento del sistema');
        $objeto = 'Eventos';
        $clase = 'genericoBundle:Eventos';
        $titulo = 'Evento del Sistema';
        $url_redireccion = 'inicial_agregar_eventos';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_eventoAction($id, Request $request){
        $form = New EventosType('Borrar evento del sistema');
        return $this->borrar_generico($id, $request, $form, 'Eventos', 'Borrar Evento del Sistema', 'inicial_agregar_evento', 'borrar', 'true');
    }
    public function crear_permisoAction(Request $request){
        $modelo = New Permisos();
        $form = new PermisosType('Crear permiso del sistema');
        $objeto = 'Permisos';
        $clase = 'usuariosBundle:Permisos';
        $titulo = 'Permisos del Sistema';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_permisoAction($id, Request $request){
        $form = New PermisosType('Editar permiso del sistema');
        $objeto = 'Permisos';
        $clase = 'usuariosBundle:Permisos';
        $titulo = 'Permisos del Sistema';
        $url_redireccion = 'inicial_agregar_permisos';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_permisoAction($id, Request $request){
        $form = New PermisosType('Borrar permiso del sistema');
        return $this->borrar_generico($id, $request, $form, 'Permisos', 'Borrar Permisos del Sistema', 'inicial_agregar_permiso', 'borrar', 'true');
    }
    public function crear_seccionAction(Request $request){
        $modelo = New Seccion();
        $form = new SeccionType('Crear Sección');
        $objeto = 'Seccion';
        $clase = 'inicialBundle:Seccion';
        $titulo = 'Sección';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_seccionAction($id, Request $request){
        $form = new SeccionType('Editar Seccion');
        $objeto = 'Seccion';
        $clase = 'inicialBundle:Seccion';
        $titulo = 'Sección';
        $url_redireccion = 'inicial_agregar_seccion';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_seccionAction($id, Request $request){
        $form = new SeccionType('Borrar Seccion');
        return $this->borrar_generico($id, $request, $form, 'Seccion', 'Borrar Seccion', 'inicial_agregar_seccion', 'borrar', 'true');
    }
    public function crear_tipo_usuarioAction(Request $request){
        $modelo = New TipoUsuario();
        $form = new TipoUsuarioType('Crear Tipo Usuario');
        $objeto = 'TipoUsuario';
        $clase = 'usuariosBundle:TipoUsuario';
        $titulo = 'Tipo Usuario';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_usuario';
        $url_editar = 'inicial_editar_tipo_usuario';
        $url_borrar = 'inicial_borrar_tipo_usuario';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_tipo_usuarioAction($id, Request $request){
        $form = new TipoUsuarioType('Editar Tipo Usuario');
        $objeto = 'TipoUsuario';
        $clase = 'usuariosBundle:TipoUsuario';
        $titulo = 'Tipo de Usuario';
        $url_redireccion = 'inicial_agregar_tipo_usuario';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_tipo_usuarioAction($id, Request $request){
        $form = new TipoUsuarioType('Borrar Tipo Usuario');
        return $this->borrar_generico($id, $request, $form, 'TipoUsuario', 'Borrar Tipo Usuario', 'inicial_agregar_tipo_usuario', 'borrar', 'true');
    }
    public function crear_tipo_contactoAction(Request $request){
        $modelo = New TipoContacto();
        $form = new TipoContactoType('Crear Tipo Contacto');
        $objeto = 'TipoContacto';
        $clase = 'usuariosBundle:TipoContacto';
        $titulo = 'Tipo de Contacto';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_contacto';
        $url_editar = 'inicial_editar_tipo_contacto';
        $url_borrar = 'inicial_borrar_tipo_contacto';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_tipo_contactoAction($id, Request $request){
        $form = new TipoContactoType('Editar Tipo Contacto');
        $objeto = 'TipoContacto';
        $clase = 'usuariosBundle:TipoContacto';
        $titulo = 'Tipo de Contacto';
        $url_redireccion = 'inicial_agregar_tipo_contacto';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_tipo_contactoAction($id, Request $request){
        $form = new TipoContactoType('Borrar Tipo Contacto');
        return $this->borrar_generico($id, $request, $form, 'TipoContacto', 'Borrar Tipo Contacto', 'inicial_agregar_tipo_contacto', 'borrar', 'true');
    }
    public function crear_representante_contactoAction(Request $request){
        $modelo = New RepresentanteContacto();
        $form = new RepresentanteContactoType('Crear Contacto Representante');
        $objeto = 'RepresentanteContacto';
        $clase = 'inicialBundle:RepresentanteContacto';
        $titulo = 'Contacto para Representante';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_representante_contacto';
        $url_editar = 'inicial_editar_representante_contacto';
        $url_borrar = 'inicial_borrar_representante_contacto';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_representante_contactoAction($id, Request $request){
        $form = new TipoContactoType('Editar Contacto Representante');
        $objeto = 'RepresentanteContacto';
        $clase = 'usuariosBundle:RepresentanteContacto';
        $titulo = 'Curso';
        $url_redireccion = 'inicial_agregar_representante_contacto';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function borrar_representante_contactoAction($id, Request $request){
        $form = new RepresentanteContactoType('Borrar Contacto Representante');
        return $this->borrar_generico($id, $request, $form, 'RepresentanteContacto', 'Borrar Tipo Contacto', 'inicial_agregar_representante_contacto', 'borrar', 'true');
    }
    public function crear_tipo_facturaAction(Request $request){
        $modelo = New TipoFactura();
        $form = new TipoFacturaType('Crear Tipo Factura');
        $objeto = 'TipoFactura';
        $clase = 'inicialBundle:TipoFactura';
        $titulo = 'Tipo de Factura';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_factura';
        $url_editar = 'inicial_editar_tipo_factura';
        $url_borrar = 'inicial_borrar_tipo_factura';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }

    public function editar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Editar Tipo Factura');
        $objeto = 'TipoFactura';
        $clase = 'inicialBundle:TipoFactura';
        $titulo = 'Tipo Factura';
        $url_redireccion = 'inicial_agregar_tipo_factura';
        $plantilla = 'inicialBundle:Default:mantenimiento';
        $remover = null;
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));

        return $this->editar_generico($id, $request, $form, 'TipoFactura', 'Editar Tipo Factura', 'inicial_agregar_tipo_factura', 'mantenimiento');
    }
    public function borrar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Borrar Tipo Factura');
        return $this->borrar_generico($id, $request, $form, 'TipoFactura', 'Borrar Tipo Factura', 'inicial_agregar_tipo_factura', 'borrar', 'true');
    }


    public function crear_gradoAction(Request $request){
        $p = New PeriodoEscolarCurso();
        $formulario = $this->createForm(new PeriodoEscolarCursoType('Crear Grado'), $p);
        $formulario-> handleRequest($request);
        $query = $this->getDoctrine()->getRepository('inicialBundle:PeriodoEscolarCurso')
            ->createQueryBuilder('grado')
            ->select('grado', 'seccion_tbl.nombre as seccion', 'curso_tbl.nombre as curso', 'periodo_tbl.nombre as periodo_escolar')
            ->where('grado.activo = true')
            ->innerJoin('inicialBundle:Seccion', 'seccion_tbl', 'WITH', 'grado.seccion = seccion_tbl.id')
            ->innerJoin('inicialBundle:Curso', 'curso_tbl', 'WITH', 'grado.curso = curso_tbl.id')
            ->innerJoin('inicialBundle:PeriodoEscolar', 'periodo_tbl', 'WITH', 'grado.periodoEscolar = periodo_tbl.id')
            ->getQuery();

        $datos = $query->getArrayResult();

        if($request->getMethod()=='POST') {
            if ($formulario->isValid()){
                $p->setActivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Grado creado con éxito'
                );

                return $this->redirect($this->generateUrl('inicial_agregar_grado'));
            }
        }
        return $this->render('inicialBundle:Default:crear_grado.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>'Crear Grado', 'url_editar'=>'inicial_editar_grado',
            'url_borrar'=>'inicial_borrar_grado'));
    }
    public function editar_gradoAction($id, Request $request)
    {

        $p = $this->getDoctrine()
            ->getRepository('inicialBundle:PeriodoEscolarCurso')
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe grado con este id: '.$id);
        }
        $formulario = $this->createForm(new PeriodoEscolarCursoType('Editar Grado'), $p);
        $formulario -> remove('guardar_crear');
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Grado editado con éxito'
                );
                return $this->redirect($this->generateUrl('inicial_agregar_grado'));
            }
        }
        return $this->render('inicialBundle:Default:crear_grado.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Editar Grado'));
    }
    public function borrar_gradoAction($id, Request $request)
    {
        $p = $this->getDoctrine()
            ->getRepository('inicialBundle:PeriodoEscolarCurso')
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe concepto de Factura con este id: '.$id);
        }
        $formulario = $this->createForm(new PeriodoEscolarCursoType('Borrar Grado'), $p);
        $formulario -> remove('seccion');
        $formulario -> remove('curso');
        $formulario -> remove('periodoEscolar');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('inicialBundle:PeriodoEscolarCurso')
            ->createQueryBuilder('grado')
            ->select('grado', 'seccion_tbl.nombre as seccion', 'curso_tbl.nombre as curso', 'periodo_tbl.nombre as periodo_escolar')
            ->where('grado.activo = true')
            ->innerJoin('inicialBundle:Seccion', 'seccion_tbl', 'WITH', 'grado.seccion = seccion_tbl.id')
            ->innerJoin('inicialBundle:Curso', 'curso_tbl', 'WITH', 'grado.curso = curso_tbl.id')
            ->innerJoin('inicialBundle:PeriodoEscolar', 'periodo_tbl', 'WITH', 'grado.periodoEscolar = periodo_tbl.id')
            ->getQuery();


        $datos = $query->getArrayResult();
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $p->setActivo('false');
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'warning', 'Grado borrado con éxito'
                );

                return $this->redirect($this->generateUrl('inicial_agregar_grado'));
            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = 'inicial_agregar_grado';
        return $this->render('inicialBundle:Default:borrar.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>'Borrar Grado', 'atajo'=>$atajo));
    }
}
