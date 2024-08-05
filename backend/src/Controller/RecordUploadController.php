<?php
namespace App\Controller;

use App\Entity\Record;
use App\Entity\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Mime\MimeTypes;

class RecordUploadController extends AbstractController
{
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

        $price = (float)$price;

        error_log('Price value: ' . $price);
        error_log('Price type: ' . gettype($price));


        $record = new Record(
            $collection,
            $title,
            $artist,
            $format,
            (int)$trackcount,
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

        return new Response('Record uploaded successfully', Response::HTTP_CREATED);
    }

    #[Route('/api/records', name: 'api_get_records', methods: ['GET'])]
    public function getRecords(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $records = $em->getRepository(Record::class)->findAll();
        $jsonRecords = $serializer->serialize($records, 'json');
        
        return new Response($jsonRecords, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/api/record/{id}', name: 'api_get_record', methods: ['GET'])]
    public function getRecord(int $id, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $record = $em->getRepository(Record::class)->find($id);

        if (!$record) {
            return new Response('Record not found', Response::HTTP_NOT_FOUND);
        }

        $jsonRecord = $serializer->serialize($record, 'json');

        return new Response($jsonRecord, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/api/record/{id}', name: 'api_delete_record', methods: ['DELETE'])]
    public function deleteRecord(int $id, EntityManagerInterface $em): Response
    {
        $record = $em->getRepository(Record::class)->find($id);

        if (!$record) {
            return new Response('Record not found', Response::HTTP_NOT_FOUND);
        }

        $em->remove($record);
        $em->flush();

        return new Response('Record deleted successfully', Response::HTTP_NO_CONTENT);
    }
}

