<?php

namespace RosaMolas\alumnosBundle\Controller;

use RosaMolas\alumnosBundle\Entity\PeriodoEscolarAlumno;
use RosaMolas\alumnosBundle\Form\AlumnosTypeSimple;
use RosaMolas\alumnosBundle\Form\AlumnosTypeUsuario;
use RosaMolas\alumnosBundle\Form\PeriodoEscolarAlumnoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RosaMolas\alumnosBundle\Entity\Alumnos;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('alumnosBundle:Default:index.html.twig', array('name' => $name));
    }
    public function crear_alumnoAction(Request $request){
        $p = New Alumnos();
        $formulario = $this->createForm(new AlumnosTypeSimple('Crear Estudiante'), $p);
        $formulario-> handleRequest($request);

        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                $p->setActivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Estudiante creado con éxito'
                );
                if ($formulario->get('guardar')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
                if ($formulario->get('guardar_crear')->isClicked()) {
                    return $this->redirect($this->generateUrl('inicial_agregar_alumno'));
                }
            }
        }
        return $this->render('alumnosBundle:Default:crear_alumno_simple.html.twig', array('form'=>$formulario->createView(),
            'accion'=>'Crear Estudiante',));
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
        return $this->render('alumnosBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(),
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
        return $this->render('alumnosBundle:Default:'.$plantilla.'.html.twig', array('form'=>$formulario->createView(), 'accion'=>$accion));
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






    public function editar_alumnoAction($id, Request $request){
        $form = new AlumnosTypeSimple('Editar Estudiante');
        return $this->editar_generico($id, $request, $form, 'Alumnos', 'Editar Estudiante', 'inicial_homepage', 'crear_alumno_simple');
    }

    public function crear_alumno_usuarioAction(Request $request){
        $modelo = New Alumnos();
        $form = new AlumnosTypeUsuario('Crear Estudiante');
        return $this->crear_generico($request, $modelo, $form, 'Alumnos', 'Crear Estudiante', 'inicial_agregar_alumno_usuario', 'inicial_editar_tipo_factura', 'inicial_borrar_tipo_factura', 'crear_alumno');
    }

    public function crear_periodo_alumnoAction(Request $request){
        $modelo = New PeriodoEscolarAlumno();
        $form = new PeriodoEscolarAlumnoType('Crear Estudiante');
        return $this->crear_generico($request, $modelo, $form, 'Alumnos', 'Crear Estudiante', 'inicial_agregar_alumno_usuario', 'inicial_editar_tipo_factura', 'inicial_borrar_tipo_factura',  'mantenimiento', 'true');
    }


    public function lista_alumno_pdfAction()
    {

        //hacer consulta simple a la bbdd


        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
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


    public function lista_alumnoAction()
    {
        //hacer consulta simple a la bbdd

        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno.id','alumno.cedula','alumno.cedulaEstudiantil', 'alumno.apellidos', 'alumno.nombres', 'alumno.fechaNacimiento', 'usuario.nombres as Nombre_Representante', 'usuario.apellidos as Apellido_Representante', 'usuario.id as usuario_id')
            ->leftJoin('alumno.usuario', 'usuario')
            ->where('usuario.activo = true')
            ->andwhere('alumno.activo = true')
            ->orderBy('alumno.id', 'DESC')
            ->getQuery();

        $datos = $query->getArrayResult();

        return $this->render('alumnosBundle:Default:lista_alumno.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos));
    }

    public function detalle_alumnoAction($id, Request $request)
    {

        //hacer consulta simple a la bbdd

        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno', 'usuarios')
            ->innerJoin('alumno.usuario', 'usuarios')
            ->where('alumno.id = :id')
            ->andWhere('alumno.activo = true')
            ->setParameter('id', $id)
            ->getQuery();


        $datos = $query->getArrayResult();

        if (!$datos)
        {
            throw $this -> createNotFoundException('no usuario con este id: '.$id);
        }
        return $this->render('alumnosBundle:Default:detalle_alumno.html.twig', array('accion'=>'Detalle Estudiante', 'datos'=>$datos));
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

        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
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
        return $this->render('alumnosBundle:Default:borrar.html.twig', array('form'=>$formulario->createView(),
            'datos'=>$datos, 'accion'=>'Borrar Estudiante', 'atajo'=>$atajo));
    }



}
