<?php

namespace App\Controller\Web;

use App\Entity\TrackEntity;
use App\Form\TrackEntityType;
use App\Repository\TrackEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/track')]
class TrackEntityController extends AbstractController
{
    #[Route('/', name: 'app_web_track_entity_index', methods: ['GET'])]
    public function index(TrackEntityRepository $trackEntityRepository): Response
    {
        return $this->render('web/track_entity/index.html.twig', [
            'track_entities' => $trackEntityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_web_track_entity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trackEntity = new TrackEntity();
        $form = $this->createForm(TrackEntityType::class, $trackEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trackEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_web_track_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/track_entity/new.html.twig', [
            'track_entity' => $trackEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_track_entity_show', methods: ['GET'])]
    public function show(TrackEntity $trackEntity): Response
    {
        return $this->render('web/track_entity/show.html.twig', [
            'track_entity' => $trackEntity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_web_track_entity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrackEntity $trackEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrackEntityType::class, $trackEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_web_track_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/track_entity/edit.html.twig', [
            'track_entity' => $trackEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_track_entity_delete', methods: ['POST'])]
    public function delete(Request $request, TrackEntity $trackEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trackEntity->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($trackEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_web_track_entity_index', [], Response::HTTP_SEE_OTHER);
    }
}
