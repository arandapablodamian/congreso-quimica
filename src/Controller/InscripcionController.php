<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use App\Service\Exceleador;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Inscripcion;

class InscripcionController extends EasyAdminController
{
    private $exceleador;
    private $parameter_bag;

    public function __construct(
        Exceleador $exceleador,
        ParameterBagInterface $parameter_bag
    ) {
        $this->parameter_bag = $parameter_bag;
        $this->exceleador = $exceleador;
    }

    public function inscripcionesExcelBatchAction(array $ids)
    {
        $fileName = 'Inscripciones.xlsx';

        $this->exceleador->inscriptosExcel($ids, $fileName);

        $excelFilepath = '/tmp/excel_'.$fileName;

        return $this->file($excelFilepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/exportAllAction", name="exportAllAction")
     */
    public function exportAllAction()
    {
        $searchQuery=$this->request->query->get('query') ;
        $searchableFields = $this->entity['search']['fields'];
        $sortDirection = $this->request->query->get('sortDirection');

        if (empty($sortDirection) || !in_array(strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'DESC';
        }
        // armo el query builder
         $queryBuilder= $this->createSearchQueryBuilder(
            $this->entity['class'],
            $searchQuery,
            $searchableFields,
            $this->request->query->get('sortField'),
            $sortDirection,
            $this->entity['list']['dql_filter']);
        
        // esta funcion aplica los filtros del formulario modal al query builder
        $this->filterQueryBuilder($queryBuilder);
        $inscripciones=$queryBuilder->getQuery()->getResult();
        
        $ids = [];
        foreach ($inscripciones as $key => $inscripcion) {
            $ids[] = $inscripcion->getId();
        }

        return $this->inscripcionesExcelBatchAction($ids);
    }

    protected function removeEntity($entity)
    {
        $entity->getEvento()->setCupoUtilizado($entity->getEvento()->getCupoUtilizado() - 1);

        parent::removeEntity($entity);
    }

    public function actualizarAsistenciaAction()
    {
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository(Inscripcion::class)->find($id);
        $entity->setEstado(1);

        $this->em->persist($entity);
        $this->em->flush();
        $this->addFlash('success', 'Registro modificado');

        return $this->redirectToRoute('easyadmin', ['entity' => 'Inscripcion', 'action' => 'list']);
    }
}
