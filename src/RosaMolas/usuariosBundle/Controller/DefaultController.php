<?php

namespace RosaMolas\usuariosBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\usuariosBundle\Form\UsuariosType;
use RosaMolas\usuariosBundle\Form\UsuariosTypeSimple;
use RosaMolas\usuariosBundle\Entity\Passwords;
use RosaMolas\usuariosBundle\Entity\RecuperarPasswordTmp;
use RosaMolas\usuariosBundle\Form\PasswordsType;


class DefaultController extends Controller
{
    public function lista_usuarioAction(Request $request)
    {
        //hacer consulta simple a la bbdd

        if($request->get('_route')=='inicial_lista_representante'){
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id=5')
                ->orderBy('usuario.id', 'DESC')
                ->getQuery();
            $elemento = 'Representantes';

        }
        else{
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario.cedula, usuario.apellidos, usuario.nombres, usuario.fechaNacimiento, usuario.direccion, usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('usuario.activo = true')
                ->andWhere('tipo_usuario.id!=5')
                ->orderBy('usuario.id')
                ->getQuery();
            $elemento = 'Usuarios';
        }

        $datos = $query->getArrayResult();

        return $this->render('usuariosBundle:Default:lista_usuario.html.twig', array('accion'=>$elemento, 'datos'=>$datos));
    }
    public function detalle_usuarioAction($id, $tipo, Request $request)
    {
        //hacer consulta simple a la bbdd

        if(strtolower($tipo) == 'representantes'){
            $url_redirect = 'inicial_lista_representante';
            $plantilla = 'detalle_representante';
            $accion = 'Detalle Representante';
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario', 'alumnos', 'contacto', 'tipo_contacto')
                ->innerJoin('usuario.alumno', 'alumnos')
                ->innerJoin('usuario.representanteContacto', 'contacto')
                ->innerJoin('contacto.tipoContacto', 'tipo_contacto','WITH', 'contacto.tipoContacto = tipo_contacto.id')
                ->where('usuario.id = :id')
                ->andWhere('usuario.activo = true')
                ->setParameter('id', $id)
                ->getQuery();

        }
        else{
            $plantilla = 'detalle_usuario';
            $url_redirect = 'inicial_lista_usuario';
            $accion = 'Detalle Usuario';
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->where('usuario.id = :id')
                ->andWhere('usuario.activo = true')
                ->setParameter('id', $id)
                ->getQuery();
        }

        $datos = $query->getArrayResult();

        if (!$datos)
        {
            $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                ->createQueryBuilder('usuario')
                ->select('usuario', 'contacto','tipo_contacto')
                ->innerJoin('usuario.representanteContacto', 'contacto')
                ->innerJoin('contacto.tipoContacto', 'tipo_contacto','WITH', 'contacto.tipoContacto = tipo_contacto.id')
                ->where('usuario.id = :id')
                ->andWhere('usuario.activo = true')
                ->setParameter('id', $id)
                ->getQuery();
            $datos = $query->getArrayResult();

            if(!$datos) {
                $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
                    ->createQueryBuilder('usuario')
                    ->select('usuario')
                    ->where('usuario.id = :id')
                    ->andWhere('usuario.activo = true')
                    ->setParameter('id', $id)
                    ->getQuery();
                $datos = $query->getArrayResult();
                if(!$datos) {

                    $this->get('session')->getFlashBag()->add(
                        'warning', 'No hay registros con este identificador '. $id
                    );
                    return $this->redirect($this->generateUrl($url_redirect));
                }
            }
        }
        return $this->render('usuariosBundle:Default:'.$plantilla.'.html.twig', array('accion'=>$accion, 'datos'=>$datos));
    }
    public function crear_usuarioAction(Request $request)
    {
        $p = new Usuarios();
        if($request->get('_route')=='inicial_agregar_representante'){
            $formulario = $this->createForm(new UsuariosType('Crear Representante'), $p);
            $formulario -> remove('tipoUsuario');
            $formulario -> remove('principal');
            $tipo_usuario = $this->getDoctrine()
                ->getRepository('usuariosBundle:TipoUsuario')
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
            $formulario -> remove('email');
            $elemento = 'Usuario';
        }
        $formulario -> remove('activo');
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                print_r($elemento);
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
        return $this->render('usuariosBundle:Default:crear_usuario.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear '.$elemento));
    }

    public function borrar_usuarioAction($id, Request $request)
    {
        $usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:Usuarios')
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
        $formulario -> remove('email');
        $formulario -> remove('sexo');
        $formulario -> remove('activo');
        $formulario -> remove('guardar_crear');
        $formulario-> handleRequest($request);

        $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
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
        return $this->render('inicialBundle:Default:borrar.html.twig', array('form'=>$formulario->createView(), 'datos'=>$datos, 'accion'=>'Borrar Usuario'));
    }
    public function editar_representanteAction($id, Request $request){
        $form = new UsuariosTypeSimple('Editar Representante');
        $objeto = 'Usuarios';
        $clase = 'usuariosBundle:Usuarios';
        $titulo = 'Representante';
        $url_redireccion = 'inicial_lista_representante';
        $plantilla = 'usuariosBundle:Default:crear_usuario';
        $remover =['tipoUsuario', 'principal'];
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
    }
    public function editar_usuarioAction($id, Request $request){
        $form = new UsuariosTypeSimple('Editar Usuario');
        $objeto = 'Usuarios';
        $clase = 'usuariosBundle:Usuarios';
        $titulo = 'Usuario';
        $url_redireccion = 'inicial_lista_usuario';
        $plantilla = 'usuariosBundle:Default:crear_usuario';
        $remover = null;
        $remover =['representanteContacto', 'principal', 'email'];
        return $this->forward('funciones_genericas:editar_generico', array('id'=>$id, 'request'=>$request, 'formulario_base'=>$form, 'objeto'=>$objeto, 'clase'=>$clase, 'titulo' => $titulo, 'url_redireccion'=> $url_redireccion, 'plantilla'=>$plantilla, 'remover' => $remover));
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
        return $this->render('usuariosBundle:Default:recuperar_pass.html.twig', array('accion'=>'Solicitud Recuperar Contraseña'));
    }
    public function recuperar_passAction($token, Request $request){
        if($token && preg_match('/^[0-9A-F]{40}$/i', $token)) {

            $query = $this->getDoctrine()->getRepository('usuariosBundle:RecuperarPasswordTmp')
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
                return $this->render('usuariosBundle:Default:index.html.twig');
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
                            $test_desact = $desact_pass->getRepository('usuariosBundle:Passwords')->findOneBy(array('perfil'=>$perfil, 'activo'=>true));
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
                            $borrar_passtmp_query = $borrar_passtmp->getRepository('usuariosBundle:RecuperarPasswordTmp')->find($datos[0]['id']);
                            $borrar_passtmp->remove($borrar_passtmp_query);
                            $borrar_passtmp->flush();

                            return $this->redirect($this->generateUrl('inicial_homepage'));
                        }
                    }
                    return $this->render('usuariosBundle:Default:recuperar_pass.html.twig', array('accion'=>'Recuperar Contraseña', 'form'=>$formulario->createView()));
                }
                else{
                    $this->get('session')->getFlashBag()->add(
                        'danger', 'enlace de excedio el tiempo para ser usado'
                    );
                    return $this->render('usuariosBundle:Default:index.html.twig');
                }
            }
        }
        return $this->render('usuariosBundle:Default:recuperar_pass.html.twig', array('accion'=>'Solicitud Recuperar Contraseña'));
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
}
