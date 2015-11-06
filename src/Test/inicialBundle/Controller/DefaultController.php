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
use Test\inicialBundle\Form\BancosType;
use Test\inicialBundle\Entity\PeriodoEscolar;
use RosaMolas\usuariosBundle\Entity\RecuperarPasswordTmp;
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
use RosaMolas\usuariosBundle\Entity\Passwords;
use RosaMolas\usuariosBundle\Form\UsuariosTypeSimple;


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


    public function solicitar_passAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $email = $request->get('email');

            $query = $this->getDoctrine()->getRepository('usuariosBundle:PerfilUsuario')
                ->createQueryBuilder('perfil')
                ->where('perfil.email = :correo')
                ->andWhere('perfil.activo = true')
                ->setParameter('correo', $email)
                ->getQuery();
            $datos = $query->getArrayResult();

            if (!$datos) {
                $this->get('session')->getFlashBag()->add(
                    'danger', 'Correo no registrado'
                );
                return $this->render('inicialBundle:Default:index.html.twig');
            } else {
                $token = sha1(uniqid($datos[0]['nombreUsuario'], true));


                $perfil = $this->getDoctrine()
                    ->getRepository('usuariosBundle:PerfilUsuario')
                    ->find(($datos[0]['id']));

                $recup_pass = new RecuperarPasswordTmp();
                $recup_pass->setidPerfil($perfil);
                $recup_pass->setFecha(new \DateTime(date('Y-m-d H:i:s')));
                $recup_pass->setToken($token);
                $em = $this->getDoctrine()->getManager();

                $em->persist($recup_pass);
                $em->flush();

                $enlace = $this->generateUrl('inicial_recuperar_pass', array('token'=> $token), true);


                $mensaje_email = \Swift_Message::newInstance()
                    ->setSubject('Restaurar Contraseña Sistema Fe y Alegria')
                    ->setFrom('ed.acevedo.programacion@gmail.com')
                    ->setTo($datos[0]['email'])
                    ->setBody('Usted ha solicitado cambiar la contaseña para acceder a nuestro sistema el dia '.date('d-m-Y H:i:s').'<br><br> Para recuperacion de contraseña haga click en el siguiente enlace: '.$enlace);
                $this->get('mailer')->send($mensaje_email);
                $this->get('session')->getFlashBag()->add(
                    'success', 'enlace de recuperacion de contraseña enviado'
                );
                return $this->render('inicialBundle:Default:index.html.twig');
            }
        }
        return $this->render('inicialBundle:Default:recuperar_pass.html.twig', array('accion'=>'Solicitud Recuperar Contraseña'));
    }
    public function recuperar_passAction($token, Request $request){
        if($token && preg_match('/^[0-9A-F]{40}$/i', $token)) {

            $query = $this->getDoctrine()->getRepository('inicialBundle:RecuperarPasswordTmp')
                ->createQueryBuilder('rec_pass')
                ->select('rec_pass', 'perfil')
                ->innerJoin('rec_pass.idPerfil','perfil')
                ->where('rec_pass.token = :token')
                ->setParameter('token', $token)
                ->getQuery();

            $datos = $query->getArrayResult();
            if (!$datos)
            {
                $this->get('session')->getFlashBag()->add(
                    'danger', 'Enlace no valido'
                );
                return $this->render('inicialBundle:Default:index.html.twig');
            }
            else{
                $delta = 72000;

                if($request->server->get('REQUEST_TIME')-$datos[0]['fecha']->getTimestamp()<$delta){
                    $perfil = $this->getDoctrine()
                        ->getRepository('usuariosBundle:PerfilUsuario')
                        ->find($datos[0]['idPerfil']['id']);

                    $p = new Passwords();
                    $formulario = $this->createForm(new PasswordsType(), $p);
                    $formulario-> handleRequest($request);
                    if($request->getMethod()=='POST') {

                        if ($formulario->isValid()) {
                            $desact_pass = $this->getDoctrine()->getEntityManager();
                            $test_desact = $desact_pass->getRepository('inicialBundle:Passwords')->findOneBy(array('perfil'=>$perfil, 'activo'=>true));
                            $test_desact->setActivo(false);
                            $desact_pass->flush();

                            $p->setFechaCreacion(new \DateTime(date('Y-m-d H:i:s')));
                            $p->setPerfil($perfil);
                            $p->setActivo(true);

                            $factory = $this->get('security.encoder_factory');
                            $codificador = $factory->getEncoder($p);
                            $salt = $p->getSalt();
                            $p->setSalt($salt);
                            $pass = $codificador->encodePassword($p->getPassword(), $salt);
                            $p->setPassword($pass);
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($p);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add(
                                'success', 'Contraseña Cambiada con éxito');
                            $borrar_passtmp= $this->getDoctrine()->getEntityManager();
                            $borrar_passtmp_query = $borrar_passtmp->getRepository('inicialBundle:RecuperarPasswordTmp')->find($datos[0]['id']);
                            $borrar_passtmp->remove($borrar_passtmp_query);
                            $borrar_passtmp->flush();

                            return $this->redirect($this->generateUrl('inicial_homepage'));
                        }
                    }
                    return $this->render('inicialBundle:Default:recuperar_pass.html.twig', array('accion'=>'Recuperar Contraseña', 'form'=>$formulario->createView()));
                }
                else{
                    $this->get('session')->getFlashBag()->add(
                        'danger', 'enlace de excedio el tiempo para ser usado'
                    );
                    return $this->render('inicialBundle:Default:index.html.twig');
                }
            }
        }
        return $this->render('inicialBundle:Default:recuperar_pass.html.twig', array('accion'=>'Solicitud Recuperar Contraseña'));
    }
    public function lista_usuario_pdfAction(Request $request)
    {
        //hacer consulta simple a la bbdd


        if($request->get('_route')=='inicial_pdf_representante'){
            $query = $this->getDoctrine()->getRepository('inicialBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('inicialBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id=5')
                ->orderBy('usuario.id')
                ->getQuery();
            $elemento = 'REPRESENTANTES';

        }
        else{
            $query = $this->getDoctrine()->getRepository('inicialBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('inicialBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id!=5')
                ->orderBy('usuario.id')
                ->getQuery();
            $elemento = 'USUARIOS';
        }

        $datos = $query->getArrayResult();
        $mpdfService = $this->get('tfox.mpdfport');
        $html = "<table>
					<tr>
						<td><img src='public/images/logo-FyA.jpg' width='150px' height='auto'></td>
					</tr>
				</table>
				<br/>
				<table border='1' style='border-collapse:collapse; width:750px;'>
					<tr>
						<th colspan='4'>$elemento REGISTRADOS</th>
					</tr>
					<tr>
						<th>ID</th>
						<th>NOMBRE</th>
						<th>APELIDO</th>
						<th>CEDULA</th>
					</tr>";

        foreach($datos as $dato){
            $html.="<tr>
							<td>".$dato['id']."</td>
							<td>".$dato['nombres']."</td>
							<td>".$dato['apellidos']."</td>
							<td>".$dato['cedula']."</td>
						</tr>";
        }
        $html.=	"</table>";
        $response = $mpdfService->generatePdfResponse($html);

        return $response;

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

    public function crear_generico($request, $modelo, $formulario_base, $objeto, $accion, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos = null, $remover = null)
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
            $query = $this->getDoctrine()->getRepository('inicialBundle:' . $objeto)
                ->createQueryBuilder(strtolower($objeto))
                ->where(strtolower($objeto) . '.activo = true')
                ->getQuery();


            $datos = $query->getArrayResult();
        }
        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', $objeto.' creado con éxito'
                );
                if(array_key_exists('guardar_crear', $formulario)){
                    if ($formulario->get('guardar')->isClicked()) {
                        return $this->redirect($this->generateUrl('inicial_homepage'));
                    }
                    if ($formulario->get('guardar_crear')->isClicked()) {
                        return $this->redirect($this->generateUrl($url_redireccion));
                    }
                }
                else {
                    return $this->redirect($this->generateUrl($url_redireccion));
                }
            }
        }
        return $this->render('inicialBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>$accion, 'url_editar'=>$url_editar,
            'url_borrar'=>$url_borrar, 'operaciones_datos'=>true));
    }

    public function editar_generico($id, $request, $formulario_base, $objeto, $accion, $url_redireccion, $plantilla, $remover = null)
    {

        $p = $this->getDoctrine()
            ->getRepository('inicialBundle:'.$objeto)
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe '.$objeto.' con este id: '.$id);
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
                    'success', $objeto.' editado con éxito'
                );
                return $this->redirect($this->generateUrl($url_redireccion));
            }
        }
        return $this->render('inicialBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(), 'accion'=>$accion));
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
        $curso = New Curso();
        $form = New CursoType('Crear Curso');
        return $this->crear_generico($request, $curso, $form,'Curso', 'Crear Curso', 'inicial_agregar_curso', 'inicial_editar_curso', 'inicial_borrar_curso', 'mantenimiento', 'true');
    }
    public function editar_cursoAction($id, Request $request){
        $form = New CursoType('Editar Curso');
        return $this->editar_generico($id, $request, $form,'Curso', 'Editar Curso', 'inicial_agregar_curso', 'mantenimiento');
    }
    public function borrar_cursoAction($id, Request $request){
        $form = New CursoType('Borrar Curso');
        return $this->borrar_generico($id, $request, $form,'Curso', 'Borrar Curso', 'inicial_agregar_curso', 'borrar', 'true');
    }
    public function crear_conceptos_facturaAction(Request $request){
        $curso = New ConceptosFactura();
        $form = new ConceptosFacturaType('Crear Concepto Factura');
        return $this->crear_generico($request, $curso, $form, 'ConceptosFactura', 'Crear Concepto Factura', 'inicial_agregar_conceptos_factura', 'inicial_editar_conceptos_factura', 'inicial_borrar_conceptos_factura', 'mantenimiento', 'true');
    }
    public function editar_conceptos_facturaAction($id, Request $request){
        $form = New ConceptosFacturaType('Editar Concepto Factura');
        return $this->editar_generico($id, $request, $form, 'ConceptosFactura', 'Editar Concepto Factura', 'inicial_agregar_conceptos_factura', 'mantenimiento');
    }
    public function borrar_conceptos_facturaAction($id, Request $request){
        $form = New ConceptosFacturaType('Borrar Concepto Factura');
        return $this->borrar_generico($id, $request, $form, 'ConceptosFactura', 'Borrar Concepto Factura', 'inicial_agregar_conceptos_factura', 'borrar', 'true');
    }
    public function crear_bancoAction(Request $request){
        $curso = New Bancos();
        $form = new BancosType('Crear Banco');
        return $this->crear_generico($request, $curso, $form, 'Bancos', 'Crear Banco', 'inicial_agregar_banco', 'inicial_editar_banco', 'inicial_borrar_banco', 'mantenimiento', 'true');
    }
    public function editar_bancoAction($id, Request $request){
        $form = New BancosType('Editar Banco');
        return $this->editar_generico($id, $request, $form, 'Bancos', 'Editar Banco', 'inicial_agregar_banco', 'mantenimiento');
    }
    public function borrar_bancoAction($id, Request $request){
        $form = New BancosType('Borrar Banco');
        return $this->borrar_generico($id, $request, $form, 'Bancos', 'Borrar Banco', 'inicial_agregar_banco', 'borrar', 'true');
    }
    public function crear_rolAction(Request $request){
        $curso = New Roles();
        $form = new RolesType('Crear Rol');
        return $this->crear_generico($request, $curso, $form, 'Roles', 'Crear Rol', 'inicial_agregar_rol', 'inicial_editar_rol', 'inicial_borrar_rol', 'mantenimiento', 'true');
    }
    public function editar_rolAction($id, Request $request){
        $form = New RolesType('Editar Rol');
        return $this->editar_generico($id, $request, $form, 'Roles', 'Editar Rol', 'inicial_agregar_rol', 'mantenimiento');
    }
    public function borrar_rolAction($id, Request $request){
        $form = New RolesType('Borrar Rol');
        return $this->borrar_generico($id, $request, $form, 'Roles', 'Borrar Rol', 'inicial_agregar_rol', 'borrar', 'true');
    }
    public function crear_periodoAction(Request $request){
        $curso = New PeriodoEscolar();
        $form = new PeriodoEscolarType('Crear Periodo Escolar');
        return $this->crear_generico($request, $curso, $form, 'PeriodoEscolar', 'Crear Periodo Escolar', 'inicial_agregar_periodo', 'inicial_editar_periodo', 'inicial_borrar_periodo', 'mantenimiento', 'true');
    }
    public function editar_periodoAction($id, Request $request){
        $form = New PeriodoEscolarType('Editar Periodo Escolar');
        return $this->editar_generico($id, $request, $form, 'PeriodoEscolar', 'Editar Periodo Escolar', 'inicial_agregar_periodo', 'mantenimiento');
    }
    public function borrar_periodoAction($id, Request $request){
        $form = New PeriodoEscolarType('Borrar Periodo Escolar');
        return $this->borrar_generico($id, $request, $form, 'PeriodoEscolar', 'Borrar Periodo Escolar', 'inicial_agregar_periodo', 'borrar', 'true');
    }
    public function crear_elementoAction(Request $request){
        $curso = New Elementos();
        $form = new ElementosType('Crear elemento del sistema');
        return $this->crear_generico($request, $curso, $form, 'Elementos', 'Crear Elemento del Sistema', 'inicial_agregar_elemento', 'inicial_editar_elemento', 'inicial_borrar_elemento', 'mantenimiento', 'true');
    }
    public function editar_elementoAction($id, Request $request){
        $form = New ElementosType('Editar elemento del sistema');
        return $this->editar_generico($id, $request, $form, 'Elementos', 'Editar Elemento del Sistema', 'inicial_agregar_elemento', 'mantenimiento');
    }
    public function borrar_elementoAction($id, Request $request){
        $form = New ElementosType('Borrar elemento del sistema');
        return $this->borrar_generico($id, $request, $form, 'Elementos', 'Borrar Elemento del Sistema', 'inicial_agregar_elemento', 'borrar', 'true');
    }
    public function crear_eventoAction(Request $request){
        $curso = New Eventos();
        $form = new EventosType('Crear evento del sistema');
        return $this->crear_generico($request, $curso, $form, 'Eventos', 'Crear Evento del Sistema', 'inicial_agregar_evento', 'inicial_editar_evento', 'inicial_borrar_evento', 'mantenimiento', 'true');
    }
    public function editar_eventoAction($id, Request $request){
        $form = New EventosType('Editar evento del sistema');
        return $this->editar_generico($id, $request, $form, 'Eventos', 'Editar Evento del Sistema', 'inicial_agregar_evento', 'mantenimiento');
    }
    public function borrar_eventoAction($id, Request $request){
        $form = New EventosType('Borrar evento del sistema');
        return $this->borrar_generico($id, $request, $form, 'Eventos', 'Borrar Evento del Sistema', 'inicial_agregar_evento', 'borrar', 'true');
    }
    public function crear_permisoAction(Request $request){
        $curso = New Permisos();
        $form = new PermisosType('Crear permiso del sistema');
        return $this->crear_generico($request, $curso, $form, 'Permisos', 'Crear Permisos del Sistema', 'inicial_agregar_permiso', 'inicial_editar_permiso', 'inicial_borrar_permiso', 'mantenimiento', 'true');
    }
    public function editar_permisoAction($id, Request $request){
        $form = New PermisosType('Editar permiso del sistema');
        return $this->editar_generico($id, $request, $form, 'Permisos', 'Editar Permisos del Sistema', 'inicial_agregar_permiso', 'mantenimiento');
    }
    public function borrar_permisoAction($id, Request $request){
        $form = New PermisosType('Borrar permiso del sistema');
        return $this->borrar_generico($id, $request, $form, 'Permisos', 'Borrar Permisos del Sistema', 'inicial_agregar_permiso', 'borrar', 'true');
    }

    public function crear_seccion3Action(Request $request){
        $curso = New Seccion();
        $form = new SeccionType('Crear Seccion');
        return $this->crear_generico($request, $curso, $form, 'Seccion', 'Crear Seccion', 'inicial_agregar_seccion', 'inicial_editar_seccion', 'inicial_borrar_seccion', 'mantenimiento', 'true');
    }

    public function crear_seccionAction(Request $request){
        $curso = New Seccion();
        $form = new SeccionType('Crear Seccion');
        return $this->get('funciones_genericas')->crear_generico($request, $curso, $form, 'Seccion', 'Crear Seccion', 'inicial_agregar_seccion', 'inicial_editar_seccion', 'inicial_borrar_seccion', 'mantenimiento', 'true');
    }
    public function editar_seccionAction($id, Request $request){
        $form = new SeccionType('Editar Seccion');
        return $this->editar_generico($id, $request, $form, 'Seccion', 'Editar Seccion', 'inicial_agregar_seccion', 'mantenimiento');
    }
    public function borrar_seccionAction($id, Request $request){
        $form = new SeccionType('Borrar Seccion');
        return $this->borrar_generico($id, $request, $form, 'Seccion', 'Borrar Seccion', 'inicial_agregar_seccion', 'borrar', 'true');
    }
    public function crear_tipo_usuarioAction(Request $request){
        $curso = New TipoUsuario();
        $form = new TipoUsuarioType('Crear Tipo Usuario');
        return $this->crear_generico($request, $curso, $form, 'TipoUsuario', 'Crear Tipo Usuario', 'inicial_agregar_tipo_usuario', 'inicial_editar_tipo_usuario', 'inicial_borrar_tipo_usuario', 'mantenimiento', 'true');
    }
    public function editar_tipo_usuarioAction($id, Request $request){
        $form = new TipoUsuarioType('Editar Tipo Usuario');
        return $this->editar_generico($id, $request, $form, 'TipoUsuario', 'Editar Tipo Usuario', 'inicial_agregar_tipo_usuario', 'mantenimiento');
    }
    public function borrar_tipo_usuarioAction($id, Request $request){
        $form = new TipoUsuarioType('Borrar Tipo Usuario');
        return $this->borrar_generico($id, $request, $form, 'TipoUsuario', 'Borrar Tipo Usuario', 'inicial_agregar_tipo_usuario', 'borrar', 'true');
    }
    public function crear_tipo_contactoAction(Request $request){
        $curso = New TipoContacto();
        $form = new TipoContactoType('Crear Tipo Contacto');
        return $this->crear_generico($request, $curso, $form, 'TipoContacto', 'Crear Tipo Contacto', 'inicial_agregar_tipo_contacto', 'inicial_editar_tipo_contacto', 'inicial_borrar_tipo_contacto', 'mantenimiento', 'true');
    }
    public function editar_tipo_contactoAction($id, Request $request){
        $form = new TipoContactoType('Editar Tipo Contacto');
        return $this->editar_generico($id, $request, $form, 'TipoContacto', 'Editar Tipo Contacto', 'inicial_agregar_tipo_contacto', 'mantenimiento');
    }
    public function borrar_tipo_contactoAction($id, Request $request){
        $form = new TipoContactoType('Borrar Tipo Contacto');
        return $this->borrar_generico($id, $request, $form, 'TipoContacto', 'Borrar Tipo Contacto', 'inicial_agregar_tipo_contacto', 'borrar', 'true');
    }
    public function crear_representante_contactoAction(Request $request){
        $curso = New RepresentanteContacto();
        $form = new RepresentanteContactoType('Crear Contacto Representante');
        return $this->crear_generico($request, $curso, $form, 'RepresentanteContacto', 'Crear Contacto Representante', 'inicial_agregar_representante_contacto', 'inicial_editar_representante_contacto', 'inicial_borrar_representante_contacto', 'mantenimiento', 'true');
    }
    public function editar_representante_contactoAction($id, Request $request){
        $form = new TipoContactoType('Editar Contacto Representante');
        return $this->editar_generico($id, $request, $form, 'RepresentanteContacto', 'Editar Tipo Contacto', 'inicial_agregar_representante_contacto', 'mantenimiento');
    }
    public function borrar_representante_contactoAction($id, Request $request){
        $form = new RepresentanteContactoType('Borrar Contacto Representante');
        return $this->borrar_generico($id, $request, $form, 'RepresentanteContacto', 'Borrar Tipo Contacto', 'inicial_agregar_representante_contacto', 'borrar', 'true');
    }
    public function crear_tipo_facturaAction(Request $request){
        $curso = New TipoFactura();
        $form = new TipoFacturaType('Crear Tipo Factura');
        return $this->crear_generico($request, $curso, $form, 'TipoFactura', 'Crear Tipo Factura', 'inicial_agregar_tipo_factura', 'inicial_editar_tipo_factura', 'inicial_borrar_tipo_factura', 'mantenimiento', 'true');
    }
    public function editar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Editar Tipo Factura');
        return $this->editar_generico($id, $request, $form, 'TipoFactura', 'Editar Tipo Factura', 'inicial_agregar_tipo_factura', 'mantenimiento');
    }
    public function borrar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Borrar Tipo Factura');
        return $this->borrar_generico($id, $request, $form, 'TipoFactura', 'Borrar Tipo Factura', 'inicial_agregar_tipo_factura', 'borrar', 'true');
    }

    public function editar_usuarioAction($id, Request $request){
        $form = new UsuariosTypeSimple('Editar Usuario');
        return $this->editar_generico($id, $request, $form, 'Usuarios', 'Editar Usuario', 'inicial_lista_usuario', 'crear_usuario');
    }

    public function editar_representanteAction($id, Request $request){
        $form = new UsuariosTypeSimple('Editar Representante');
        $remover =['tipoUsuario', 'principal'];
        return $this->editar_generico($id, $request, $form, 'Usuarios', 'Editar Usuario', 'inicial_lista_usuario', 'crear_usuario', $remover);
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
