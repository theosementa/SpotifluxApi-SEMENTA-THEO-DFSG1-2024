<?php

namespace App\Controller\Web;

use App\Entity\AlbumEntity;
use App\Form\AlbumEntityType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AlbumEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/web/album')]
class AlbumEntityController extends AbstractController
{
    #[Route('/', name: 'app_web_album_entity_index', methods: ['GET'])]
    public function index(AlbumEntityRepository $albumEntityRepository): Response
    {
        return $this->render('web/album_entity/index.html.twig', [
            'album_entities' => $albumEntityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_web_album_entity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $albumEntity = new AlbumEntity();
        $form = $this->createForm(AlbumEntityType::class, $albumEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($albumEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_web_album_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/album_entity/new.html.twig', [
            'album_entity' => $albumEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_album_entity_show', methods: ['GET'])]
    public function show(AlbumEntity $albumEntity): Response
    {
        return $this->render('web/album_entity/show.html.twig', [
            'album_entity' => $albumEntity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_web_album_entity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AlbumEntity $albumEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlbumEntityType::class, $albumEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_web_album_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/album_entity/edit.html.twig', [
            'album_entity' => $albumEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_album_entity_delete', methods: ['POST'])]
    public function delete(Request $request, AlbumEntity $albumEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$albumEntity->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($albumEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_web_album_entity_index', [], Response::HTTP_SEE_OTHER);
    }
}
