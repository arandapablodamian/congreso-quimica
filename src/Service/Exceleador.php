<?php

namespace App\Service;

// Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Inscripcion;
use App\Entity\User;

class Exceleador
{
    //aprendiendo a inyectar
    private $parameter_bag;
    private $em;
    private $container;

    public function __construct(ParameterBagInterface $parameter_bag, EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->parameter_bag = $parameter_bag;
        $this->em = $em;
        $this->container = $container;
    }

    public function inscriptosExcel($ids, $fileName)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Congreso');
        $sheet->setCellValue('B1', 'Evento');
        $sheet->setCellValue('C1', 'Curso');
        $sheet->setCellValue('D1', 'Usuario');
        $sheet->setCellValue('E1', 'Apellido y Nombre');
        $sheet->setCellValue('F1', 'DNI');
        $sheet->setCellValue('G1', 'Estado');
        $sheet->setCellValue('H1', 'Asociacion');

        $i = 2;
        foreach ($ids as $id) {
            $inscripcion = $this->em->getRepository(Inscripcion::class, 'encopress')->find($id);

            $congreso = $inscripcion->getEvento()->getEventoTipo()->getCongreso()->getTitulo();
            $sheet->setCellValue('A'.$i, $congreso);

            $eventoTipo = $inscripcion->getEvento()->getEventoTipo()->getNombre();
            $sheet->setCellValue('B'.$i, $eventoTipo);

            $curso = $inscripcion->getEvento()->getTitulo();
            $sheet->setCellValue('C'.$i, $curso);

            $usuario = $inscripcion->getUser()->getUsername();
            $sheet->setCellValue('D'.$i, $usuario);

            $apeNom = $inscripcion->getUser()->getApellido().', '.$inscripcion->getUser()->getNombre();
            $sheet->setCellValue('E'.$i, $apeNom);

            $dni = $inscripcion->getUser()->getDni();
            $sheet->setCellValue('F'.$i, $dni);

            if (0 == $inscripcion->getEstado()) {
                $estado = 'Inscripto';
            } elseif (1 == $inscripcion->getEstado()) {
                $estado = 'Asistio';
            } elseif (2 == $inscripcion->getEstado()) {
                $estado = 'Ausente';
            } else {
                $estado = '';
            }
            
            $sheet->setCellValue('G'.$i, $estado);

            $asociacion = $inscripcion->getUser()->getAsociacion();
            $sheet->setCellValue('H'.$i, $asociacion);
            
            ++$i;
        }

        $sheet->setTitle('Inscripciones');

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        $excelFilepath = '/tmp/excel_'.$fileName;
        // Create the file
        // delete if exist
        if (file_exists($excelFilepath)) {
            unlink($excelFilepath);
        }

        $writer->save($excelFilepath);
    }

    public function usuariosExcel($ids, $fileName)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Email');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Apellido');
        $sheet->setCellValue('D1', 'DNI');
        $sheet->setCellValue('E1', 'Telefóno');
        $sheet->setCellValue('F1', 'Pais');
        $sheet->setCellValue('G1', 'Mano Hábil');
        $sheet->setCellValue('H1', 'Obra Social');
        $sheet->setCellValue('I1', 'Grupo Sanguineo');
        $sheet->setCellValue('J1', 'Restricción Alimentaria');
        $sheet->setCellValue('K1', 'Alergias');
        $sheet->setCellValue('L1', 'Carreras');
        $sheet->setCellValue('M1', 'Asociación');
        $sheet->setCellValue('N1', 'Año de Cursado');
        $sheet->setCellValue('O1', 'Enfermedades Cronicas');
        $sheet->setCellValue('P1', 'Discapacidad');
        $sheet->setCellValue('Q1', 'Descripción discapacidad');
        $sheet->setCellValue('R1', 'Categoria');
        $sheet->setCellValue('S1', 'Activo');
        $sheet->setCellValue('T1', 'Realiza Visitas');
        $i = 2;
        foreach ($ids as $id) {
            $usuario = $this->em->getRepository(User::class)->find($id);

            $sheet->setCellValue('A'.$i, $usuario->getEmail());

            $sheet->setCellValue('B'.$i, $usuario->getNombre());

            $sheet->setCellValue('C'.$i, $usuario->getApellido());

            $sheet->setCellValue('D'.$i, $usuario->getDni());

            $sheet->setCellValue('E'.$i, $usuario->getTelefono());
            
            $sheet->setCellValue('F'.$i, $usuario->getPais());
            
            $sheet->setCellValue('G'.$i, $usuario->getManoHabil());
            
            $sheet->setCellValue('H'.$i, $usuario->getObraSocial());
            
            $sheet->setCellValue('I'.$i, $usuario->getGrupoSanguineo());
            
            $sheet->setCellValue('J'.$i, $usuario->getRestriccionAlimentaria());
            
            $sheet->setCellValue('K'.$i, $usuario->getAlergias());
            
            $sheet->setCellValue('L'.$i, $usuario->getCarreras());
            
            $sheet->setCellValue('M'.$i, $usuario->getAsociacion());
            
            $sheet->setCellValue('N'.$i, $usuario->getAnioCursado());
            
            $sheet->setCellValue('O'.$i, $usuario->getEnfermedadesCronicas());
            
            $sheet->setCellValue('P'.$i, $usuario->getDiscapacidad());
            
            $sheet->setCellValue('Q'.$i, $usuario->getDiscapacidadDescripcion());
            
            $sheet->setCellValue('R'.$i, $usuario->getCategoria());
            
            
            if($usuario->getActive()){
                $activo= 'Si';
            }else{
                $activo= 'No';
            }
            $sheet->setCellValue('S'.$i, $activo);
            
            if($usuario->getRealizaVisitas()){
                $visita= 'Si';
            }else{
                $visita= 'No';
            }
            
            $sheet->setCellValue('T'.$i, $visita);
            

            ++$i;
        }

        $sheet->setTitle('Usuario');

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        $excelFilepath = '/tmp/excel_'.$fileName;
        // Create the file
        // delete if exist
        if (file_exists($excelFilepath)) {
            unlink($excelFilepath);
        }

        $writer->save($excelFilepath);
    }
}
