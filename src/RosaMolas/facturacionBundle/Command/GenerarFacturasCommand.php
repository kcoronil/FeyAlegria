<?php
namespace RosaMolas\facturacionBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GenerarFacturasCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('RosaMolas:facturacion:generar_facturas')
        ->setDescription('Genera las facturas mensuales');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date_start = new \DateTime('midnight first day of this month');
        $date_end = new \DateTime('last day of this month');
        $test = $date_end->format('‌​Y-m-d H:i:s');
        $tipo_factura = $this->getContainer()->get('doctrine')->getRepository('facturacionBundle:TipoFactura')->find(2);
        $periodo_alumno = $this->getContainer()->get('doctrine')
            ->getRepository('alumnosBundle:PeriodoEscolarCursoAlumno')
            ->findBy(array('activo'=> 'true'));
        foreach($periodo_alumno as $per_alum){
            $query = $this->getContainer()->get('doctrine')->getRepository('facturacionBundle:Factura')
                ->createQueryBuilder('factura')
                ->innerJoin('factura.periodoEscolarCursoAlumnos', 'periodo_alumno')
                ->where('periodo_alumno.id = :id')
                ->andWhere('factura.activo = true')
                ->andWhere('factura.tipoFactura != 1')
                ->andwhere('factura.fecha BETWEEN :start AND :end')
                ->setParameter('start', str_replace("\xE2\x80\x8C\xE2\x80\x8B", "", $date_start->format('‌​Y-m-d H:i:s')))
                ->setParameter('end', str_replace("\xE2\x80\x8C\xE2\x80\x8B", "", $date_end->format('‌​Y-m-d H:i:s')))
                ->setParameter('id', $per_alum->getId())
                ->getQuery();
            $datos = $query->getResult();
            $output->writeln($per_alum->getAlumno()->getId());
            if(empty($datos)){
                $this->getContainer()->get('funciones_genericas')->crear_factura($per_alum->getAlumno()->getId(), $tipo_factura);
            }
        }
//        $output->writeln(var_dump(count($periodo_alumno)));
    }
}