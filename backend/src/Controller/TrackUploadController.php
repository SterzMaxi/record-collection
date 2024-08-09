<?php
namespace App\Controller;

use App\Entity\Track;
use App\Entity\Record; // Ensure this is imported
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Psr\Log\LoggerInterface;

class TrackUploadController extends AbstractController
{
    #[Route('/api/track', name: 'api_create_track', methods: ['POST'])]
    public function createTrack(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] $user): Response
    {
        $recordId = $request->request->get('record_id');
        if (!$recordId) {
            return new Response('Record ID is missing', Response::HTTP_BAD_REQUEST);
        }

        $record = $entityManager->getRepository(Record::class)->find($recordId);
        if (!$record) {
            return new Response('Record not found', Response::HTTP_NOT_FOUND);
        }

        $artist = $request->request->get('artist');
        $tracknumber = $request->request->get('tracknumber');
        $tracktitle = $request->request->get('tracktitle');
        $tracktime = $request->request->get('tracktime');
        $genre = $request->request->get('genre');
        $listenlink = $request->request->get('listenlink');

        if (!$artist || !$tracknumber || !$tracktitle || !$tracktime || !$genre || !$listenlink) {
            return new Response('Missing track data', Response::HTTP_BAD_REQUEST);
        }

        $track = new Track(
            $record,
            $artist,
            $tracknumber,
            $tracktitle,
            $tracktime,
            $genre,
            $listenlink
        );

        $entityManager->persist($track);
        $entityManager->flush();

        return new Response('Track uploaded successfully', Response::HTTP_CREATED);
    }

    #[Route('/api/tracks', name: 'api_get_tracks', methods: ['GET'])]
    public function getTracks(TrackRepository $trackRepository): Response
    {
        $tracks = $trackRepository->findAll();

        $jsonTracks = [];
        foreach ($tracks as $track) {
            $jsonTracks[] = [
                'id' => $track->getId(),
                'artist' => $track->getArtist(),
                'tracknumber' => $track->getTracknumber(),
                'tracktitle' => $track->getTracktitle(),
                'tracktime' => $track->getTracktime(),
                'genre' => $track->getGenre(),
                'listenlink' => $track->getListenlink(),
                'record_id' => $track->getRecord()->getId(), // Assuming you need record ID
            ];
        }

        return new JsonResponse($jsonTracks, Response::HTTP_OK);
    }

    #[Route('/api/track/{id}', name: 'api_get_track', methods: ['GET'])]
    public function getTrack(int $id, TrackRepository $trackRepository): Response
    {
        $track = $trackRepository->find($id);

        if (!$track) {
            return new JsonResponse(['error' => 'Track not found'], Response::HTTP_NOT_FOUND);
        }

        $jsonTrack = [
            'id' => $track->getId(),
            'artist' => $track->getArtist(),
            'tracknumber' => $track->getTracknumber(),
            'tracktitle' => $track->getTracktitle(),
            'tracktime' => $track->getTracktime(),
            'genre' => $track->getGenre(),
            'listenlink' => $track->getListenlink(),
            'record_id' => $track->getRecord()->getId(), // Assuming you need record ID
        ];

        return new JsonResponse($jsonTrack, Response::HTTP_OK);
    }

    #[Route('/api/track/{id}', name: 'api_delete_track', methods: ['DELETE'])]
    public function deleteTrack(int $id, TrackRepository $trackRepository, EntityManagerInterface $entityManager): Response
    {
        $track = $trackRepository->find($id);

        if (!$track) {
            return new JsonResponse(['error' => 'Track not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($track);
        $entityManager->flush();

        return new Response('Track deleted successfully', Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/collection/{collectionId}/record/{recordId}/tracks', name: 'api_get_tracks_for_record', methods: ['GET'])]
    public function getTracksForRecord(int $collectionId, int $recordId, TrackRepository $trackRepository, EntityManagerInterface $entityManager): Response
    {
        $record = $entityManager->getRepository(Record::class)->findOneBy([
            'id' => $recordId,
            'collection' => $collectionId
        ]);

        if (!$record) {
            return new JsonResponse(['error' => 'Record not found', $record], Response::HTTP_NOT_FOUND);
        }

        // Find tracks by the Record entity reference
        $tracks = $trackRepository->findBy(['record' => $record]);

        if (empty($tracks)) {
            return new JsonResponse(['error' => 'No tracks found for this record'], Response::HTTP_NOT_FOUND);
        }

            $jsonTracks = [];
            foreach ($tracks as $track) {
                $jsonTracks[] = [
                    'id' => $track->getId(),
                    'artist' => $track->getArtist(),
                    'tracknumber' => $track->getTracknumber(),
                    'tracktitle' => $track->getTracktitle(),
                    'tracktime' => $track->getTracktime(),
                    'genre' => $track->getGenre(),
                    'listenlink' => $track->getListenlink(),
                    'record_id' => $track->getRecord()->getId(), // Assuming you need record ID
                ];
            }

        return new JsonResponse($jsonTracks, Response::HTTP_OK);
    }
}