<?php

namespace RosaMolas\genericoBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use RosaMolas\alumnosBundle\Entity\AlumnoRepresentanteDatos;
use RosaMolas\alumnosBundle\Entity\Alumnos;
use RosaMolas\alumnosBundle\Entity\PeriodoEscolarCursoAlumno;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggRepsDatos;
use RosaMolas\alumnosBundle\Form\AlumnosTypeInscripcion;
use RosaMolas\facturacionBundle\Entity\DetalleFactura;
use RosaMolas\alumnosBundle\Form\AlumnosTypeAggRep;
use RosaMolas\facturacionBundle\Entity\Factura;
use RosaMolas\facturacionBundle\Entity\MontosAlumnos;
use RosaMolas\facturacionBundle\Entity\TipoFactura;
use RosaMolas\facturacionBundle\Form\TipoFacturaType;
use RosaMolas\genericoBundle\Entity\Inscripcion;
use RosaMolas\genericoBundle\Entity\Pagos;
use RosaMolas\genericoBundle\Entity\Parentescos;
use RosaMolas\genericoBundle\Entity\PeriodoEscolarFinalizado;
use RosaMolas\genericoBundle\Form\InscripcionType;
use RosaMolas\genericoBundle\Form\PagosType;
use RosaMolas\genericoBundle\Form\ParentescosType;
use RosaMolas\usuariosBundle\Entity\Usuarios;
use RosaMolas\usuariosBundle\Form\UsuariosTypeInscripcion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use RosaMolas\genericoBundle\Service\pdfService;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->createQueryBuilder('periodo_alumno')
            ->select('periodo_alumno', 'alumnos')
            ->Join('periodo_alumno.alumno', 'alumnos')
            ->Join('alumnos.representante', 'representantes')
            ->Join('representantes.representanteContacto', 'contactos')
            ->where('periodo_alumno.cursoSeccion = :id')
            ->andwhere('periodo_alumno.activo = true')
            ->andwhere('alumnos.activo = true')
            ->andwhere('representantes.activo = true')
            ->orderBy('alumnos.id', 'DESC')
