<?php

namespace App\Controller\Api;

use App\Entity\TrackEntity;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TrackEntityRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: "Track")]
class TrackEntityController extends AbstractController
{
    public function __construct(
        private TrackEntityRepository $trackEntityRepository,
        private EntityManagerInterface $entityManager
     ) {
     }

    #[Route('/api/tracks', name: 'app_api_tracks', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TrackEntity::class, groups: ['read']))
        )
    )]
    public function getTracks(): Response
    {
        $tracks = $this->trackEntityRepository->findAll();

        return $this->json([
            'tracks' => $tracks,
         ], 200, [], [
            'groups' => ['read']
         ]);
    }

    #[Route('/api/track/{id}', name: 'app_api_track_id', methods: ['GET'])]
    public function showTrack(int $id): Response
    {
        $track = $this->trackEntityRepository->find($id);

        if (!$track) {
            return $this->json(['message' => 'Track not found'], 404);
        }

        return $this->json([
            'track' => $track,
        ], 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/track', name: 'app_api_track_create', methods: ['POST'])]
    public function createTrack(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'] ?? null;
        $duration = $data['duration'] ?? null;

        $track = new TrackEntity();
        $track->setTitle($title);
        $track->setDuration($duration);

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        return $this->json(['message' => 'Track created successfully'], 201);
    }

    #[Route('/api/track/{id}', name: 'app_api_track_update', methods: ['PUT'])]
    public function updateTrack(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $track = $this->trackEntityRepository->find($id);

        if (!$track) {
            return $this->json(['message' => 'Track not found'], 404);
        }

        $title = $data['title'] ?? null;
        $duration = $data['duration'] ?? null;

        if (!empty($title)) {
            $track->setTitle($title);
        }

        if (!empty($duration)) {
            $track->setDuration($duration);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Track updated successfully'], 200);
    }

    #[Route('/api/track/{id}', name: 'app_api_track_delete', methods: ['DELETE'])]
    public function deleteTrack(int $id): Response
    {
        $track = $this->trackEntityRepository->find($id);

        if (!$track) {
            return $this->json(['message' => 'Track not found'], 404);
        }

        $this->entityManager->remove($track);
        $this->entityManager->flush();

        return $this->json(['message' => 'Track deleted successfully'], 200);
    }
}
