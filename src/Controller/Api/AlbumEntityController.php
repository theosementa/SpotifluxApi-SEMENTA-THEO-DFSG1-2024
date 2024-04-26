<?php

namespace App\Controller\Api;

use App\Entity\AlbumEntity;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AlbumEntityRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: "Album")]
class AlbumEntityController extends AbstractController
{

    public function __construct(
        private AlbumEntityRepository $albumEntityRepository,
        private EntityManagerInterface $entityManager
     ) {
     }

     
    #[Route('/api/albums', name: 'app_api_albums', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AlbumEntity::class, groups: ['read']))
        )
    )]
    public function getAlbums(): Response
    {
        $albums = $this->albumEntityRepository->findAll();

        return $this->json([
            'albums' => $albums,
         ], 200, [], [
            'groups' => ['read']
         ]);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_id', methods: ['GET'])]
    public function showAlbum(int $id): Response
    {
        $album = $this->albumEntityRepository->find($id);

        if (!$album) {
            return $this->json(['message' => 'Album not found'], 404);
        }

        return $this->json([
            'album' => $album,
        ], 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/album', name: 'app_api_album_create', methods: ['POST'])]
    public function createAlbum(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;
        $year = $data['year'] ?? null;

        $album = new AlbumEntity();
        $album->setName($name);
        $album->setYear($year);

        $this->entityManager->persist($album);
        $this->entityManager->flush();

        return $this->json(['message' => 'Album created successfully'], 201);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_update', methods: ['PUT'])]
    public function updateAlbum(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $album = $this->albumEntityRepository->find($id);

        if (!$album) {
            return $this->json(['message' => 'Album not found'], 404);
        }

        $name = $data['name'] ?? null;
        $year = $data['year'] ?? null;

        if (!empty($name)) {
            $album->setName($name);
        }

        if (!empty($year)) {
            $album->setYear($year);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Album updated successfully'], 200);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_delete', methods: ['DELETE'])]
    public function deleteAlbum(int $id): Response
    {
        $album = $this->albumEntityRepository->find($id);

        if (!$album) {
            return $this->json(['message' => 'Album not found'], 404);
        }

        $this->entityManager->remove($album);
        $this->entityManager->flush();

        return $this->json(['message' => 'Album deleted successfully'], 200);
    }
}
