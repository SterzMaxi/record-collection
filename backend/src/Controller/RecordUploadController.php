<?php
namespace App\Controller;

use App\Entity\Record;
use App\Entity\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class RecordUploadController extends AbstractController
{

    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $_ENV['APP_BASE_URL'];
    }

    #[Route('/api/record', name: 'api_create_record', methods: ['POST'])]
    public function createRecord(Request $request, EntityManagerInterface $em, #[CurrentUser] $user): Response
    {
        $collectionId = $request->request->get('collection_id');
        if (!$collectionId) {
            return new Response('Collection ID is missing', Response::HTTP_BAD_REQUEST);
        }

        $collection = $em->getRepository(Collection::class)->find($collectionId);
        if (!$collection) {
            return new Response('Collection not found', Response::HTTP_NOT_FOUND);
        }
        if (!$collection) {
            return new Response('Collection not found', Response::HTTP_BAD_REQUEST);
        }
        $title = $request->request->get('title');
        $artist = $request->request->get('artist');
        $format = $request->request->get('format');
        $trackcount = $request->request->get('trackcount');
        $label = $request->request->get('label');
        $country = $request->request->get('country');
        $releasedate = $request->request->get('releasedate');
        $genre = $request->request->get('genre');

        $price = $request->request->get('price');

        $listenlink = $request->request->get('listenlink');
        $grade = $request->request->get('grade');

        // Handle file uploads
        $bookletfront = $request->files->get('bookletfront');
        $bookletback = $request->files->get('bookletback');

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

        $bookletfrontFilename = null;
        $bookletbackFilename = null;

        if ($bookletfront) {
            $mimeTypes = new MimeTypes();
            $extension = $mimeTypes->getExtensions($bookletfront->getMimeType())[0];
            $bookletfrontFilename = md5(uniqid()) . '.' . $extension;
            $bookletfront->move($uploadDir, $bookletfrontFilename);
        }

        if ($bookletback) {
            $mimeTypes = new MimeTypes();
            $extension = $mimeTypes->getExtensions($bookletback->getMimeType())[0];
            $bookletbackFilename = md5(uniqid()) . '.' . $extension;
            $bookletback->move($uploadDir, $bookletbackFilename);
        }

        $releasedate = $request->request->get('releasedate');

        // Check if releasedate is present and not empty
        if (!$releasedate) {
            return new Response('Release date is missing', Response::HTTP_BAD_REQUEST);
        }

        // Attempt to create DateTime object
        try {
            $formattedDate = new \DateTime($releasedate);
        } catch (\Exception $e) {
            return new Response('Invalid date format', Response::HTTP_BAD_REQUEST);
        }

        $price = (float) $price;

        error_log('Price value: ' . $price);
        error_log('Price type: ' . gettype($price));


        $record = new Record(
            $collection,
            $title,
            $artist,
            $format,
            (int) $trackcount,
            $label,
            $country,
            $formattedDate,
            $genre,
            $price,
            $bookletfrontFilename,
            $bookletbackFilename,
            $grade
        );

        $em->persist($record);
        $em->flush();

        $responseData = [
            'message' => 'Record uploaded successfully',
            'id' => $record->getId()
        ];

        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }

    #[Route('/api/collection/{collectionId}/records', name: 'api_get_records_by_collection', methods: ['GET'])]
    public function getRecordsByCollection(int $collectionId, EntityManagerInterface $em): Response
    {
        $collection = $em->getRepository(Collection::class)->find($collectionId);

        if (!$collection) {
            return new JsonResponse(['error' => 'Collection not found'], Response::HTTP_NOT_FOUND);
        }

        $records = $em->getRepository(Record::class)->findBy(['collection' => $collection]);

        $jsonRecords = [];
        foreach ($records as $record) {
            $jsonRecords[] = [
                'id' => $record->getId(),
                'title' => $record->getTitle(),
                'artist' => $record->getArtist(),
                'format' => $record->getFormat(),
                'trackcount' => $record->getTrackCount(),
                'label' => $record->getLabel(),
                'country' => $record->getCountry(),
                'releasedate' => $record->getReleaseDate()->format('Y-m-d'),
                'genre' => $record->getGenre(),
                'price' => $record->getPrice(),
                'bookletfront' => $record->getBookletFront() ? $this->baseUrl . '/uploads/' . $record->getBookletFront() : null,
                'bookletback' => $record->getBookletBack() ? $this->baseUrl . '/uploads/' . $record->getBookletBack() : null,
                'grade' => $record->getGrade(),
                'userId' => $record->getCollection()->getUser()->getId(),
            ];
        }

        return new JsonResponse($jsonRecords, Response::HTTP_OK);
    }

    #[Route('/api/collection/{collectionId}/record/{recordId}', name: 'api_get_record', methods: ['GET'])]
    public function getRecord(int $collectionId, int $recordId, EntityManagerInterface $em): Response
    {
        $record = $em->getRepository(Record::class)->findOneBy([
            'collection' => $collectionId,
            'id' => $recordId
        ]);

        if (!$record) {
            return new Response('Record not found', Response::HTTP_NOT_FOUND);
        }

        $jsonRecord = [
            'id' => $record->getId(),
            'title' => $record->getTitle(),
            'artist' => $record->getArtist(),
            'format' => $record->getFormat(),
            'trackcount' => $record->getTrackCount(),
            'label' => $record->getLabel(),
            'country' => $record->getCountry(),
            'releasedate' => $record->getReleaseDate()->format('Y-m-d'),
            'genre' => $record->getGenre(),
            'price' => $record->getPrice(),
            'bookletfront' => $record->getBookletFront() ? $this->baseUrl . '/uploads/' . $record->getBookletFront() : null,
            'bookletback' => $record->getBookletBack() ? $this->baseUrl . '/uploads/' . $record->getBookletBack() : null,
            'grade' => $record->getGrade(),
            'userId' => $record->getCollection()->getUser()->getId(),
        ];

        return new JsonResponse($jsonRecord, Response::HTTP_OK);
    }

    #[Route('/api/record/{id}', name: 'api_update_record', methods: ['PUT'])]
    public function updateRecord(int $id, Request $request, EntityManagerInterface $em, #[CurrentUser] $user): Response
    {
        // Find the existing record by ID
        $record = $em->getRepository(Record::class)->find($id);

        if (!$record) {
            return new Response('Record not found', Response::HTTP_NOT_FOUND);
        }

        // Check if the current user is authorized to edit the record
        if ($record->getCollection()->getUser()->getId() !== $user->getId()) {
            return new Response('Unauthorized', Response::HTTP_FORBIDDEN);
        }

        // Update record fields with the new data
        $collectionId = $request->request->get('collection_id');
        $title = $request->request->get('title');
        $artist = $request->request->get('artist');
        $format = $request->request->get('format');
        $trackcount = $request->request->get('trackcount');
        $label = $request->request->get('label');
        $country = $request->request->get('country');
        $releasedate = $request->request->get('releasedate');
        $genre = $request->request->get('genre');
        $price = $request->request->get('price');
        $grade = $request->request->get('grade');

        // Handle file uploads
        $bookletfront = $request->files->get('bookletfront');
        $bookletback = $request->files->get('bookletback');

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

        if ($bookletfront) {
            // Remove old file if it exists
            $oldBookletFront = $record->getBookletFront();
            if ($oldBookletFront && file_exists($uploadDir . '/' . $oldBookletFront)) {
                unlink($uploadDir . '/' . $oldBookletFront);
            }

            $mimeTypes = new MimeTypes();
            $extension = $mimeTypes->getExtensions($bookletfront->getMimeType())[0];
            $bookletfrontFilename = md5(uniqid()) . '.' . $extension;
            $bookletfront->move($uploadDir, $bookletfrontFilename);
            $record->setBookletFront($bookletfrontFilename);
        }

        if ($bookletback) {
            // Remove old file if it exists
            $oldBookletBack = $record->getBookletBack();
            if ($oldBookletBack && file_exists($uploadDir . '/' . $oldBookletBack)) {
                unlink($uploadDir . '/' . $oldBookletBack);
            }

            $mimeTypes = new MimeTypes();
            $extension = $mimeTypes->getExtensions($bookletback->getMimeType())[0];
            $bookletbackFilename = md5(uniqid()) . '.' . $extension;
            $bookletback->move($uploadDir, $bookletbackFilename);
            $record->setBookletBack($bookletbackFilename);
        }

        // Check if releasedate is present and not empty
        if ($releasedate) {
            try {
                $formattedDate = new \DateTime($releasedate);
                $record->setReleaseDate($formattedDate);
            } catch (\Exception $e) {
                return new Response('Invalid date format', Response::HTTP_BAD_REQUEST);
            }
        }

        $price = (float) $price;

        // Update record fields
        if ($title)
            $record->setTitle($title);
        if ($artist)
            $record->setArtist($artist);
        if ($format)
            $record->setFormat($format);
        if ($trackcount !== null)
            $record->setTrackCount((int) $trackcount);
        if ($label)
            $record->setLabel($label);
        if ($country)
            $record->setCountry($country);
        if ($genre)
            $record->setGenre($genre);
        if ($price !== null)
            $record->setPrice($price);
        if ($grade)
            $record->setGrade($grade);

        $em->flush();

        $responseData = [
            'message' => 'Record updated successfully',
            'id' => $record->getId()
        ];

        return new JsonResponse($responseData, Response::HTTP_OK);
    }

    #[Route('/api/record/{id}', name: 'api_delete_record', methods: ['DELETE'])]
    public function deleteRecord(int $id, EntityManagerInterface $em, #[CurrentUser] $user): Response
    {
        $record = $em->getRepository(Record::class)->find($id);

        if (!$record) {
            return new Response('Record not found', Response::HTTP_NOT_FOUND);
        }

        if ($record->getCollection()->getUser()->getId() !== $user->getId()) {
            return new Response('Unauthorized', Response::HTTP_FORBIDDEN);
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

        $bookletFrontFilename = $record->getBookletFront();
        $bookletBackFilename = $record->getBookletBack();

        $bookletFrontPath = $uploadDir . '/' . $bookletFrontFilename;
        $bookletBackPath = $uploadDir . '/' . $bookletBackFilename;

        if ($bookletFrontFilename && file_exists($bookletFrontPath)) {
            unlink($bookletFrontPath);
        }

        if ($bookletBackFilename && file_exists($bookletBackPath)) {
            unlink($bookletBackPath);
        }

        $em->remove($record);
        $em->flush();

        return new Response('Record deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

