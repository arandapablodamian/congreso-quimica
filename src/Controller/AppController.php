<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Congreso;
use App\Entity\EventoTipo;
use App\Entity\Evento;
use App\Entity\Inscripcion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(Request $request)
    {
        $congresos = $this->getDoctrine()->getRepository(Congreso::class)
            ->findOneBy(['activo' => true], ['id' => 'DESC']);

        $eventoTipos = $this->getDoctrine()->getRepository(EventoTipo::class)
            ->findBy(['activo' => true], ['orden' => 'ASC']);

        return $this->render('app/index.html.twig', [
            'congresos' => $congresos,
            'eventoTipos' => $eventoTipos
        ]);
    }

    /**
     * @Route("/usuario/inscribirse/{eventoId}", name="app_inscribirse")
     */
    public function inscribirse($eventoId, Request $request, ValidatorInterface $validator)
    {
        // Controlar el cupo del evento
        $entityManager = $this->getDoctrine()->getManager();
        $evento = $entityManager->getRepository(Evento::class)->find($eventoId);

        if (!$evento->tieneCupo()) {
            $this->addFlash('warning', 'inscripcion_sin_cupo');

            return $this->redirectToRoute('app_index');
        }

        $form = $this->createFormBuilder()
            ->add('confirmar', SubmitType::class, [
                'label' => 'confirmar_inscripcion',
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ])
            ->getForm();

        $form->handleRequest($request);
        $errorsString = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $inscripcion = new Inscripcion();
            $inscripcion->setUser($this->getUser());
            $inscripcion->setEvento($evento);

            $errors = $validator->validate($inscripcion);

            if (count($errors) > 0) {
                $errorsString = $errors;
            } else {
                $entityManager->persist($inscripcion);
                $entityManager->flush();
                $this->addFlash('success', 'inscripcion_inscripto');

                return $this->redirectToRoute('app_index');
            }
        }

        return $this->render('app/inscripcion.html.twig', [
            'evento' => $evento,
            'form' => $form->createView(),
            'error' => $errorsString,
        ]);
    }

    /**
     * @Route("/usuario/mis-inscripciones", name="app_eventos_inscripto")
     */
    public function usuarioEventosInscripto()
    {
        $inscripciones = $this->getDoctrine()->getManager()->getRepository(Inscripcion::class)->findByUser($this->getUser()->getId());

        return $this->render('app/eventosInscripto.html.twig', [
            'inscripciones' => $inscripciones,
        ]);
    }

    /**
     * @Route("/requisitosEvento/{id}", name="requisitosEvento")
     */
    public function requisitosEventoAction(Evento $evento)
    {
        return new JsonResponse($evento->getRequisitos());
    }
}
