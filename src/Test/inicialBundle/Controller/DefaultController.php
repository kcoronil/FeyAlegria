<?php

namespace Test\inicialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Test\inicialBundle\Entity\PeriodoEscolar;
use Test\inicialBundle\Entity\RecuperarPasswordTmp;
use Test\inicialBundle\Entity\Usuarios;
use Test\inicialBundle\Form\AlumnosTypeUsuario;
use Test\inicialBundle\Form\PeriodoEscolarType;
use Test\inicialBundle\Form\UsuariosType;
use Test\inicialBundle\Entity\Roles;
use Test\inicialBundle\Form\RolesType;
use Test\inicialBundle\Entity\PerfilUsuario;
use Test\inicialBundle\Form\PerfilUsuarioType;
use Test\inicialBundle\Entity\Alumnos;
use Test\inicialBundle\Form\AlumnosTypeSimple;
use Test\inicialBundle\Entity\Passwords;
use Test\inicialBundle\Form\PasswordType;
use Test\inicialBundle\Form\PasswordsType;
use Test\inicialBundle\Form\RecuperarPasswordTmpType;



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
        $formulario = $this->createForm(new UsuariosType(), $p);
        if($request->get('_route')=='inicial_agregar_representante'){
            $formulario -> remove('tipoUsuario');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('inicialBundle:TipoUsuario')
                ->find(5);
            $p->setTipoUsuario($tipo_usuario);
            $elemento = 'Representante';

        }
        else{
            $formulario -> remove('principal');
            $elemento = 'Usuario';
        }
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                /*foreach($p->getAlumno() as $alumno){
                    $alumno->addAlumno($a);
                    $em->getEntityManager()->persist($alumno);}*/
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

    public function editar_usuarioAction($id, Request $request)
    {
        $usuario = $this->getDoctrine()
            ->getRepository('inicialBundle:Usuarios')
            ->find($id);
        if (!$usuario)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }
        $formulario = $this->createForm(new UsuariosType(), $usuario);
        $formulario -> remove('guardar_crear');
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'Usuario modificado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_usuario'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Modificar Usuario'));
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
        $formulario = $this->createForm(new UsuariosType(), $usuario);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');
        $formulario -> remove('cedula');
        $formulario -> remove('nombres');
        $formulario -> remove('apellidos');
        $formulario -> remove('fechaNacimiento');
        $formulario -> remove('direccion');
        $formulario -> remove('sexo');
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

        return $this->render('inicialBundle:Default:detalle_usuario.html.twig', array('form'=>$formulario->createView(), 'datos'=>$datos, 'accion'=>'Borrar Usuario'));
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

        //hacer consulta simple a la bbdd

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

    public function crear_alumnoAction(Request $request)
    {
        $p = new Alumnos();
        $formulario = $this->createForm(new AlumnosTypeSimple(),$p);
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Alumno Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_alumno'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_alumno_simple.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante'));
    }

    public function crear_alumno_usuarioAction(Request $request)
    {
        $p = new Alumnos();
        $formulario = $this->createForm(new AlumnosTypeUsuario(), $p);
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                /*foreach($p->getAlumno() as $alumno){
                    $alumno->addAlumno($a);
                    $em->getEntityManager()->persist($alumno);}*/
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'Alumno Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_alumno_usuario'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_alumno.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Alumno'));
    }

    public function editar_alumnoAction($id, Request $request)
    {

        $alumno = $this->getDoctrine()
            ->getRepository('inicialBundle:Alumnos')
            ->find($id);
        if (!$alumno)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }
        $formulario = $this->createForm(new AlumnosTypeSimple(), $alumno);
        $formulario -> remove('guardar_crear');
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Alumno Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_alumno'));
                }
            }
        }
        return $this->render('inicialBundle:Default:crear_alumno_simple.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Editar Estudiante'));
    }


    public function crear_rolAction(Request $request)
    {
        $p = new Roles();
        $formulario = $this->createForm(new RolesType(), $p);
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('inicialBundle:Roles')
            ->createQueryBuilder('roles')
            ->where('roles.activo = true')
            ->getQuery();


        $datos = $query->getArrayResult();


        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'Rol Creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_rol'));
                }
            }
        }
        return $this->render('inicialBundle:Default:mantenimiento.html.twig', array('form'=>$formulario->createView(), 'datos'=>$datos, 'accion'=>'Crear rol'));
    }

    public function crear_perfilAction(Request $request)
    {
        $p = new PerfilUsuario();
        $formulario = $this->createForm(new PerfilUsuarioType(),$p);
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

    public function crear_periodoAction(Request $request)
    {

        $query = $this->getDoctrine()->getRepository('inicialBundle:PeriodoEscolar')
            ->createQueryBuilder('periodoescolar')
            ->select('periodoescolar')
            ->orderBy('periodoescolar.id')
            ->getQuery();

        $datos = $query->getArrayResult();


        //instancia de la clase (Entity/PeriodoEscolar)
        $p = new PeriodoEscolar();

        //formulario en blanco de la instancia
        $formulario = $this->createForm(new PeriodoEscolarType(), $p);
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();

                //mensaje de registro
                $this->get('session')->getFlashBag()->add(
                    'success', 'Periodo Creado con éxito'
                );
                //solo realiza el guardar
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }

                //redirecciona de nuevo a al formulario
                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_periodo'));
                }
            }
        }
        //aquii se busca en Resources/views/
        return $this->render('inicialBundle:Default:mantenimiento.html.twig', array('form'=>$formulario->createView(), 'datos'=>$datos,'accion'=>'Crear Periodo'));

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
}
