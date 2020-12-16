<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Swift_Mailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\Exceleador;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserController extends EasyAdminController
{
    public $activeBack;
    private $exceleador;
    private $parameter_bag;

    public function __construct(
        Exceleador $exceleador,
        ParameterBagInterface $parameter_bag
    ) {
        $this->parameter_bag = $parameter_bag;
        $this->exceleador = $exceleador;
    }

    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [
            Swift_Mailer::class,
            UserPasswordEncoderInterface::class,
        ];
    }

    protected function createUserEditForm($entity, array $entityProperties)
    {
        $this->activeBack = $entity->getActive();

        return parent::createEditForm($entity, $entityProperties);
    }

    protected function persistEntity($entity)
    {
        if (!empty($this->request->request->get('user')['plainPassword'])) {
            $entity->setPassword(
                $this->get(UserPasswordEncoderInterface::class)->encodePassword(
                    $entity,
                    $this->request->request->get('user')['plainPassword']
                    )
                );
        }

        parent::persistEntity($entity);
    }

    protected function updateEntity($entity)
    {
        if (!empty($this->request->request->get('user')['plainPassword'])) {
            $entity->setPassword(
                $this->get(UserPasswordEncoderInterface::class)->encodePassword(
                    $entity,
                    $this->request->request->get('user')['plainPassword']
                    )
                );
        }

        parent::updateEntity($entity);

        if (!$this->activeBack && $entity->getActive()) {
            $message = (new \Swift_Message('congresos.aareiq.org'))
                ->setFrom($this->getParameter('mailer_user_from'))
                ->setTo($entity->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activeUser.html.twig',
                        ['name' => $entity->nombreCompleto()]
                    ),
                    'text/html'
                )
            ;

            $this->get(\Swift_Mailer::class)->send($message);
        }
    }

    public function usuariosExcelBatchAction(array $ids)
    {
        $fileName = 'usuarios.xlsx';

        $this->exceleador->usuariosExcel($ids, $fileName);

        $excelFilepath = '/tmp/excel_'.$fileName;

        return $this->file($excelFilepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

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

        return $this->usuariosExcelBatchAction($ids);
    }
}
