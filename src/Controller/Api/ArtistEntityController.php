<?php

namespace App\Controller\Api;

use App\Entity\ArtistEntity;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArtistEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: "Artist")]
class ArtistEntityController extends AbstractController
{
    public function __construct(
        private ArtistEntityRepository $artistEntityRepository,
        private EntityManagerInterface $entityManager
     ) {
     }

    #[Route('/api/artists', name: 'app_api_artists', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ArtistEntity::class, groups: ['read']))
        )
    )]
    public function getArtists(): Response
    {
        $artists = $this->artistEntityRepository->findAll();

        return $this->json([
            'artists' => $artists,
         ], 200, [], [
            'groups' => ['read']
         ]);
    }

    #[Route('/api/artist/{id}', name: 'app_api_artist_id', methods: ['GET'])]
    public function showArtist(int $id): Response
    {
        $artist = $this->artistEntityRepository->find($id);

        if (!$artist) {
            return $this->json(['message' => 'Artist not found'], 404);
        }

        return $this->json([
            'artist' => $artist,
        ], 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/artist', name: 'app_api_artist_create', methods: ['POST'])]
    public function createArtist(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;

        $artist = new ArtistEntity();
        $artist->setName($name);

        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        return $this->json(['message' => 'Artist created successfully'], 201);
    }

    #[Route('/api/artist/{id}', name: 'app_api_artist_update', methods: ['PUT'])]
    public function updateArtist(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $artist = $this->artistEntityRepository->find($id);

        if (!$artist) {
            return $this->json(['message' => 'Artist not found'], 404);
        }

        $name = $data['name'] ?? null;

        if (!empty($name)) {
            $artist->setName($name);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Artist updated successfully'], 200);
    }

    #[Route('/api/artist/{id}', name: 'app_api_artist_delete', methods: ['DELETE'])]
    public function deleteArtist(int $id): Response
    {
        $artist = $this->artistEntityRepository->find($id);

        if (!$artist) {
            return $this->json(['message' => 'Artist not found'], 404);
        }

        $this->entityManager->remove($artist);
        $this->entityManager->flush();

        return $this->json(['message' => 'Artist deleted successfully'], 200);
    }
}
