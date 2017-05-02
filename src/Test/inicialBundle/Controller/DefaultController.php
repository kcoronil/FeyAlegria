<?php
namespace Test\inicialBundle\Controller;
use RosaMolas\facturacionBundle\Form\ConceptosFacturaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\genericoBundle\Entity\Bancos;
use RosaMolas\facturacionBundle\Entity\ConceptosFactura;
use RosaMolas\genericoBundle\Entity\Elementos;
use RosaMolas\genericoBundle\Entity\Eventos;
use Test\inicialBundle\Entity\CursoSeccion;
use Test\inicialBundle\Entity\Etapa;
use RosaMolas\usuariosBundle\Entity\Permisos;
use Test\inicialBundle\Entity\Seccion;
use RosaMolas\usuariosBundle\Entity\TipoContacto;
use RosaMolas\usuariosBundle\Entity\TipoUsuario;
use RosaMolas\genericoBundle\Form\BancosType;
use Test\inicialBundle\Entity\PeriodoEscolar;
use Test\inicialBundle\Form\CursoSeccionType;
use Test\inicialBundle\Form\ElementosType;
use Test\inicialBundle\Form\EtapaType;
use Test\inicialBundle\Form\EventosType;
use Test\inicialBundle\Form\PeriodoEscolarType;
use RosaMolas\usuariosBundle\Form\PermisosType;
use Test\inicialBundle\Form\SeccionType;
use RosaMolas\usuariosBundle\Form\TipoContactoType;
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

