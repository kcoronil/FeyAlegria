<?php

namespace Test\inicialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Test\inicialBundle\Entity\Bancos;
use Test\inicialBundle\Entity\ConceptosFactura;
use Test\inicialBundle\Entity\Elementos;
use Test\inicialBundle\Entity\Eventos;
use Test\inicialBundle\Entity\PeriodoEscolarCurso;
use Test\inicialBundle\Entity\Permisos;
use Test\inicialBundle\Entity\Seccion;
use Test\inicialBundle\Entity\TipoContacto;
use Test\inicialBundle\Entity\TipoFactura;
use Test\inicialBundle\Entity\TipoUsuario;
use Test\inicialBundle\Form\BancosType;
use Test\inicialBundle\Entity\PeriodoEscolar;
use Test\inicialBundle\Entity\RecuperarPasswordTmp;
use Test\inicialBundle\Entity\Usuarios;
use Test\inicialBundle\Form\AlumnosTypeUsuario;
use Test\inicialBundle\Form\ConceptosFacturaType;
use Test\inicialBundle\Form\ElementosType;
use Test\inicialBundle\Form\EventosType;
use Test\inicialBundle\Form\PeriodoEscolarCursoType;
use Test\inicialBundle\Form\PeriodoEscolarType;
use Test\inicialBundle\Form\PermisosType;
use Test\inicialBundle\Form\SeccionType;
use Test\inicialBundle\Form\TipoContactoType;
use Test\inicialBundle\Form\TipoFacturaType;
use Test\inicialBundle\Form\TipoUsuarioType;
use Test\inicialBundle\Form\UsuariosType;
use Test\inicialBundle\Entity\Roles;
use Test\inicialBundle\Form\RolesType;
use Test\inicialBundle\Entity\PerfilUsuario;
use Test\inicialBundle\Form\PerfilUsuarioType;
use Test\inicialBundle\Entity\Alumnos;
use Test\inicialBundle\Form\AlumnosTypeSimple;
use Test\inicialBundle\Entity\Curso;
use Test\inicialBundle\Form\CursoType;
use Test\inicialBundle\Entity\Passwords;
use Test\inicialBundle\Form\PasswordsType;
use Test\inicialBundle\Form\UsuariosTypeSimple;


