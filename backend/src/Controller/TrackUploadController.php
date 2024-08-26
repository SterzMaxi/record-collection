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
    public function createTrack(Request $request, EntityManagerInterface $em, #[CurrentUser] $user): Response
    {
        $recordId = $request->request->get('record_id');
        if (!$recordId) {
            return new Response('Record ID is missing', Response::HTTP_BAD_REQUEST);
        }

        $record = $em->getRepository(Record::class)->find($recordId);
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

        $em->persist($track);
        $em->flush();

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
                'record_id' => $track->getRecord()->getId(),
            ];
        }

        return new JsonResponse($jsonTracks, Response::HTTP_OK);
    }

    #[Route('/api/collection/{collectionId}/record/{recordId}/track/{trackId}', name: 'api_get_track_of_record_in_collection', methods: ['GET'])]
    public function getTrack(int $trackId, TrackRepository $trackRepository): Response
    {
        $track = $trackRepository->find($trackId);

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
            'record_id' => $track->getRecord()->getId(),
        ];

        return new JsonResponse($jsonTrack, Response::HTTP_OK);
    }

    #[Route('/api/track/{id}', name: 'api_delete_track', methods: ['DELETE'])]
    public function deleteTrack(int $id, TrackRepository $trackRepository, EntityManagerInterface $em): Response
    {
        $track = $trackRepository->find($id);

        if (!$track) {
            return new JsonResponse(['error' => 'Track not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($track);
        $em->flush();

        return new Response('Track deleted successfully', Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/collection/{collectionId}/record/{recordId}/tracks', name: 'api_get_tracks_for_record', methods: ['GET'])]
    public function getTracksForRecord(int $collectionId, int $recordId, TrackRepository $trackRepository, EntityManagerInterface $em): Response
    {
        $record = $em->getRepository(Record::class)->findOneBy([
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
                    'record_id' => $track->getRecord()->getId(),
                ];
            }

        return new JsonResponse($jsonTracks, Response::HTTP_OK);
    }

    #[Route('/api/collection/{collectionId}/record/{recordId}/track/{trackId}', name: 'api_update_track_for_record_in_collection', methods: ['PUT'])]
public function updateTrackForRecordInCollection(int $collectionId, int $recordId, int $trackId, Request $request, EntityManagerInterface $em, #[CurrentUser] $user): Response
{
    // Fetch the record that belongs to the specific collection
    $record = $em->getRepository(Record::class)->findOneBy([
        'id' => $recordId,
        'collection' => $collectionId,
    ]);

    if (!$record) {
        return new JsonResponse(['error' => 'Record not found in the specified collection'], Response::HTTP_NOT_FOUND);
    }

    // Fetch the track within the specified record
    $track = $em->getRepository(Track::class)->findOneBy([
        'id' => $trackId,
        'record' => $record
    ]);

    if (!$track) {
        return new JsonResponse(['error' => 'Track not found in the specified record'], Response::HTTP_NOT_FOUND);
    }

    // Decode the JSON payload
    $data = json_decode($request->getContent(), true);

    // Validate that all required fields are present in the JSON data
    if (!isset($data['artist'], $data['tracknumber'], $data['tracktitle'], $data['tracktime'], $data['genre'], $data['listenlink'])) {
        return new JsonResponse(['error' => 'Missing track data'], Response::HTTP_BAD_REQUEST);
    }

    // Update track details
    $track->setArtist($data['artist']);
    $track->setTracknumber($data['tracknumber']);
    $track->setTracktitle($data['tracktitle']);
    $track->setTracktime($data['tracktime']);
    $track->setGenre($data['genre']);
    $track->setListenlink($data['listenlink']);

    $em->flush();

    return new JsonResponse(['message' => 'Track updated successfully'], Response::HTTP_OK);
}


}