//            ->setParameter('id',$id)
            ->getQuery();

        $datos = $query->getResult();


        $fecha_actual = new \DateTime("now");
        $html = $this->renderView('genericoBundle:Default:index.html.twig', array('accion'=>'Listado de Alumnos', 'fecha'=>$fecha_actual, 'datos' => $datos));
        $mpdfService = $this->get('tfox.mpdfport');
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array('Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="file.pdf"'));
    }

    public function listado_alumnos_contactosAction(Request $request)
    {
        $periodo_activo = $this->getDoctrine()
            ->getRepository('inicialBundle:PeriodoEscolar')
            ->findOneBy(array('activo'=>true));
        $directoryPath = $this->container->getParameter('kernel.root_dir');
        $title = array(iconv('utf-8', 'windows-1252', 'U.E. Col Maria Rosa Molas - Fé y Alegría'),
        iconv('utf-8', 'windows-1252', 'Teléfono: 0212-8702598 Fax: 02128702598')
        );

        $date = new \DateTime();
        $topright = array($date->format('d/m/Y h:i:s A'),
            iconv('utf-8', 'windows-1252', 'Año Escolar: '.$periodo_activo->getNombre())
        );


        $query = $this->getDoctrine()->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->createQueryBuilder('periodo_alumno')
            ->select('periodo_alumno', 'alumnos')
            ->Join('periodo_alumno.alumno', 'alumnos')
            ->Join('periodo_alumno.cursoSeccion', 'cursoSeccion')
            ->Join('alumnos.alumnoRepresentanteDatos', 'alumno_representantes')
            ->Join('alumno_representantes.representante', 'representantes')
            ->Join('representantes.representanteContacto', 'contactos')
            ->where('periodo_alumno.activo = true')
            ->andwhere('alumnos.activo = true')
            ->andwhere('representantes.activo = true')
            ->orderBy('cursoSeccion.id', 'ASC')
            ->addOrderBy('alumnos.primerApellido', 'ASC')
            ->getQuery();

        $datos = $query->getResult();
        $pdf =  new pdfService();
        $pdf->SetFont('Arial','',8);
        $pdf->setHeaderImage($directoryPath."/../web/public/images/fe-y-alegria_fav.png");
        $pdf->setheaderTitle($title);
        $pdf->setHeaderfontSize(9);
        $pdf->setHeaderTopRight($topright);
        $pagewidthMargin = $pdf->GetPageWidth()-20;
        $currentCurso = '';
        $i=1;
        foreach($datos as $dato){
            $cedula = $dato->getAlumno()->getCedula();
            if(empty($cedula)){
                $cedula = $dato->getAlumno()->getCedulaEstudiantil();
            }
            $estudianteInfo = strtoupper($dato->getAlumno()->getApellidoNombreCompleto()).'   Cedula: '.$cedula;
            $lugarNacimiento = $dato->getAlumno()->getLugarNacimiento();
            if(!empty($lugarNacimiento)){
                $estudianteInfo = $estudianteInfo.'    Lugar de Nacimiento: '.$lugarNacimiento;
            }
            $estudianteInfo = $estudianteInfo."\n";
            $rep = '';
            $direccion = '';
            foreach($dato->getAlumno()->getAlumnoRepresentanteDatos() as $representanteDatos){
                $contacto ='Teléfono: ';
                if($representanteDatos->getPrincipal()){
                    foreach($representanteDatos->getRepresentante()->getRepresentanteContacto() as $representanteContacto){
                        $contacto = $contacto.' '. $representanteContacto->getContacto();
                    }
                    $repPpal = 'Representante Legal: '.$representanteDatos->getRepresentante()->getNombreApellido(). '    Cedula: '. $representanteDatos->getRepresentante()->getCedula().'    parentesco: '.$representanteDatos->getParentesco()->getNombre()."\n";
                    $direccion = 'dirección: '.$representanteDatos->getRepresentante()->getDireccion();
                }
                else{
                    foreach($representanteDatos->getRepresentante()->getRepresentanteContacto() as $representanteContacto){
                        $contacto = $contacto.' '. $representanteContacto->getContacto();
                    }
                    $rep= $rep.'Representante: '.$representanteDatos->getRepresentante()->getNombreApellido(). '    parentesco: '.$representanteDatos->getParentesco()->getNombre().' '.$contacto."\n";
                }
            }
            $estudianteInfo = iconv('utf-8', 'windows-1252', $estudianteInfo);
            $direccion = iconv('utf-8', 'windows-1252', $direccion);
            $contacto = iconv('utf-8', 'windows-1252', $contacto);
            $rep = iconv('utf-8', 'windows-1252', $rep);
            if(intval($pdf->GetY()+30)>240 or $currentCurso != $dato->getCursoSeccion()->getId()){
                $i=1;
                $pdf->AddPage('P', 'Letter');
                $pdf->SetFont('Arial','',10);
                $pdf->Cell($pagewidthMargin, 8, 'DIRECTORIO DE ALUMNOS', 0, 0, 'C');
                $pdf->Ln(6);
                $pdf->SetFont('Arial','',8);
                $curso = $dato->getCursoSeccion()->getCurso()->getNombre();
                $seccion = $dato->getCursoSeccion()->getSeccion()->getNombre();
                $curso_str = 'Curso: '.$curso.'     '.' Seccion:'.$seccion;
                $pdf->Cell($pagewidthMargin, 6, iconv('utf-8', 'windows-1252', $curso_str), 0, 0, 'C');
                $pdf->Ln(6);
                $pdf->Cell($pagewidthMargin, 6, iconv('utf-8', 'windows-1252', 'No   Estudiante'), 1, 0, 'L');
                $pdf->Ln(6);
            }
            $pdf->Multicell($pagewidthMargin, 6, $i.'    '.$estudianteInfo);
            $pdf->SetX(15);
            $pdf->Multicell($pagewidthMargin, 5, 'Fecha de Nacimiento: '.$dato->getAlumno()->getFechaNacimiento()->format('d/m/Y').'    Edad: '.$dato->getAlumno()->getEdad());
            $pdf->SetX(15);
            $pdf->Multicell(0, 4, $direccion);
            $pdf->SetX(15);
            $pdf->Multicell($pagewidthMargin, 6, $repPpal.$contacto);
            $pdf->Multicell($pagewidthMargin, 5, '     '.$rep, 'B');
            $currentCurso = $dato->getCursoSeccion()->getId();
            $i=$i+1;
        }
        return new Response(
            $pdf->Output(),
            200,
            array('Content-Type' => 'application/pdf'));
    }
    public function relacionMensualidadesAction(Request $request)
    {

        $periodo_activo = $this->getDoctrine()
            ->getRepository('inicialBundle:PeriodoEscolar')
            ->findOneBy(array('activo'=>true));
        $directoryPath = $this->container->getParameter('kernel.root_dir');
        $title = array(iconv('utf-8', 'windows-1252', 'U.E. Col Maria Rosa Molas - Fé y Alegría'),
            iconv('utf-8', 'windows-1252', 'RIF: J-00133027-5          NIT: 0082854118'),
            iconv('utf-8', 'windows-1252', 'Teléfono: 0212-8702598 Fax: 02128702598')
        );

        $date = new \DateTime();
        $topright = array($date->format('d/m/Y h:i:s A'),
            iconv('utf-8', 'windows-1252', 'Año Escolar: '.$periodo_activo->getNombre())
        );
        $periodo_alumnos = $this->getDoctrine()
            ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->findBy(
                array('activo'=>true),
                array('cursoSeccion' => 'ASC')
            );
        $facturaMensualidad = $this->getDoctrine()
            ->getRepository('facturacionBundle:TipoFactura')
            ->findOneBy(array('mensualidad'=>true, 'activo'=>true));

        $pdf =  new pdfService();
        $pdf->SetFont('Arial','',8);
        $pdf->setHeaderImage($directoryPath."/../web/public/images/fe-y-alegria_fav.png");
        $pdf->setheaderTitle($title);
        $pdf->setHeaderfontSize(9);
        $pdf->setHeaderTopRight($topright);
        $pagewidthMargin = $pdf->GetPageWidth()-20;
        $currentCurso = '';
        $i=1;
        $arrayContents = array();
        $curso = '';
        $AlumnCount = 0;
        $total_mensual = 0;
        $currentCurso = '';
        $first = true;
        foreach($periodo_alumnos as $dato){
            if($currentCurso != $dato->getCursoSeccion()->getCurso()->getId() and !$first) {
                $arrayContents[] = array('curso'=>$curso, 'cant_alumnos'=>$AlumnCount, 'total_mensual' => $total_mensual);
                $curso = '';
                $AlumnCount = 0;
                $total_mensual = 0;
            }
            $curso = $dato->getCursoSeccion()->getCurso()->getNombre();
            foreach($dato->getAlumno()->getMontosAlumnos() as $montosAlumn){
                foreach($montosAlumn->getConceptoFactura()->getTipoFactura() as $tipoFact){

                    if($tipoFact->getId() == $facturaMensualidad->getId()){


                        $total_mensual = $total_mensual + $montosAlumn->getMonto();
                    }
                }
            }

            $currentCurso = $dato->getCursoSeccion()->getCurso()->getId();
            $AlumnCount = $AlumnCount+1;
            if($first){
                $first = false;
            }
        }
        $i=1;
        $pdf->AddPage('P', 'Letter');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell($pagewidthMargin, 8, 'RELACION DE MENSUALIDADES', 0, 0, 'C');
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',8);
        $curso = $dato->getCursoSeccion()->getCurso()->getNombre();
        $seccion = $dato->getCursoSeccion()->getSeccion()->getNombre();
        $curso_str = 'Curso: '.$curso.'     '.' Seccion:'.$seccion;
        $pdf->Cell(40);
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', 'Curso'), 0, 0, 'C');
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', 'No de Alumnos'), 0, 0, 'C');
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', 'Cuota Promedio'), 0, 0, 'C');
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', 'Total Mensual'), 0, 0, 'C');
        $pdf->Ln(6);
        $totalMensual = 0;
        $totalalumnos = 0;
        foreach($arrayContents as $content){
            $pdf->Cell(40);
            $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', $content['curso']), 'B', 0, 'C');
            $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', $content['cant_alumnos']), 'B', 0, 'C');
            $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', intval($content['total_mensual']/$content['cant_alumnos'])), 'B', 0, 'C');
            $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', $content['total_mensual']), 'B', 0, 'C');
            $pdf->Ln(6);
            $totalalumnos = $totalalumnos + $content['cant_alumnos'];
            $totalMensual = $totalMensual + $content['total_mensual'];

        }
        $totalpromedio = ($totalMensual/$totalalumnos);
        $pdf->SetY(-27);
        $pdf->Cell(40);
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', 'TOTAL'), 'TB', 0, 'C');
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', $totalalumnos), 'TB', 0, 'C');
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252', intval($totalpromedio)), 'TB', 0, 'C');
        $pdf->Cell(30, 6, iconv('utf-8', 'windows-1252',$totalMensual), 'TB', 0, 'C');
        return new Response(
            $pdf->Output(),
            200,
            array('Content-Type' => 'application/pdf'));
    }
    public function listado_alumnos_mensualidadesAction(Request $request)
    {
        $periodo_activo = $this->getDoctrine()
            ->getRepository('inicialBundle:PeriodoEscolar')
            ->findOneBy(array('activo'=>true));
        $directoryPath = $this->container->getParameter('kernel.root_dir');
        $title = array(iconv('utf-8', 'windows-1252', 'U.E. Col Maria Rosa Molas - Fé y Alegría'),
            iconv('utf-8', 'windows-1252', 'Teléfono: 0212-8702598 Fax: 02128702598')
        );

        $date = new \DateTime();
        $topright = array($date->format('d/m/Y h:i:s A'),
            iconv('utf-8', 'windows-1252', 'Año Escolar: '.$periodo_activo->getNombre())
        );

        $query = $this->getDoctrine()->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->createQueryBuilder('periodo_alumno')
            ->select('periodo_alumno', 'alumnos')
            ->Join('periodo_alumno.alumno', 'alumnos')
            ->Join('periodo_alumno.cursoSeccion', 'cursoSeccion')
            ->where('periodo_alumno.activo = true')
            ->andwhere('alumnos.activo = true')
            ->orderBy('cursoSeccion.id', 'ASC')
            ->addOrderBy('alumnos.primerApellido', 'ASC')
            ->getQuery();

        $datos = $query->getResult();
        $pdf =  new pdfService();
        $pdf->SetFont('Arial','',8);
        $pdf->setHeaderImage($directoryPath."/../web/public/images/fe-y-alegria_fav.png");
        $pdf->setheaderTitle($title);
        $pdf->setHeaderfontSize(9);
        $pdf->setHeaderTopRight($topright);
        $pagewidthMargin = $pdf->GetPageWidth()-20;
        $currentCurso = '';
        $i=1;
        $facturaMensualidad = $this->getDoctrine()
            ->getRepository('facturacionBundle:TipoFactura')
            ->findOneBy(array('mensualidad'=>true, 'activo'=>true));
        foreach($datos as $dato){

            $estudianteInfo = strtoupper($dato->getAlumno()->getApellidoNombreCompleto());

            $conceptosFactura = [];
            foreach($facturaMensualidad->getConceptosFactura() as $conFact){
                $conceptosFactura = $conFact->getId();
            }
            $MontosAlumno = $this->getDoctrine()
                ->getRepository('facturacionBundle:MontosAlumnos')
                ->findBy(array('alumno'=>$dato->getAlumno(), 'conceptoFactura'=>$conceptosFactura));
            $monto = 0;
            foreach($MontosAlumno as $montoAlumn){
                $monto = $monto + $montoAlumn->getMonto();
            }


            $estudianteInfo = iconv('utf-8', 'windows-1252', $estudianteInfo);
            $monto= iconv('utf-8', 'windows-1252', $monto);
            if(intval($pdf->GetY()+30)>240 or $currentCurso != $dato->getCursoSeccion()->getId()){
                $i=1;
                $pdf->AddPage('P', 'Letter');
                $pdf->SetFont('Arial','',10);
                $pdf->Cell($pagewidthMargin, 8, 'COMPROMISO ECONOMICO DE LOS ESTUDIANTES', 0, 0, 'C');
                $pdf->Ln(6);
                $pdf->SetFont('Arial','',8);
                $curso = $dato->getCursoSeccion()->getCurso()->getNombre();
                $seccion = $dato->getCursoSeccion()->getSeccion()->getNombre();
                $curso_str = 'Curso: '.$curso.'     '.' Seccion:'.$seccion;
                $pdf->Cell($pagewidthMargin, 6, iconv('utf-8', 'windows-1252', $curso_str), 0, 0, 'C');
                $pdf->Ln(6);
                $pdf->Cell($pagewidthMargin, 6, iconv('utf-8', 'windows-1252', 'No   Estudiante                                                                      APORTE ECONOMICO'), 1, 0, 'L');
                $pdf->Ln(6);
            }
            $pdf->Cell(90, 6, $i.'    '.$estudianteInfo);
            $pdf->Cell(10, 6, $monto);
            $pdf->Cell(90, 5, '', 'B');
            $pdf->Ln(6);
            $currentCurso = $dato->getCursoSeccion()->getId();
            $i=$i+1;
        }
        return new Response(
            $pdf->Output(),
            200,
            array('Content-Type' => 'application/pdf'));
    }
    public function listado_alumnosAction(Request $request)
    {
        $periodo_activo = $this->getDoctrine()
            ->getRepository('inicialBundle:PeriodoEscolar')
            ->findOneBy(array('activo'=>true));
        $directoryPath = $this->container->getParameter('kernel.root_dir');
        $title = array(iconv('utf-8', 'windows-1252', 'U.E. Col Maria Rosa Molas - Fé y Alegría'),
            iconv('utf-8', 'windows-1252', 'Teléfono: 0212-8702598 Fax: 02128702598')
        );

        $date = new \DateTime();
        $topright = array($date->format('d/m/Y h:i:s A'),
            iconv('utf-8', 'windows-1252', 'Año Escolar: '.$periodo_activo->getNombre())
        );


        $query = $this->getDoctrine()->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->createQueryBuilder('periodo_alumno')
            ->select('periodo_alumno', 'alumnos')
            ->Join('periodo_alumno.alumno', 'alumnos')
            ->Join('periodo_alumno.cursoSeccion', 'cursoSeccion')
            ->where('periodo_alumno.activo = true')
            ->andwhere('alumnos.activo = true')
            ->orderBy('cursoSeccion.id', 'ASC')
            ->addOrderBy('alumnos.primerApellido', 'ASC')
            ->getQuery();

        $datos = $query->getResult();
        $pdf =  new pdfService();
        $pdf->SetFont('Arial','',7);
        $pdf->setHeaderImage($directoryPath."/../web/public/images/fe-y-alegria_fav.png");
        $pdf->setheaderTitle($title);
        $pdf->setHeaderfontSize(9);
        $pdf->setHeaderTopRight($topright);
        $pagewidthMargin = $pdf->GetPageWidth()-20;
        $currentCurso = '';
        $i=1;
        foreach($datos as $dato){
            $estudianteInfo = strtoupper($dato->getAlumno()->getApellidoNombreCompleto());
            $estudianteInfo = iconv('utf-8', 'windows-1252', $estudianteInfo);
            if(intval($pdf->GetY()+30)>240 or $currentCurso != $dato->getCursoSeccion()->getId()){
                $i=1;
                $pdf->AddPage('P', 'Letter');
                $pdf->SetFont('Arial','',9);
                $pdf->Cell($pagewidthMargin, 8, 'LISTADO DE ALUMNOS', 0, 0, 'C');
                $pdf->Ln(6);
                $pdf->SetFont('Arial','',8);
                $curso = $dato->getCursoSeccion()->getCurso()->getNombre();
                $seccion = $dato->getCursoSeccion()->getSeccion()->getNombre();
                $curso_str = 'Curso: '.$curso.'     '.' Seccion:'.$seccion;
                $pdf->Cell($pagewidthMargin, 6, iconv('utf-8', 'windows-1252', $curso_str), 0, 0, 'C');
                $pdf->Ln(6);
                $pdf->Cell(6, 5, iconv('utf-8', 'windows-1252', 'No'), 1, 0, 'L');
                $pdf->Cell(100, 5, iconv('utf-8', 'windows-1252', 'Alumnos '), 1, 0, 'C');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Cell(9, 5, '', '1');
                $pdf->Ln(5);
            }
            $pdf->Cell(6, 5, $i, 1);
            $pdf->Cell(100, 5, $estudianteInfo, 1);
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');
            $pdf->Cell(9, 5, '', '1');

            $pdf->Ln(5);
            $currentCurso = $dato->getCursoSeccion()->getId();
            $i=$i+1;
        }
        return new Response(
            $pdf->Output(),
            200,
            array('Content-Type' => 'application/pdf'));
    }
    public function recibo_pagoAction($id, Request $request)
    {
        $facturas = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->find($id);

        $pagosId = array();
        foreach($facturas->getPagos() as $pagos){
            $pagosId[]= $pagos->getId();
        }
        $pago = $this->getDoctrine()
            ->getRepository('genericoBundle:Pagos')
            ->findBy(array('id'=>$pagosId));


        $fecha_actual = new \DateTime("now");
        $html = $this->renderView('genericoBundle:Default:recibo_pago.html.twig', array('accion'=>'Listado de Alumnos', 'fecha'=>$fecha_actual, 'facturas' => $facturas, 'pagos'=>$pago));
        //print_r($html);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array('Content-Type' => 'application/pdf'));
    }
    public function constancia_inscripcionAction($id, Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno as estudiante', 'periodo_alumno as periodo_estudiante', 'cursos as curso', 'secciones as seccion',
                'periodos as periodo', 'etapas as etapa')
            ->where('alumno.id = :id')
            ->andwhere('alumno.activo = true')
            ->innerJoin('alumnosBundle:PeriodoEscolarCursoAlumno', 'periodo_alumno', 'WITH', 'alumno.id = periodo_alumno.alumno')
            ->innerJoin('inicialBundle:PeriodoEscolar', 'periodos', 'WITH', 'periodo_alumno.periodoEscolar = periodos.id')
            ->innerJoin('inicialBundle:CursoSeccion', 'periodo_curso', 'WITH', 'periodo_alumno.cursoSeccion = periodo_curso.id')
            ->innerJoin('inicialBundle:Curso', 'cursos', 'WITH', 'periodo_curso.curso = cursos.id')
            ->innerJoin('inicialBundle:Seccion', 'secciones', 'WITH', 'periodo_curso.seccion = secciones.id')
            ->innerJoin('inicialBundle:Etapa', 'etapas', 'WITH', 'periodo_curso.etapa = etapas.id')
            ->setParameter('id',$id)
            ->getQuery();

        $datos = $query->getArrayResult();

        $fecha_nacimiento = $datos[0]['estudiante']['fechaNacimiento'];
        $fecha_actual = new \DateTime("now");
        $fecha_actual->getTimezone();
        $diff = $fecha_actual->diff($fecha_nacimiento);
        $edad = $diff->y;

        $html = $this->renderView('genericoBundle:Default:constancia_inscripcion.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos, 'edad'=>$edad, 'fecha'=>$fecha_actual));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf'
            )
        );
    }
    public function constancia_estudiosAction($id, Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno as estudiante', 'periodo_alumno as periodo_estudiante', 'cursos as curso', 'secciones as seccion',
                'periodos as periodo', 'etapas as etapa')
            ->where('alumno.id = :id')
            ->andwhere('alumno.activo = true')
            ->innerJoin('alumnosBundle:PeriodoEscolarCursoAlumno', 'periodo_alumno', 'WITH', 'alumno.id = periodo_alumno.alumno')
            ->innerJoin('inicialBundle:PeriodoEscolar', 'periodos', 'WITH', 'periodo_alumno.periodoEscolar = periodos.id')
            ->innerJoin('inicialBundle:CursoSeccion', 'periodo_curso', 'WITH', 'periodo_alumno.cursoSeccion = periodo_curso.id')
            ->innerJoin('inicialBundle:Curso', 'cursos', 'WITH', 'periodo_curso.curso = cursos.id')
            ->innerJoin('inicialBundle:Seccion', 'secciones', 'WITH', 'periodo_curso.seccion = secciones.id')
            ->innerJoin('inicialBundle:Etapa', 'etapas', 'WITH', 'periodo_curso.etapa = etapas.id')

            ->setParameter('id',$id)
            ->getQuery();
        $datos = $query->getArrayResult();

        $fecha_nacimiento = $datos[0]['estudiante']['fechaNacimiento'];
        $fecha_actual = new \DateTime("now");
        $fecha_actual->getTimezone();
        $diff = $fecha_actual->diff($fecha_nacimiento);
        $edad = $diff->y;

        $html = $this->renderView('genericoBundle:Default:constancia_estudios.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos, 'edad'=>$edad, 'fecha'=>$fecha_actual));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf'
            )
        );
    }
    public function lista_alumnoAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno.id','alumno.cedula','alumno.cedulaEstudiantil', 'alumno.primerApellido', 'alumno.primerNombre', 'alumno.fechaNacimiento', 'usuario.nombres as Nombre_Representante', 'usuario.apellidos as Apellido_Representante', 'usuario.id as usuario_id')
            ->leftJoin('alumno.representante', 'usuario')
            ->where('usuario.activo = true')
            ->where('usuario.principal = true')
            ->andwhere('alumno.activo = true')
            ->orderBy('alumno.id', 'DESC')
            ->getQuery();

        $datos = $query->getArrayResult();

        $html = $this->renderView('genericoBundle:Default:listado_alumnos.html.twig', array('accion'=>'Listado de Alumnos', 'datos'=>$datos));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf'
            )
        );
    }
    public function agregar_pagoAction($id, request $request)
    {
        $factura = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->find($id);
        $estudiante = $factura->getPeriodoEscolarCursoAlumnos()->getAlumno();
        $p = new Pagos();
        $p->setFechaRegistro(new \DateTime("now"));
        $formulario = $this->createForm(new PagosType('Agregar Pago'), $p);
        $formulario-> handleRequest($request);
        foreach ($estudiante->getAlumnoRepresentanteDatos() as $alumno_rep_datos) {
            if ($alumno_rep_datos->getPrincipal() == true) {
                $representante_ppal = $this->getDoctrine()
                    ->getRepository('usuariosBundle:Usuarios')
                    ->find($alumno_rep_datos->getRepresentante()->getId());
            }
        }
        $alumnos_rep = '';
        foreach ($representante_ppal->getAlumnoRepresentanteDatos() as $alumno_tmp) {
            $alumnos_rep[] = $alumno_tmp->getAlumno();
        }
        $periodo_alumnos = $this->getDoctrine()
            ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->findby(array('alumno' => $alumnos_rep, 'activo' => 'true'));
        $periodos_ids ='';
        foreach ($periodo_alumnos as $per_alumno_tmp) {
            $periodos_ids[] = $per_alumno_tmp->getId();
        }

        $facturas = $this->getDoctrine()
            ->getRepository('facturacionBundle:Factura')
            ->findBy(array(
                'periodoEscolarCursoAlumnos'=> $periodos_ids,
                'pagada'=>false,
                'activo'=>true
            ));

        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {


                $facturaSeleccionadas = $request->request->get('facturas');
                if(empty($facturaSeleccionadas)){
                    $this->get('session')->getFlashBag()->add(
                        'warning', 'Debe seleccionar al menos una factura');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }

                $pagoFacturas = $this->getDoctrine()
                    ->getRepository('facturacionBundle:Factura')
                    ->findBy(array('id'=> $facturaSeleccionadas));

                $p->setActivo(true);
                $monto_facturas_seleccionadas = 0;

                foreach($pagoFacturas as $factura){
                    $factura->setPagada(true);
                    $p->addFactura($factura);
                    $monto_facturas_seleccionadas = $monto_facturas_seleccionadas + $factura->getMonto();
                }
                if($p->getMonto()<$monto_facturas_seleccionadas){
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Monto del pago insuficiente para cubrir las facturas seleccionadas');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Pago creado con éxito'
                );

                if ($formulario->get('guardar')->isClicked()){
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
                if ($formulario->get('guardar_crear')->isClicked()){
                    return $this->redirect($this->generateUrl('generico_agregar_pago'));
                }
            }
        }
        return $this->render('genericoBundle:Default:agregar_pago.html.twig',
            array(
                'form'=>$formulario->createView(),
                'accion'=>'Agregar Pago',
                'factura'=>$factura,
                'estudiante'=>$estudiante,
                'facturas'=>$facturas

            ));
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


    public function crear_parentescoAction(Request $request){
        $modelo = New Parentescos();
        $form = new ParentescosType('Crear Parentesco');
        $objeto = 'Parentescos';
        $clase = 'genericoBundle:Parentescos';
        $titulo = 'Parentesco';
        $datos = 'true';
        $remover = '';
        $url_redireccion = 'generico_agregar_parentescos';
        $url_editar = 'generico_editar_parentescos';
        $url_borrar = 'generico_borrar_parentescos';
        $resultado = $this->get('funciones_genericas')->crear_generico($request, $modelo, $form, $objeto, $clase, $titulo, $url_redireccion, $url_editar, $url_borrar, $datos, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function editar_parentescoAction($id, Request $request){
        $form = new ParentescosType('Editar Parentesco');
        $clase = 'genericoBundle:Parentescos';
        $titulo = 'Parentesco';
        $url_redireccion = 'generico_agregar_parentescos';
        $remover = null;
        $resultado = $this->get('funciones_genericas')->editar_generico($id, $request, $form, $clase, $titulo, $url_redireccion, $remover);
        if(array_key_exists('resulado', $resultado)) {
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:mantenimiento' . '.html.twig', $resultado);
    }
    public function borrar_parentescoAction($id, Request $request){
        $form = New ParentescosType('Borrar Parentesco');
        $objeto = 'Parentescos';
        $clase = 'genericoBundle:Parentescos';
        $titulo = 'Parentesco';
        $remover = null;
        $url_redireccion = 'generico_agregar_parentescos';
        $resultado = $this->get('funciones_genericas')->borrar_generico($id, $request, $form, $clase, $objeto, $titulo, $url_redireccion);
        if(array_key_exists('resulado', $resultado)){
            return $this->redirect($this->generateUrl($resultado['url']));
        }
        return $this->render('inicialBundle:Default:borrar' . '.html.twig', $resultado);
    }

    public function inscripcion_completaAction($id_rep, $id_agg_rep, Request $request)
    {
        $inscripcion = $this->getDoctrine()
            ->getRepository('genericoBundle:Inscripcion')
            ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
        if(empty($inscripcion)){
            $inscripcion = new Inscripcion();
            $inscripcion->setFecha(new \DateTime("now"));
            $inscripcion->setIscripcionHash(md5($inscripcion->getFecha()->format('Y-m-d-H-i-s').$this->getUser()->getUsername()));
            $inscripcion->setActivo(true);
            $inscripcion->setEstatus(1);
            $inscripcion->setUsuario($this->getUser()->getUsuario());
            $em = $this->getDoctrine()->getManager();
            $em->persist($inscripcion);
            $em->flush();

        }
        if(!empty($id_agg_rep)){
            $representante = $this->getDoctrine()
                ->getRepository('usuariosBundle:Usuarios')
                ->find($id_agg_rep);
            if (!$representante)
            {
                throw $this -> createNotFoundException('No existe representante con este id: '.$id_agg_rep);
            }
            else{
                $representantes = $inscripcion->getRepresentantes();
                $ids =explode(',', $representantes);
                if(!in_array($id_agg_rep, $ids)){
                    if(empty($representantes)){
                        $representantes = $id_agg_rep;
                    }
                    else{
                        $representantes = $representantes.','.$id_agg_rep;
                    }
                    $inscripcion->setRepresentantes($representantes);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Representante Asignado con éxito');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
                else {
                    $this->get('session')->getFlashBag()->add(
                        'warning', 'Representante ya esta asignado a esta inscripción');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
            }
        }

        $representantes = null;
        $representantes_ids = $inscripcion->getRepresentantes();
        if(!empty($representantes_ids)){
            $ids =explode(',', $representantes_ids);
            $representantes = $this->getDoctrine()
                ->getRepository('usuariosBundle:Usuarios')
                ->findBy(array('id'=> $ids));
        }
        $alumnosInscripcion = null;
        $alumnosInscripcion_ids = $inscripcion->getAlumnos();
        if(!empty($alumnosInscripcion_ids)){
            $ids =explode(',', $alumnosInscripcion_ids);
            $alumnosInscripcion = $this->getDoctrine()
                ->getRepository('alumnosBundle:Alumnos')
                ->findBy(array('id'=> $ids));
        }
        $facturasInscripcion = null;
        $facturasInscripcion_ids = $inscripcion->getFacturas();
        if(!empty($facturasInscripcion_ids)){
            $ids =explode(',', $facturasInscripcion_ids);
            $facturasInscripcion = $this->getDoctrine()
                ->getRepository('facturacionBundle:Factura')
                ->findBy(array('id'=> $ids));
        }
        $pagosFacturas = null;
        $pagosFacturas_ids = $inscripcion->getPagos();
        if(!empty($pagosFacturas_ids)){
            $ids = explode(',', $pagosFacturas_ids);
            $pagosFacturas = $this->getDoctrine()
                ->getRepository('facturacionBundle:Factura')
                ->findBy(array('id'=> $ids));
        }
        $facturasPagadas = null;
        $facturasPagadas_ids = $inscripcion->getFacturasPagadas();
        if(!empty($facturasPagadas_ids)){
            $ids = explode(',', $facturasPagadas_ids);
            $facturasPagadas = $this->getDoctrine()
                ->getRepository('facturacionBundle:Factura')
                ->findBy(array('id'=> $ids));
        }
        $alumnosRepresentantesDatos = new ArrayCollection();
        if(!empty($alumnosInscripcion) and !empty($representantes)){
            foreach($alumnosInscripcion as $alumnos_tmp){
                foreach($representantes as $rep){
                    $alumnoRepDatos = $this->getDoctrine()
                        ->getRepository('alumnosBundle:AlumnoRepresentanteDatos')
                        ->findOneBy(array('representante'=> $rep->getId(), 'alumno'=>$alumnos_tmp->getId()));
                    if(empty($alumnoRepDatos)){
                        $alumnoRepDatos = new AlumnoRepresentanteDatos();
                        $alumnoRepDatos->setAlumno($alumnos_tmp);
                        $alumnoRepDatos->setRepresentante($rep);
                    }
                    $alumnosRepresentantesDatos->add($alumnoRepDatos);
                }
            }
        }
        $alumnosRepDatosForm =$this->createForm( new AlumnosTypeAggRepsDatos('Datos de parentesco'), array('test' => $alumnosRepresentantesDatos));
        $alumnosRepDatosForm-> handleRequest($request);
        $formInscripcion = $this->createForm(new InscripcionType('test'), $inscripcion);
        $formInscripcion-> handleRequest($request);


        if($inscripcion->getEstatus() == 3 and count($alumnosInscripcion) != count($inscripcion->getFacturas())) {
            $tipo_factura = $this->getDoctrine()->getRepository('facturacionBundle:TipoFactura')->findOneBy(array('inscripcion'=>true));

            if(empty($facturasInscripcion)){
                foreach ($alumnosInscripcion as $estudiante) {
                    $fact = $this->get('funciones_genericas')->crear_factura($estudiante->getId(), $tipo_factura);
                    if(empty($facturasInscripcion_ids)){
                        $facturasInscripcion_ids = $fact->getId();
                    }
                    else{
                        $facturasInscripcion_ids = $facturasInscripcion_ids.','.$fact->getId();
                    }
                }
                $inscripcion->setFacturas($facturasInscripcion_ids);
                $inscripcion->setEstatus(4);
                $em = $this->getDoctrine()->getManager();

                $em->flush();
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        if($inscripcion->getEstatus() == 4 and count($facturasInscripcion) > count($facturasPagadas)) {
            $p = new Pagos();
            $facturasPorPagos = [];
            $totalFacturado = 0;

            foreach ($facturasInscripcion as $factura) {
                if(!$factura->getPagada()){
                    $totalFacturado = $totalFacturado + $factura->getMonto();
                    $facturasPorPagos[] = $factura;
                }
            }
            $formularioPagos = $this->createForm(new PagosType('Agregar Pago'), $p);
            $formularioPagos->handleRequest($request);

            if ($request->getMethod() == 'POST') {
                if ($formularioPagos->isValid()) {
                    $facturaSeleccionadas = $request->request->get('facturas');
                    if(empty($facturaSeleccionadas)){
                        $this->get('session')->getFlashBag()->add(
                            'warning', 'Debe seleccionar al menos una factura');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    $pagoFacturas = $this->getDoctrine()
                        ->getRepository('facturacionBundle:Factura')
                        ->findBy(array('id'=> $facturaSeleccionadas));

                    $p->setActivo(true);
                    $monto_facturas_seleccionadas = 0;

                    foreach($pagoFacturas as $factura){
                        $factura->setPagada(true);
                        $p->addFactura($factura);
                        $monto_facturas_seleccionadas = $monto_facturas_seleccionadas + $factura->getMonto();
                    }
                    if($p->getMonto()<$monto_facturas_seleccionadas){
                        $this->get('session')->getFlashBag()->add(
                            'success', 'Monto del pago insuficiente para cubrir las facturas seleccionadas');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($p);
                    $pagosFacturaIds = $inscripcion->getPagos();
                    if(empty($pagosFacturaIds )){
                        $pagosFacturaIds = $p->getId();
                    }
                    else{
                        $pagosFacturaIds = $pagosFacturaIds .','.$p->getId();
                    }
                    $inscripcion->setPagos($pagosFacturaIds);

                    $facturasPagadasIds = $inscripcion->getFacturasPagadas();
                    $str_fact_select = '';
                    foreach($facturaSeleccionadas as $fact_select){
                        $str_fact_select = empty($str_fact_select) ? $fact_select : $str_fact_select .','.$fact_select;
                    }
                    if(empty($facturasPagadasIds )){
                        $facturasPagadasIds = $str_fact_select;
                    }
                    else{
                        $facturasPagadasIds = $facturasPagadasIds .','.$str_fact_select;
                    }
                    $inscripcion->setfacturasPagadas($facturasPagadasIds);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Pago creado con éxito'
                    );
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
            }
        }
        elseif($inscripcion->getEstatus() == 4 and count($facturasInscripcion) == count($facturasPagadas)) {
            $em = $this->getDoctrine()->getManager();
            $inscripcion->setEstatus(5);
            $em->flush();
        }

        if ($inscripcion->getEstatus() == 5 and $inscripcion->getActivo()){
            $accion = 'Resumen Inscripción';
            $periodos_alumnos=[];
            foreach($alumnosInscripcion as $estudiante) {
                $periodo_alumno = $this->getDoctrine()
                    ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
                    ->findby(array('alumno' => $estudiante->getId(), 'activo' => 'true'));
                array_push($periodos_alumnos, $periodo_alumno);
            }

        }
        if($request->getMethod()=='POST') {
            if ($alumnosRepDatosForm->get('guardar')->isClicked()) {

                if ($alumnosRepDatosForm->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    foreach ($alumnosRepresentantesDatos as $alumnoRepDatos) {
                        $em->persist($alumnoRepDatos);
                    }
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Datos Actualizados con éxito');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
            }
            if ($formInscripcion->get('guardar')->isClicked()) {
                if($inscripcion->getEstatus()==1){
                    if (count($alumnosRepresentantesDatos) == count($alumnosInscripcion) * count($representantes)) {
                        $alumn_ced_procesada = 1;
                        foreach($alumnosInscripcion as $p) {
                            $cedula_alumno = '';
                            $rep_ppal = 0;
                            $representante_ppal = '';
                            $ced_rep = false;
                            if (!$p->getCedula()) {
                                $cedula_alumno = false;
                            }
                            foreach ($p->getAlumnoRepresentanteDatos() as $alumno_rep_datos) {
                                if ($alumno_rep_datos->getPrincipal() == true) {
                                    $rep_ppal = $rep_ppal + 1;
                                    if ($cedula_alumno == false) {
                                        $representante_ppal = $this->getDoctrine()
                                            ->getRepository('usuariosBundle:Usuarios')
                                            ->find($alumno_rep_datos->getRepresentante()->getId());
                                    }
                                }
                            }

                            if ($rep_ppal == 0 or $rep_ppal > 1) {
                                $this->get('session')->getFlashBag()->add(
                                    'danger', 'Debe Seleccionar un Representante Principal para cada alumno');

                                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                            }

                            if ($cedula_alumno == false) {
                                if ($representante_ppal->getAlumnoRepresentanteDatos()->count()>1) {
                                    foreach ($representante_ppal->getAlumnoRepresentanteDatos() as $alum_rep_datos) {
                                        if ($alum_rep_datos->getAlumno()->getId() != $p->getId()) {
                                            if($alum_rep_datos->getAlumno()->getFechaNacimiento() == $p->getFechaNacimiento()) {
                                                $ced_rep = true;
                                            }
                                        }
                                    }
                                    if($ced_rep == true){
                                        $queryCedula = true;
                                        while(!empty($queryCedula)){
                                            $cedulaEstudiantil = $alumn_ced_procesada . $p->getFechaNacimiento()->format('y') . $representante_ppal->getCedula();
                                            $queryCedula = $this->queryCedulaEstudiantil($cedulaEstudiantil);
                                            if(!empty($queryCedula)){
                                                $alumn_ced_procesada = $alumn_ced_procesada +1;
                                            }
                                        }
                                        $p->setCedulaEstudiantil($alumn_ced_procesada . $p->getFechaNacimiento()->format('y') . $representante_ppal->getCedula());
                                        $alumn_ced_procesada = $alumn_ced_procesada +1;
                                    }
                                    else{
                                        $alumn_ced_correlativo = 0;
                                        $cedulaEstudiantil = $p->getFechaNacimiento()->format('y') . $representante_ppal->getCedula();
                                        $queryCedula = $this->queryCedulaEstudiantil($cedulaEstudiantil);
                                        while(!empty($queryCedula)){
                                            $cedulaEstudiantil = $alumn_ced_correlativo . $p->getFechaNacimiento()->format('y') . $representante_ppal->getCedula();
                                            $queryCedula = $this->queryCedulaEstudiantil($cedulaEstudiantil);
                                            if(!empty($queryCedula)){
                                                $alumn_ced_correlativo = $alumn_ced_correlativo +1;
                                            }
                                        }
                                        $p->setCedulaEstudiantil($cedulaEstudiantil);
                                    }

                                } else {
                                    $alumn_ced_correlativo = 0;
                                    $cedulaEstudiantil = $p->getFechaNacimiento()->format('y') . $representante_ppal->getCedula();
                                    $queryCedula = $this->queryCedulaEstudiantil($cedulaEstudiantil);
                                    while(!empty($queryCedula)){
                                        $cedulaEstudiantil = $alumn_ced_correlativo . $p->getFechaNacimiento()->format('y') . $representante_ppal->getCedula();
                                        $queryCedula = $this->queryCedulaEstudiantil($cedulaEstudiantil);
                                        if(!empty($queryCedula)){
                                            $alumn_ced_correlativo = $alumn_ced_correlativo +1;
                                        }
                                    }
                                    $p->setCedulaEstudiantil($cedulaEstudiantil);
                                }
                            }
                        }
                        $em = $this->getDoctrine()->getManager();
                        $inscripcion->setEstatus(3);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                            'success', 'Datos Actualizados con éxito');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    else {
                        $this->get('session')->getFlashBag()->add(
                            'warning', 'Debe completar los datos de parentezco');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                }
//                elseif($inscripcion->getEstatus()==2) {
//                    if (count($inscripcion->getMontosParticulares()) == count($inscripcion->getMontosParticularesProcesados())) {
//                        $em = $this->getDoctrine()->getManager();
//                        $inscripcion->setEstatus(3);
//                        $em->flush();
//                        $this->get('session')->getFlashBag()->add(
//                            'success', 'Datos Actualizados con éxito');
//                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
//                    } else {
//                        $this->get('session')->getFlashBag()->add(
//                            'warning', 'Agregar todos los montos particulares');
//                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
//                    }
//                }

                elseif($inscripcion->getEstatus()==3){
                    if(count($inscripcion->getFacturas())== count($inscripcion->getPagos())){
                        $em = $this->getDoctrine()->getManager();
                        $inscripcion->setEstatus(4);
                        $inscripcion->setActivo(false);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                            'success', 'Datos Actualizados con éxito');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                    else {
                        $this->get('session')->getFlashBag()->add(
                            'warning', 'Agregar todos los montos particulares');
                        return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                    }
                }
                elseif($inscripcion->getEstatus()==5){
                    $em = $this->getDoctrine()->getManager();
                    $inscripcion->setEstatus(6);
                    $inscripcion->setActivo(false);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Inscripcion Finalizada Correctamente');
                    return $this->redirect($this->generateUrl('inicial_homepage'));
                }
            }
        }

        if($inscripcion->getEstatus() == 1){
            $template = 'genericoBundle:Default:crear_generico2.html.twig';
        }
        elseif($inscripcion->getEstatus()==2){
            $template = 'genericoBundle:Default:crear_generico4.html.twig';
        }
        elseif($inscripcion->getEstatus()==3){
            $template = 'genericoBundle:Default:crear_generico4.html.twig';
        }
        elseif($inscripcion->getEstatus()>=4){
            $template = 'genericoBundle:Default:crear_generico3.html.twig';
        }

        return $this->render($template,
            array(
                'representantes'=>$representantes,
                'inscripcion'=>$inscripcion,
                'alumnosInscripcion' => $alumnosInscripcion,
                'alumnosRepDatosForm' =>$alumnosRepDatosForm ? $alumnosRepDatosForm->createView(): '',
                'formInscripcion' => $formInscripcion->createView(),
                'formPagos' => isset($formularioPagos) ? $formularioPagos->createView():'',
                'facturas' => isset($facturasInscripcion) ? $facturasInscripcion :'',
                'facturasPorPagos' => isset($facturasPorPagos) ? $facturasPorPagos :'',
                'totalFacturado' => isset($totalFacturado) ? $totalFacturado : '',
                'accion'=>isset($accion) ? $accion : '',
                'periodos_alumnos' => isset($periodos_alumnos) ? $periodos_alumnos : ''
            )
        );
    }

    private function queryCedulaEstudiantil($ced_test){
        $verifyced = $this->getDoctrine()
            ->getRepository('alumnosBundle:Alumnos')
            ->findOneBy(array('cedulaEstudiantil'=>$ced_test, 'activo'=>true));
        return $verifyced;
    }

    public function formulario_crear_representante_genericoAction(Request $request){
        $p = new Usuarios();
        $titulo= 'Crear Representante';
        $formulario = $this->createForm(new UsuariosTypeInscripcion($titulo), $p);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');

        $tipo_usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:TipoUsuario')
            ->find(5);
        $p->setTipoUsuario($tipo_usuario);
        $p->setPrincipal(false);

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {

                $inscripcion = $this->getDoctrine()
                    ->getRepository('genericoBundle:Inscripcion')
                    ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));

                $em = $this->getDoctrine()->getManager();
                $p->setInscripcion($inscripcion);
                $em->persist($p);

                $representantes = $inscripcion->getRepresentantes();
                if(empty($representantes)){
                    $representantes = $p->getId();
                }
                else{
                    $representantes = $representantes.','.$p->getId();
                }
                $inscripcion->setRepresentantes($representantes);
                $em->flush();
//                $session->set("inscripcion", $inscripcion);
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante Creado con éxito');
//                return array('representante'=>$p,);
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
//        return array('form'=>$formulario->createView(), 'accion'=>'Crear Representante');
        return $this->render('genericoBundle:Default/parts:crear_representante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Representante'));
    }

    public function formulario_actualizar_representante_genericoAction(Request $request, $id){
        $p = $this->getDoctrine()
            ->getRepository('usuariosBundle:Usuarios')
            ->find($id);
        if (!$p)
        {
            throw $this -> createNotFoundException('No existe Estudiante con este id: '.$id);
        }

        $titulo= 'Crear Representante';
        $formulario = $this->createForm(new UsuariosTypeInscripcion($titulo), $p);
        $formulario -> remove('tipoUsuario');
        $formulario -> remove('principal');

        $tipo_usuario = $this->getDoctrine()
            ->getRepository('usuariosBundle:TipoUsuario')
            ->find(5);
        $p->setTipoUsuario($tipo_usuario);
        $p->setPrincipal(false);

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Representante Actualizado con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:editar_representante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Actualizar Representante'));
    }

    public function quitar_representante_inscripcionAction(Request $request, $id){
        $inscripcion = $this->getDoctrine()
            ->getRepository('genericoBundle:Inscripcion')
            ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
        $representantes = $inscripcion->getRepresentantes();
        if(!empty($representantes)) {
            $representantes = str_replace($id, '', $representantes);
            $representantes = str_replace(',,', ',', $representantes);
            $representantes = ltrim($representantes, ',');
            $representantes = rtrim($representantes, ',');
            $inscripcion->setRepresentantes($representantes);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'warning', 'Representante removido con éxito');
        }
        return new JsonResponse(true, 200);
    }
    public function quitar_alumno_inscripcionAction(Request $request, $id){
        $inscripcion = $this->getDoctrine()
            ->getRepository('genericoBundle:Inscripcion')
            ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
        $alumnos = $inscripcion->getAlumnos();
        if(!empty($alumnos)) {
            $alumno = $this->getDoctrine()
                ->getRepository('alumnosBundle:Alumnos')
                ->find($id);
            if (!$alumno)
            {
                throw $this -> createNotFoundException('No existe Estudiante con este id: '.$id);
            }
            $alumnos = str_replace($id, '', $alumnos);
            $alumnos = str_replace(',,', ',', $alumnos);
            $alumnos = ltrim($alumnos, ',');
            $alumnos = rtrim($alumnos, ',');
            $em = $this->getDoctrine()->getManager();
            $inscripcion->setAlumnos($alumnos);
            $em->flush();

            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();
            $em->remove($alumno);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'warning', 'Estudiante eliminado con éxito');
        }
        return new JsonResponse(true, 200);
    }

    public function formulario_crear_estudiante_genericoAction(Request $request){
        $alumno = new Alumnos();
        $monto = new MontosAlumnos();
        $periodo_alumno = new PeriodoEscolarCursoAlumno();
        $alumno->addPeriodoEscolarCursoAlumno($periodo_alumno);
        $tfact = $this->getDoctrine()
            ->getRepository('facturacionBundle:TipoFactura')
            ->findOneBy(array('activo' => 'true', 'mensualidad'=>true));
        $conFact = $tfact->getConceptosFactura();
        foreach($conFact as $cfact){
            $monto->setConceptoFactura($cfact);
        }
        $monto->setActivo(true);
        $alumno->addMontosAlumnos($monto);
        $cursos = $this->getDoctrine()
            ->getRepository('inicialBundle:CursoSeccion')
            ->findBy(array('activo'=>true));

        $cant_seccion = [];
        foreach($cursos as $curso){
            $alumnos = $this->getDoctrine()
                ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
                ->findBy(array('cursoSeccion'=>$curso->getId(), 'activo'=>true));
            $cant_seccion[$curso->getId()] =  array('cupos'=>$curso->getCupos(), 'alumnos'=>count($alumnos));
        }

        $formulario = $this->createForm(new AlumnosTypeInscripcion('Crear Estudiante', null, $cant_seccion), $alumno);

        $formulario -> remove('activo');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {
                $inscripcion = $this->getDoctrine()
                    ->getRepository('genericoBundle:Inscripcion')
                    ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));
                $alumno->setActivo(true);

                $periodo_activo = $this->getDoctrine()
                    ->getRepository('inicialBundle:PeriodoEscolar')
                    ->findOneBy(array('activo'=>true));

                foreach($alumno->getPeriodoEscolarCursoAlumno() as $periodo_alumno){
                    $periodo_alumno->setPeriodoEscolar($periodo_activo);
                    $periodo_alumno->setActivo(true);
                }

                $em = $this->getDoctrine()->getManager();
                $alumno->setInscripcion($inscripcion);
                $em->persist($alumno);

                $alumnosInscripcionIds = $inscripcion->getAlumnos();
                if(empty($alumnosInscripcionIds )){
                    $alumnosInscripcion = $alumno->getId();
                }
                else{
                    $alumnosInscripcion = $alumnosInscripcionIds .','.$alumno->getId();
                }
                $inscripcion->setAlumnos($alumnosInscripcion);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Estudiante Creado con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:crear_estudiante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Crear Estudiante'));
    }

    public function formulario_actualizar_estudiante_genericoAction(Request $request, $id)
    {

        $alumno = $this->getDoctrine()
            ->getRepository('alumnosBundle:Alumnos')
            ->find($id);
        if (!$alumno)
        {
            throw $this -> createNotFoundException('No existe Estudiante con este id: '.$id);
        }

        $cursos = $this->getDoctrine()
            ->getRepository('inicialBundle:CursoSeccion')
            ->findBy(array('activo'=>true));

        $cant_seccion = [];
        foreach($cursos as $curso){
            $alumnos = $this->getDoctrine()
                ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
                ->findBy(array('cursoSeccion'=>$curso->getId(), 'activo'=>true));
            $cant_seccion[$curso->getId()] =  array('cupos'=>$curso->getCupos(), 'alumnos'=>count($alumnos));
        }

        $formulario = $this->createForm(new AlumnosTypeInscripcion('Actualizar Estudiante', null, $cant_seccion), $alumno);
        $formulario -> remove('alumnoRepresentanteDatos');
        $formulario-> handleRequest($request);
        if($request->getMethod()=='POST') {

            if ($formulario->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Estudiante Actualizado con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:editar_estudiante.html.twig', array('form'=>$formulario->createView(), 'accion'=>'Actualizar Estudiante'));
    }

    public function inscripcion_lista_agregar_representanteAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('usuariosBundle:Usuarios')
            ->createQueryBuilder('usuario')
            ->select('usuario.cedula, usuario.primerApellido, usuario.primerNombre, usuario.fechaNacimiento, usuario.direccion, usuario.id')
            ->innerJoin('usuariosBundle:TipoUsuario', 'tipo_usuario', 'WITH', 'usuario.tipoUsuario = tipo_usuario.id')
            ->where('usuario.activo = true')
            ->andWhere('tipo_usuario.id=5')
            ->orderBy('usuario.id', 'DESC')
            ->getQuery();
        $datos = $query->getArrayResult();
        $elemento = 'Seleccione Representante';

        return $this->render('genericoBundle:Default/parts:agregar_representante_existente.html.twig', array('accion'=>$elemento, 'lista_representante'=>$datos));
    }

    public function formulario_agregar_monto_estudiante_genericoAction(Request $request, $id){
        $p = $this->getDoctrine()
            ->getRepository('facturacionBundle:TipoFactura')
            ->findBy(array('activo' => 'true', 'inscripcion'=>false));

        foreach ($p as $tfact) {
            foreach ($tfact->getConceptosFactura() as $con_fact) {
                $con_fact->addMontosAlumno(New MontosAlumnos());
            }
        }
        $formulario = $this->createForm('collection', $p, array('type' => new TipoFacturaType('Crear Tipo Factura', null, true), 'allow_add' => true, 'allow_delete' => false,
            'by_reference' => false, 'prototype' => false, 'label' => false, 'cascade_validation' => false,
            'error_bubbling' => false));
        $formulario->handleRequest($request);
        $estudiante = $this->getDoctrine()
            ->getRepository('alumnosBundle:Alumnos')
            ->find($id);
        if($request->getMethod()=='POST') {
            if ($formulario->isValid()) {
                // $p->setActivo(true);

                foreach ($p as $tipo_factura) {
                    foreach($tipo_factura->getConceptosFactura() as $concepto_factura) {
                        foreach ($concepto_factura->getMontosAlumnos() as $monto_alumno) {
                            $monto_alumno->setConceptoFactura($concepto_factura);
                            $monto_alumno->setAlumno($estudiante);
                            $monto_alumno->setActivo(true);
                        }
                    }
                }
                $inscripcion = $this->getDoctrine()
                    ->getRepository('genericoBundle:Inscripcion')
                    ->findOneBy(array('usuario'=>$this->getUser(), 'activo'=>true));

                $alumnosMontoParticularIds = $inscripcion->getAlumnos();
                if(empty($alumnosMontoParticularIds )){
                    $alumnosInscripcion = $estudiante->getId();
                }
                else{
                    $alumnosInscripcion = $alumnosMontoParticularIds .','.$estudiante->getId();
                }
                $inscripcion->setMontosParticularesProcesados($alumnosInscripcion);

                $em = $this->getDoctrine()->getManager();
                foreach ($p as $tipo_factura) {
                    $em->persist($tipo_factura);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success', 'Montos particulares del estudiante creados con éxito');
                return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
            }
        }
        return $this->render('genericoBundle:Default/parts:crear_montos_estudiante.html.twig',
            array('form'=>$formulario->createView(),
                'accion'=>'Crear Montos para Estudiante',
                'estudiante'=>$estudiante
            )
        );
    }
    public function finalizarPeriodoAction(Request $request, $id_alumn_rep){
        $finalizarPeriodo = $this->getDoctrine()
            ->getRepository('genericoBundle:PeriodoEscolarFinalizado')
            ->findOneBy(array('usuario'=>$this->getUser(), 'finalizado'=>false));
        $periodoActivo = $this->getDoctrine()
            ->getRepository('inicialBundle:PeriodoEscolar')
            ->findOneBy(array('activo'=>true));
        if(empty($finalizarPeriodo)){
            $finalizarPeriodo = new PeriodoEscolarFinalizado();
            $finalizarPeriodo->setFecha(new \DateTime("now"));
            $finalizarPeriodo->setProcesoHash(md5($finalizarPeriodo->getFecha()->format('Y-m-d-H-i-s').$this->getUser()->getUsername()));
            $finalizarPeriodo->setActivo(true);
            $finalizarPeriodo->setFinalizado(false);
            $finalizarPeriodo->setEstatus(1);
            $finalizarPeriodo->setUsuario($this->getUser()->getUsuario());
            $finalizarPeriodo->setPeriodoEscolarFinalizado($periodoActivo);
            $em = $this->getDoctrine()->getManager();
            $em->persist($finalizarPeriodo);
            $em->flush();
        }
//        var_dump($periodoActivo);
        $cursoAlumnos = $this->getDoctrine()
            ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->findBy(array('periodoEscolar'=>$periodoActivo, 'activo'=>true));
//        var_dump(count($cursoAlumnos));

        $alumnosReprobados = null;
        $alumnosReprobados_ids = $finalizarPeriodo->getAlumnosReprobados();
        if(!empty($alumnosReprobados_ids)){
            $ids =explode(',', $alumnosReprobados_ids);
            $alumnosReprobados = $this->getDoctrine()
                ->getRepository('alumnosBundle:Alumnos')
                ->findBy(array('id'=> $ids));
        }

        if(isset($id_alumn_rep)){
            $alumno = $this->getDoctrine()
                ->getRepository('alumnosBundle:Alumnos')
                ->find($id_alumn_rep);

            if (!$alumno)
            {
                throw $this -> createNotFoundException('No existe representante con este id: '.$id_alumn_rep);
            }
            else{
                $alumnosReprob = $finalizarPeriodo->getAlumnosReprobados();
                $ids =explode(',', $alumnosReprob);
                if(!in_array($id_alumn_rep, $ids)){
                    if(empty($alumnosReprob)){
                        $alumnosReprob = $id_alumn_rep;
                    }
                    else{
                        $alumnosReprob = $alumnosReprob.','.$id_alumn_rep;
                    }
                    $finalizarPeriodo->setAlumnosReprobados($alumnosReprob);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success', 'Estudiante asignado con éxito');
                    return $this->redirect($this->generateUrl('generico_inscripcion_completa'));
                }
                else {
                    $this->get('session')->getFlashBag()->add(
                        'warning', 'Estudiante ya esta asignado a esta inscripción');
                    return $this->redirect($this->generateUrl('generico_finalizar_periodo_escolar'));
                }
            }
        }

        return $this->render('genericoBundle:Default:finalizarPeriodoEscolar.html.twig',
            array(
                'finalizarPeriodo'=>$finalizarPeriodo,
                'alumnosReprobados'=>$alumnosReprobados
            )
        );
    }
    public function finalizar_periodo_lista_alumnos_reprobAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('alumnosBundle:Alumnos')
            ->createQueryBuilder('alumno')
            ->select('alumno.id','alumno.cedula', 'alumno.cedulaEstudiantil', 'alumno.primerApellido', 'alumno.primerNombre', 'alumno.fechaNacimiento', 'curso.nombre as Grado', 'seccion.nombre as Seccion', "CONCAT( CONCAT(representante.primerNombre, ' '),  representante.primerApellido) as Representante", 'representante.id as usuario_id')
            ->innerJoin('alumno.alumnoRepresentanteDatos', 'alumnoRepresentante')
            ->innerJoin('alumnoRepresentante.representante', 'representante')
            ->innerJoin('alumno.periodoEscolarCursoAlumno', 'cursoAlumno')
            ->innerJoin('cursoAlumno.cursoSeccion', 'curso_seccion')
            ->innerJoin('curso_seccion.curso', 'curso')
            ->innerJoin('curso_seccion.seccion', 'seccion')
            ->where('representante.activo = true')
            ->andwhere('alumno.activo = true')
            ->andwhere('alumnoRepresentante.principal = true')
            ->andwhere('cursoAlumno.activo = true')
//            ->groupBy('curso.paso')
            ->addOrderBy('curso.paso', 'ASC')
            ->addOrderBy('seccion.nombre', 'ASC')
            ->addOrderBy('alumno.primerApellido', 'ASC')
//            ->addOrderBy('alumno.id', 'ASC')
            ->getQuery();

        $datos = $query->getArrayResult();
        $elemento = 'Seleccione Alumnos Reprobados';

        return $this->render('genericoBundle:Default/parts:seleccionarAlumnosReprobados.html.twig', array('accion'=>$elemento, 'lista_alumnos'=>$datos));
    }

}