class DefaultController extends Controller
{
    public function indexAction(request $request)
    {
        $session = $this->getRequest()->getSession();
        if($request->getMethod()=='POST'){
            $session->clear();
            $username=$request->get('usuario');
            $password= $request->get('password');
            $em = $this->getDoctrine()->getManager();


            $query = $this->getDoctrine()->getRepository('inicialBundle:PerfilUsuario')
                ->createQueryBuilder('perfil')
                ->select('perfil.id as id_perfil', 'perfil.nombreUsuario', 'perfil.email', 'perfil.activo as perfil_activo', 'usuario.nombres', 'tipo_usuario.id as id_tipo_usuario', 'tipo_usuario.nombre as nombre_tipo_usuario', 'password.activo as pass_activo')
                ->innerJoin('inicialBundle:Usuarios', 'usuario', 'WITH', 'perfil.usuario = usuario.id')
                ->innerJoin('inicialBundle:Passwords', 'password', 'WITH', 'perfil.id = password.perfil')
                ->innerJoin('inicialBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('perfil.nombreUsuario = :user')
                ->andwhere('password.password = :pass')
                ->andwhere('password.activo = true')
                ->setParameter('user', $username)
                ->setParameter('pass', $password)
                ->getQuery();

            $user = $query->getOneOrNullResult();

            if ($user){
                $session = $request ->getSession();
                $session -> set("email", $user['email']);
                $session -> set("perfil_activo", $user['perfil_activo']);
                $session -> set("pass_activo", $user['pass_activo']);

                if($session->get('perfil_activo')==1){

                    if($session->get('pass_activo')==1 ){
                        $session -> set("id", $user['id_perfil']);
                        $session -> set("autenticado", true);
                        $session -> set("nombre_usuario", $user['nombreUsuario']);
                        $session -> set("nombres", $user['nombres']);
                        $session -> set("tipo_usuario", $user['nombre_tipo_usuario']);
                        $session -> set("id_tipo_usuario", $user['id_tipo_usuario']);
                        return $this->render('inicialBundle:Default:index.html.twig');
                    }
                    else{
                        $this -> get('session') -> getFlashBag() -> add(
                            'warning', 'Clave Inactiva debe actualizar su clave');
                    }
                }
                else{
                        $this -> get('session') -> getFlashBag() -> add(
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

    public function lista_usuarioAction(Request $request)
    {
        //hacer consulta simple a la bbdd

        if($request->get('_route')=='inicial_lista_representante'){
            $query = $this->getDoctrine()->getRepository('inicialBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('inicialBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id=5')
                ->orderBy('usuario.id')
                ->getQuery();
            $elemento = 'Representantes';

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
            $elemento = 'Usuarios';
        }

        $datos = $query->getArrayResult();

        return $this->render('inicialBundle:Default:lista_usuario.html.twig', array('accion'=>'Listado de '.$elemento, 'datos'=>$datos));
    }
    public function detalle_usuarioAction($id, Request $request)
    {

        //hacer consulta simple a la bbdd

        $query = $this->getDoctrine()->getRepository('inicialBundle:Usuarios')
            ->createQueryBuilder('usuario')
            ->where('usuario.id = :id')
            ->andWhere('usuario.activo = true')
            ->setParameter('id', $id)
            ->getQuery();


        $datos = $query->getArrayResult();

        if (!$datos)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }

        return $this->render('inicialBundle:Default:detalle_usuario.html.twig', array('accion'=>'Detalle Usuario', 'datos'=>$datos));
    }
    public function crear_usuarioAction(Request $request)
    {
        $p = new Usuarios();
        if($request->get('_route')=='inicial_agregar_representante'){
            $formulario = $this->createForm(new UsuariosType('Crear Representante'), $p);
            $formulario -> remove('tipoUsuario');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('inicialBundle:TipoUsuario')
                ->find(5);
            $p->setTipoUsuario($tipo_usuario);
            $elemento = 'Representante';
        }
        else{
            $formulario = $this->createForm(new UsuariosType('Crear Usuario'), $p);
            $formulario -> remove('alumno');
            $formulario -> remove('principal');
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
        }

        return $this->render('inicialBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear '.$elemento));
    }

    public function borrar_usuarioAction($id, Request $request)
    {
        $usuario = $this->getDoctrine()
            ->getRepository('inicialBundle:Usuarios')
            ->find($id);
        if (!$usuario)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }
        $formulario = $this->createForm(new UsuariosTypeSimple('Borrar Usuario'), $usuario);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');
        $formulario -> remove('cedula');
        $formulario -> remove('nombres');
        $formulario -> remove('apellidos');
        $formulario -> remove('fechaNacimiento');
        $formulario -> remove('direccion');
        $formulario -> remove('sexo');
        $formulario -> remove('activo');
        $formulario -> remove('guardar_crear');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('inicialBundle:Usuarios')
            ->createQueryBuilder('usuario')
            ->where('usuario.id = :id')
            ->andWhere('usuario.activo = true')
            ->setParameter('id', $id)
            ->getQuery();

        $datos = $query->getArrayResult();

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $usuario->setActivo(false);
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'warning', 'Usuario borrado con éxito'
                );
                return $this->redirect($this->generateUrl('inicial_lista_usuario'));

            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = 'inicial_agregar_banco';
        return $this->render('inicialBundle:Default:borrar.html.twig', array('form'=>$formulario->createView(), 'datos'=>$datos, 'accion'=>'Borrar Usuario', 'atajo'=>$atajo));
    }

    public function lista_alumnoAction()
    {
        //hacer consulta simple a la bbdd

        $query = $this->getDoctrine()->getRepository('inicialBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno.id','alumno.cedula','alumno.cedulaEstudiantil', 'alumno.apellidos', 'alumno.nombres', 'alumno.fechaNacimiento', 'usuario.nombres as Nombre_Representante', 'usuario.apellidos as Apellido_Representante', 'usuario.id as usuario_id')
            ->leftJoin('alumno.usuario', 'usuario')
            ->where('usuario.activo = true')
            ->andwhere('alumno.activo = true')
            ->orderBy('alumno.id', 'DESC')
            ->getQuery();

        $datos = $query->getArrayResult();

        return $this->render('inicialBundle:Default:lista_alumno.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos));
    }

    public function detalle_alumnoAction($id, Request $request)
    {
        $query = $this->getDoctrine()->getRepository('inicialBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->where('alumno.id = :id')
            ->andWhere('alumno.activo = true')
            ->setParameter('id', $id)
            ->getQuery();

        $datos = $query->getArrayResult();

        if (!$datos)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }

        return $this->render('inicialBundle:Default:detalle_alumno.html.twig', array('accion'=>'Detalle Alumno', 'datos'=>$datos));
    }

    public function borrar_alumnoAction($id, Request $request)
    {
        $alumno = $this->getDoctrine()
            ->getRepository('inicialBundle:Alumnos')
            ->find($id);
        if (!$alumno)
        {
            throw $this -> createNotFoundException('no alumno con este id: '.$id);
        }
        $formulario = $this->createForm(new AlumnosTypeSimple('Borrar Estudiante'), $alumno);
        $formulario -> remove('usuario');
        $formulario -> remove('cedulaEstudiantil');
        $formulario -> remove('cedula');
        $formulario -> remove('nombres');
        $formulario -> remove('apellidos');
        $formulario -> remove('fechaNacimiento');
        $formulario -> remove('lugarNacimiento');
        $formulario -> remove('sexo');
        $formulario -> remove('activo');
        $formulario -> remove('guardar_crear');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('inicialBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->where('alumno.id = :id')
            ->andWhere('alumno.activo = true')
            ->setParameter('id', $id)
            ->getQuery();


        $datos = $query->getArrayResult();

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $alumno->setActivo(false);
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'warning', 'Alumno borrado con éxito'
                );

                return $this->redirect($this->generateUrl('inicial_lista_alumno'));

            }
        }
        $this->get('session')->getFlashBag()->add(
            'danger', 'Seguro que desea borrar este registro?'
        );
        $atajo = 'inicial_lista_alumno';
        return $this->render('inicialBundle:Default:borrar.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>'Borrar Estudiante', 'atajo'=>$atajo));
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


    public function lista_alumno_pdfAction()
    {

        //hacer consulta simple a la bbdd


        $query = $this->getDoctrine()->getRepository('inicialBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno.id','alumno.cedula','alumno.cedulaEstudiantil', 'alumno.apellidos', 'alumno.nombres', 'alumno.fechaNacimiento', 'usuario.nombres as Nombre_Representante', 'usuario.apellidos as Apellido_Representante', 'usuario.id as usuario_id')
            ->leftJoin('alumno.usuario', 'usuario')
            ->where('usuario.activo = true')
            ->andwhere('alumno.activo = true')
            ->orderBy('alumno.id', 'DESC')
            ->getQuery();

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
						<th colspan='5'>ESTUDIANTES REGISTRADOS</th>
					</tr>
					<tr>
						<th>ID</th>
						<th>NOMBRE</th>
						<th>APELIDO</th>
						<th>CEDULA</th>
						<th>REPRESENTANTE</th>
					</tr>";

        foreach($datos as $dato){
            $html.="<tr>
							<td>".$dato['id']."</td>
							<td>".$dato['nombres']."</td>
							<td>".$dato['apellidos']."</td>
							<td>".$dato['cedula']."</td>
							<td>".$dato['Nombre_Representante']." ".$dato['Apellido_Representante']."</td>
						</tr>";
        }


        $html.=	"</table>";

        $response = $mpdfService->generatePdfResponse($html);

        return $response;

    }

    public function solicitar_passAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $email = $request->get('email');

            $query = $this->getDoctrine()->getRepository('inicialBundle:PerfilUsuario')
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
                    ->getRepository('inicialBundle:PerfilUsuario')
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
                        ->getRepository('inicialBundle:PerfilUsuario')
                        ->find($datos[0]['idPerfil']['id']);

                    $p = new Passwords();
                    $formulario = $this->createForm(new PasswordsType(), $p);
                    //$formulario->setParent($perfil);
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

    public function crear_generico($request, $modelo, $formulario_base, $objeto, $accion, $url_redireccion, $url_editar, $url_borrar, $plantilla, $datos = null)
    {
        $p = new $modelo;
        $formulario = $this->createForm($formulario_base, $p);
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

    public function editar_generico($id, $request, $formulario_base, $objeto, $accion, $url_redireccion, $plantilla)
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
        return $this->editar_generico($id, $request, $form,'Curso', 'Editar Curso', 'inicial_agregar_curso', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'ConceptosFactura', 'Editar Concepto Factura', 'inicial_agregar_conceptos_factura', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'Bancos', 'Editar Banco', 'inicial_agregar_banco', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'Roles', 'Editar Rol', 'inicial_agregar_rol', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'PeriodoEscolar', 'Editar Periodo Escolar', 'inicial_agregar_periodo', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'Elementos', 'Editar Elemento del Sistema', 'inicial_agregar_elemento', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'Eventos', 'Editar Evento del Sistema', 'inicial_agregar_evento', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'Permisos', 'Editar Permisos del Sistema', 'inicial_agregar_permiso', 'mantenimiento', 'true');
    }
    public function borrar_permisoAction($id, Request $request){
        $form = New PermisosType('Borrar permiso del sistema');
        return $this->borrar_generico($id, $request, $form, 'Permisos', 'Borrar Permisos del Sistema', 'inicial_agregar_permiso', 'borrar', 'true');
    }
    public function crear_seccionAction(Request $request){
        $curso = New Seccion();
        $form = new SeccionType('Crear Seccion');
        return $this->crear_generico($request, $curso, $form, 'Seccion', 'Crear Seccion', 'inicial_agregar_seccion', 'inicial_editar_seccion', 'inicial_borrar_seccion', 'mantenimiento', 'true');
    }
    public function editar_seccionAction($id, Request $request){
        $form = new SeccionType('Editar Seccion');
        return $this->editar_generico($id, $request, $form, 'Seccion', 'Editar Seccion', 'inicial_agregar_seccion', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'TipoUsuario', 'Editar Tipo Usuario', 'inicial_agregar_tipo_usuario', 'mantenimiento', 'true');
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
        return $this->editar_generico($id, $request, $form, 'TipoContacto', 'Editar Tipo Contacto', 'inicial_agregar_tipo_contacto', 'mantenimiento', 'true');
    }
    public function borrar_tipo_contactoAction($id, Request $request){
        $form = new TipoContactoType('Borrar Tipo Contacto');
        return $this->borrar_generico($id, $request, $form, 'TipoContacto', 'Borrar Tipo Contacto', 'inicial_agregar_tipo_contacto', 'borrar', 'true');
    }
    public function crear_tipo_facturaAction(Request $request){
        $curso = New TipoFactura();
        $form = new TipoFacturaType('Crear Tipo Factura');
        return $this->crear_generico($request, $curso, $form, 'TipoFactura', 'Crear Tipo Factura', 'inicial_agregar_tipo_factura', 'inicial_editar_tipo_factura', 'inicial_borrar_tipo_factura', 'mantenimiento', 'true');
    }
    public function editar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Editar Tipo Factura');
        return $this->editar_generico($id, $request, $form, 'TipoFactura', 'Editar Tipo Factura', 'inicial_agregar_tipo_factura', 'mantenimiento', 'true');
    }
    public function borrar_tipo_facturaAction($id, Request $request){
        $form = new TipoFacturaType('Borrar Tipo Factura');
        return $this->borrar_generico($id, $request, $form, 'TipoFactura', 'Borrar Tipo Factura', 'inicial_agregar_tipo_factura', 'borrar', 'true');
    }
    public function crear_alumnoAction(Request $request){
        $modelo = New Alumnos();
        $form = new AlumnosTypeSimple('Crear Estudiante');
        return $this->crear_generico($request, $modelo, $form, 'Alumnos', 'Crear Estudiante', 'inicial_agregar_alumno', 'inicial_editar_tipo_factura', 'inicial_borrar_tipo_factura', 'crear_alumno_simple');
    }
    public function editar_alumnoAction($id, Request $request){
        $form = new AlumnosTypeSimple('Editar Estudiante');
        return $this->editar_generico($id, $request, $form, 'Alumnos', 'Editar Estudiante', 'inicial_agregar_alumno', 'crear_alumno_simple');
    }
    public function crear_alumno_usuarioAction(Request $request){
        $modelo = New Alumnos();
        $form = new AlumnosTypeUsuario('Crear Estudiante');
        return $this->crear_generico($request, $modelo, $form, 'Alumnos', 'Crear Estudiante', 'inicial_agregar_alumno_usuario', 'inicial_editar_tipo_factura', 'inicial_borrar_tipo_factura', 'crear_alumno');
    }
    public function editar_usuarioAction($id, Request $request){
        $form = new UsuariosTypeSimple('Editar Usuario');
        return $this->editar_generico($id, $request, $form, 'Usuarios', 'Editar Usuario', 'inicial_lista_usuario', 'crear_usuario');
    }
    public function crear_gradoAction(Request $request){
        $curso = New PeriodoEscolarCurso();
        $form = new PeriodoEscolarCursoType('Crear Grado');
        return $this->crear_generico($request, $curso, $form, 'PeriodoEscolarCurso', 'Crear Grado', 'inicial_agregar_grado', 'inicial_editar_grado', 'inicial_borrar_grado', 'mantenimiento', 'true');
    }
}