//        $alumnosInscripcion = $this->getDoctrine()
//            ->getRepository('alumnosBundle:Alumnos')
//            ->findAll();
//        $cedula_alumno = '';
//        $rep_ppal = 0;
//        $representante_ppal = '';
//        foreach($alumnosInscripcion as $alumno){
//            foreach($alumno->getAlumnoRepresentanteDatos() as $alumno_rep_datos){
//                if($alumno_rep_datos->getPrincipal()==true){
////                    $rep_ppal=  $rep_ppal + 1;
//                    if($cedula_alumno == false){
//                        $representante_ppal = $this->getDoctrine()
//                            ->getRepository('usuariosBundle:Usuarios')
//                            ->find($alumno_rep_datos->getRepresentante()->getId());
//                    }
//                }
//            }
//            if($cedula_alumno == false){
//                if($representante_ppal->getAlumnoRepresentanteDatos()){
////                    if(!$alumno->getCedulaEstudiantil()) {
//                        $cant_alumnos_anio = 0;
//                        $ced_escolar = $alumno->getFechaNacimiento()->format('y') . $representante_ppal->getCedula();
////                        foreach ($representante_ppal->getAlumnoRepresentanteDatos() as $alum_rep_datos) {
//
//                            $alumnoced = $this->getDoctrine()
//                                ->getRepository('alumnosBundle:Alumnos')
//                                ->findOneBy(array('cedulaEstudiantil' => $ced_escolar));
//                            var_dump($ced_escolar);
//                            var_dump(isset($alumnoced));
//                            var_dump(empty($alumnoced));
////                            var_dump($alumnoced);
//                            if(!empty($alumnoced)){
////                                var_dump($representante_ppal->getAlumnoRepresentanteDatos());
//                                var_dump('cant alum '.count($representante_ppal->getAlumnoRepresentanteDatos()));
//                                while($cant_alumnos_anio <= count($representante_ppal->getAlumnoRepresentanteDatos())) {
//                                    var_dump('true');
//                                    var_dump($cant_alumnos_anio);
//                                    $cant_alumnos_anio = $cant_alumnos_anio +1;
//                                    $ced_escolar = $cant_alumnos_anio . $alumno->getFechaNacimiento()->format('y') . $representante_ppal->getCedula();
//                                    $alumnoced = $this->getDoctrine()
//                                        ->getRepository('alumnosBundle:Alumnos')
//                                        ->findOneBy(array('cedulaEstudiantil' => $ced_escolar));
//                                    if(empty($alumnoced)){
//                                        break;
//                                    }
//                                }
//                            }
////
////                            if ($alum_rep_datos->getAlumno()->getId() != $alumno->getId() and $alum_rep_datos->getAlumno()->getFechaNacimiento() == $alumno->getFechaNacimiento()) {
////                                $cant_alumnos_anio = $cant_alumnos_anio + 1;
////
////                            }
////                        }
////
////                        var_dump('id '.$alumno->getId());
////                        var_dump('ced esc '.$ced_escolar);
//                        $alumno->setCedulaEstudiantil($ced_escolar);
//
//
////                    }
//                }
////                else{
////                    $alumno->setCedulaEstudiantil($alumno->getFechaNacimiento()->format('y').$representante_ppal->getCedula());
////                }
//            }
//        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
//        print_r($session->get("autenticado"));
//        print_r('<br>');
        //$test = $this->container->get('security.context')->getToken()->getUser();
        //print_r($this->container->get('security.context')->getToken()->getUser());
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
                            $session->set("usuario_id", $user[0]['id']);
                            $session->set("autenticado", true);
                            $session->set("nombre_usuario", $user[0]['nombreUsuario']);
                            $session->set("nombres", $user[1]['primerNombre'].' '.$user[1]['primerApellido']);
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

        if($this->getUser() and !$session->get('autenticado')){
            $query = $this->getDoctrine()->getRepository('usuariosBundle:PerfilUsuario')
                ->createQueryBuilder('perfil')
                ->select('perfil', 'usuario', 'tipo_usuario')
                ->innerJoin('usuariosBundle:Usuarios', 'usuario', 'WITH', 'perfil.usuario = usuario.id')
                ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
                ->where('perfil.nombreUsuario = :user')
                ->setParameter('user', $this->getUser()->getUsername())
                ->getQuery();
            $user = $query->getArrayResult();
            $session->set("email", $user[0]['email']);
            $session->set("perfil_activo", $user[0]['activo']);
            $session->set("usuario_id", $user[0]['id']);
            $session->set("autenticado", true);
            $session->set("nombre_usuario", $user[0]['nombreUsuario']);
            $session->set("nombres", $user[1]['primerNombre'].' '.$user[1]['primerApellido']);
            $session->set("tipo_usuario", $user[2]['nombre']);
            $session->set("id_tipo_usuario", $user[2]['id']);

        }
        return $this->render('inicialBundle:Default:index.html.twig');
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
    public function crear_cursoAction(Request $request){
        $modelo = New Curso();
        $form = New CursoType('Crear Curso');
        $objeto = 'Curso';
        $clase = 'inicialBundle:Curso';
        $titulo = 'Curso';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_cursoAction($id, Request $request){
        $form = New CursoType('Editar Curso');
        $clase = 'inicialBundle:Curso';
        $titulo = 'Curso';
        $url_redireccion = 'inicial_agregar_curso';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_cursoAction($id, Request $request){
        $form = New CursoType('Borrar Curso');
        $objeto = 'Curso';
        $clase = 'inicialBundle:Curso';
        $titulo = 'Curso';
        $remover = null;
        $url_redireccion = 'inicial_agregar_curso';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_conceptos_facturaAction(Request $request){
        $modelo = New ConceptosFactura();
        $form = new ConceptosFacturaType('Crear Concepto Factura');
        $objeto = 'ConceptosFactura';
        $clase = 'facturacionBundle:ConceptosFactura';
        $titulo = 'Conceptos de Factura';
        $url_redireccion = 'inicial_agregar_conceptos_factura';
        $url_editar = 'inicial_editar_conceptos_factura';
        $url_borrar = 'inicial_borrar_conceptos_factura';
        $datos = 'true';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_conceptos_facturaAction($id, Request $request){
        $form = New ConceptosFacturaType('Editar Concepto Factura');
        $clase = 'facturacionBundle:ConceptosFactura';
        $titulo = 'Conceptos de Factura';
        $url_redireccion = 'inicial_agregar_conceptos_factura';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_conceptos_facturaAction($id, Request $request){
        $form = New ConceptosFacturaType('Borrar Concepto Factura');
        $objeto = 'ConceptosFactura';
        $clase = 'facturacionBundle:ConceptosFactura';
        $titulo = 'Conceptos de Factura';
        $remover = ['tipoFactura'];
        $url_redireccion = 'inicial_agregar_conceptos_factura';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_bancoAction(Request $request){
        $modelo = New Bancos();
        $form = new BancosType('Crear Banco');
        $objeto = 'Bancos';
        $clase = 'genericoBundle:Bancos';
        $titulo = 'Bancos';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_bancoAction($id, Request $request){
        $form = New BancosType('Editar Banco');
        $clase = 'genericoBundle:Bancos';
        $titulo = 'Banco';
        $url_redireccion = 'inicial_agregar_bancos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_bancoAction($id, Request $request){
        $form = New BancosType('Borrar Banco');
        $objeto = 'Bancos';
        $clase = 'genericoBundle:Bancos';
        $titulo = 'Banco';
        $remover = null;
        $url_redireccion = 'inicial_agregar_bancos';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $titulo, $objeto, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_rolAction(Request $request){
        $modelo = New Roles();
        $form = new RolesType('Crear Rol');
        $objeto = 'Roles';
        $clase = 'usuariosBundle:Roles';
        $titulo = 'Rol';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_rolAction($id, Request $request){
        $form = New RolesType('Editar Rol');
        $clase = 'usuariosBundle:Roles';
        $titulo = 'Rol';
        $url_redireccion = 'inicial_agregar_roles';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_rolAction($id, Request $request){
        $form = New RolesType('Borrar Rol');
        $objeto = 'Roles';
        $clase = 'usuariosBundle:Roles';
        $titulo = 'Rol';
        $remover = null;
        $url_redireccion = 'inicial_agregar_roles';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
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
        $datos = 'true';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_periodoAction($id, Request $request){
        $form = New PeriodoEscolarType('Editar Periodo Escolar');
        $clase = 'inicialBundle:PeriodoEscolar';
        $titulo = 'Periodo Escolar';
        $url_redireccion = 'inicial_agregar_periodo';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_periodoAction($id, Request $request){
        $form = New PeriodoEscolarType('Borrar Periodo Escolar');
        $objeto = 'PeriodoEscolar';
        $clase = 'inicialBundle:PeriodoEscolar';
        $titulo = 'Periodo Escolar';
        $remover = null;
        $url_redireccion = 'inicial_agregar_periodo';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_elementoAction(Request $request){
        $modelo = New Elementos();
        $form = new ElementosType('Crear elemento del sistema');
        $objeto = 'Elementos';
        $clase = 'genericoBundle:Elementos';
        $titulo = 'Elemento';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_elementoAction($id, Request $request){
        $form = New ElementosType('Editar elemento del sistema');
        $clase = 'genericoBundle:Elementos';
        $titulo = 'Elemento del Sistema';
        $url_redireccion = 'inicial_agregar_elementos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_elementoAction($id, Request $request){
        $form = New ElementosType('Borrar elemento del sistema');
        $objeto = 'Elementos';
        $clase = 'genericoBundle:Elementos';
        $titulo = 'Elemento del Sistema';
        $remover = null;
        $url_redireccion = 'inicial_agregar_elementos';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_eventoAction(Request $request){
        $modelo = New Eventos();
        $form = new EventosType('Crear evento del sistema');
        $objeto = 'Eventos';
        $clase = 'genericoBundle:Eventos';
        $titulo = 'Evento del Sistema';
        $datos = 'true';
        $remover = '';
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_eventoAction($id, Request $request){
        $form = New EventosType('Editar evento del sistema');
        $clase = 'genericoBundle:Eventos';
        $titulo = 'Evento del Sistema';
        $url_redireccion = 'inicial_agregar_eventos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_eventoAction($id, Request $request){
        $form = New EventosType('Borrar evento del sistema');
        $objeto = 'Eventos';
        $clase = 'genericoBundle:Eventos';
        $titulo = 'Evento del Sistema';
        $remover = null;
        $url_redireccion = 'inicial_agregar_eventos';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_permisoAction(Request $request){
        $modelo = New Permisos();
        $form = new PermisosType('Crear permiso del sistema');
        $objeto = 'Permisos';
        $clase = 'usuariosBundle:Permisos';
        $titulo = 'Permisos del Sistema';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_permisoAction($id, Request $request){
        $form = New PermisosType('Editar permiso del sistema');
        $clase = 'usuariosBundle:Permisos';
        $titulo = 'Permisos del Sistema';
        $url_redireccion = 'inicial_agregar_permisos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_permisoAction($id, Request $request){
        $form = New PermisosType('Borrar permiso del sistema');
        $objeto = 'Permisos';
        $clase = 'usuariosBundle:Permisos';
        $titulo = 'Permisos del Sistema';
        $remover = null;
        $url_redireccion = 'inicial_agregar_permisos';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_seccionAction(Request $request){
        $modelo = New Seccion();
        $form = new SeccionType('Crear Sección');
        $objeto = 'Seccion';
        $clase = 'inicialBundle:Seccion';
        $titulo = 'Sección';
        $datos = 'true';
        $remover = null;
        $url_redireccion = null;
        $url_editar = null;
        $url_borrar = null;
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_seccionAction($id, Request $request){
        $form = new SeccionType('Editar Seccion');
        $clase = 'inicialBundle:Seccion';
        $titulo = 'Sección';
        $url_redireccion = 'inicial_agregar_seccion';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_seccionAction($id, Request $request){
        $form = new SeccionType('Borrar Seccion');
        $objeto = 'Seccion';
        $clase = 'inicialBundle:Seccion';
        $titulo = 'Seccion';
        $remover = null;
        $url_redireccion = 'inicial_agregar_seccion';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_tipo_usuarioAction(Request $request){
        $modelo = New TipoUsuario();
        $form = new TipoUsuarioType('Crear Tipo Usuario');
        $objeto = 'TipoUsuario';
        $clase = 'usuariosBundle:TipoUsuario';
        $titulo = 'Tipo Usuario';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_usuario';
        $url_editar = 'inicial_editar_tipo_usuario';
        $url_borrar = 'inicial_borrar_tipo_usuario';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_tipo_usuarioAction($id, Request $request){
        $form = new TipoUsuarioType('Editar Tipo Usuario');
        $clase = 'usuariosBundle:TipoUsuario';
        $titulo = 'Tipo de Usuario';
        $url_redireccion = 'inicial_agregar_tipo_usuario';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_tipo_usuarioAction($id, Request $request){
        $form = new TipoUsuarioType('Borrar Tipo Usuario');
        $objeto = 'TipoUsuario';
        $clase = 'usuariosBundle:TipoUsuario';
        $titulo = 'Tipo de Usuario';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_usuario';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_tipo_contactoAction(Request $request){
        $modelo = New TipoContacto();
        $form = new TipoContactoType('Crear Tipo Contacto');
        $objeto = 'TipoContacto';
        $clase = 'usuariosBundle:TipoContacto';
        $titulo = 'Tipo de Contacto';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_contacto';
        $url_editar = 'inicial_editar_tipo_contacto';
        $url_borrar = 'inicial_borrar_tipo_contacto';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_tipo_contactoAction($id, Request $request){
        $form = new TipoContactoType('Editar Tipo Contacto');
        $clase = 'usuariosBundle:TipoContacto';
        $titulo = 'Tipo de Contacto';
        $url_redireccion = 'inicial_agregar_tipo_contacto';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_tipo_contactoAction($id, Request $request){
        $form = new TipoContactoType('Borrar Tipo Contacto');
        $objeto = 'TipoContacto';
        $clase = 'usuariosBundle:TipoContacto';
        $titulo = 'Tipo de Contacto';
        $remover = null;
        $url_redireccion = 'inicial_agregar_tipo_contacto';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_etapaAction(Request $request){
        $modelo = New Etapa();
        $form = new EtapaType('Crear Etapa');
        $objeto = 'EtapaType';
        $clase = 'inicialBundle:Etapa';
        $titulo = 'Etapa';
        $datos = 'true';
        $remover = null;
        $url_redireccion = 'inicial_agregar_etapa';
        $url_editar = 'inicial_editar_tipo_contacto';
        $url_borrar = 'inicial_borrar_tipo_contacto';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_etapaAction($id, Request $request){
        $form = new EtapaType('Editar Etapa');
        $clase = 'inicialBundle:Etapa';
        $titulo = 'Etapa';
        $url_redireccion = 'inicial_agregar_etapa';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_etapaAction($id, Request $request){
        $form = new EtapaType('Borrar Tipo Contacto');
        $objeto = 'Etapa';
        $clase = 'inicialBundle:Etapa';
        $titulo = 'Etapa';
        $remover = null;
        $url_redireccion = 'inicial_agregar_etapa';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }
    public function crear_gradoAction(Request $request){
        $p = New CursoSeccion();
        $formulario = $this->createForm(new CursoSeccionType('Crear Grado'), $p);
        $formulario-> handleRequest($request);
        $query = $this->getDoctrine()->getRepository('inicialBundle:CursoSeccion')
            ->createQueryBuilder('grado')
            ->select('grado', 'seccion_tbl.nombre as seccion', 'curso_tbl.nombre as curso', 'etapas_tbl.nombre as etapa')
            ->where('grado.activo = true')
            ->innerJoin('inicialBundle:Seccion', 'seccion_tbl', 'WITH', 'grado.seccion = seccion_tbl.id')
            ->innerJoin('inicialBundle:Curso', 'curso_tbl', 'WITH', 'grado.curso = curso_tbl.id')
            ->innerJoin('inicialBundle:Etapa', 'etapas_tbl', 'WITH', 'grado.etapa = etapas_tbl.id')
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
            ->getRepository('inicialBundle:CursoSeccion')
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe grado con este id: '.$id);
        }
        $formulario = $this->createForm(new CursoSeccionType('Editar Grado'), $p);
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
            ->getRepository('inicialBundle:CursoSeccion')
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe concepto de Factura con este id: '.$id);
        }
        $formulario = $this->createForm(new CursoSeccionType('Borrar Grado'), $p);
        $formulario -> remove('seccion');
        $formulario -> remove('curso');
        $formulario -> remove('etapa');
        $formulario-> handleRequest($request);
        $query = $this->getDoctrine()->getRepository('inicialBundle:CursoSeccion')
            ->createQueryBuilder('grado')
            ->select('grado', 'seccion_tbl.nombre as seccion', 'curso_tbl.nombre as curso', 'etapas_tbl.nombre as etapa')
            ->where('grado.id = :id')
            ->andwhere('grado.activo = true')
            ->innerJoin('inicialBundle:Seccion', 'seccion_tbl', 'WITH', 'grado.seccion = seccion_tbl.id')
            ->innerJoin('inicialBundle:Curso', 'curso_tbl', 'WITH', 'grado.curso = curso_tbl.id')
            ->innerJoin('inicialBundle:Etapa', 'etapas_tbl', 'WITH', 'grado.etapa = etapas_tbl.id')
            ->setParameter('id', $id)
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
    public function estudianteAction(Request $request){
        return $this->render('inicialBundle:Default:estudiante.html.twig');

    }
    public function representanteAction(Request $request){
        return $this->render('inicialBundle:Default:representante.html.twig');

    }
    public function administradorAction(Request $request){
        return $this->render('inicialBundle:Default:administrador.html.twig');

    }
    public function config_usuariosAction(Request $request){
        return $this->render('inicialBundle:Default:config_usuario.html.twig');

    }
    public function config_facturacionAction(Request $request){
        return $this->render('inicialBundle:Default:facturacion.html.twig');

    }
    public function config_sistemaAction(Request $request){
        return $this->render('inicialBundle:Default:config_sistema.html.twig');

    }
    public function config_periodosAction(Request $request){
        return $this->render('inicialBundle:Default:periodos_escolares.html.twig');

    }
}