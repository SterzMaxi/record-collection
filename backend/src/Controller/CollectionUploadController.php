<?php
namespace App\Controller;

use App\Entity\Collection;
use App\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class CollectionUploadController extends AbstractController
{

    #[Route('/api/allcollections', name: 'api_get_all_collections', methods: ['GET'])]
    public function getAllCollections(CollectionRepository $collectionRepository): JsonResponse
    {
        // Fetch all collections from the database
        $collections = $collectionRepository->findAll();

        // Manually construct the response array
        $responseData = [];
        foreach ($collections as $collection) {
            $recordsData = [];
            foreach ($collection->getRecords() as $record) {
                $recordsData[] = [
                    'id' => $record->getId(),
                    'title' => $record->getTitle(), // Adjust according to your Record entity
                    'artist' => $record->getArtist(), // Adjust according to your Record entity
                    // Add more fields as necessary
                ];
            }

            // Get the username of the user who owns the collection
            $user = $collection->getUser();

            $responseData[] = [
                'id' => $collection->getId(),
                'collectionname' => $collection->getCollectionName(),
                'style' => $collection->getStyle(),
                'created_at' => $collection->getCreated()->format('Y-m-d H:i:s'),
                'updated_at' => $collection->getUpdated()->format('Y-m-d H:i:s'),
                'username' => $user->getEmail(),
                'records' => $recordsData,
            ];
        }

        // Return the manually constructed response as JSON
        return new JsonResponse($responseData);
    }


    #[Route('/api/collection', name: 'api_create_collection', methods: ['POST'])]
    public function createCollection(Request $request, EntityManagerInterface $em, #[CurrentUser] $user): Response
    {
        $collectionname = $request->request->get('collectionname');
        $style = $request->request->get('style');

        $collection = new Collection($user, $collectionname, $style);

        $em->persist($collection);
        $em->flush();

        return new Response('Collection created successfully', Response::HTTP_CREATED);
    }

    #[Route('/api/collections', name: 'api_get_collections', methods: ['GET'])]
    public function getCollections(CollectionRepository $collectionRepository, #[CurrentUser] $user): JsonResponse
    {
        $collections = $collectionRepository->findBy(['user' => $user]);

        $data = [];
        foreach ($collections as $collection) {
            $data[] = [
                'id' => $collection->getId(),
                'collectionname' => $collection->getCollectionName(),
                'style' => $collection->getStyle(),
                'created_at' => $collection->getCreated()->format('Y-m-d H:i:s'),
                'updated_at' => $collection->getUpdated()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/collection/{id}', name: 'api_delete_collection', methods: ['DELETE'])]
    public function deleteCollection(Collection $collection, EntityManagerInterface $em, #[CurrentUser] $user): JsonResponse
    {
        if ($collection->getUser() !== $user) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Get the directory where the images are stored
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

        // Get all records associated with the collection
        $records = $collection->getRecords();

        // Loop through each record and delete the associated images
        foreach ($records as $record) {
            // Get the filenames of the images
            $bookletFrontFilename = $record->getBookletFront();
            $bookletBackFilename = $record->getBookletBack();

            // Construct the full paths to the images
            $bookletFrontPath = $uploadDir . '/' . $bookletFrontFilename;
            $bookletBackPath = $uploadDir . '/' . $bookletBackFilename;

            // Check if the files exist and delete them
            if ($bookletFrontFilename && file_exists($bookletFrontPath)) {
                unlink($bookletFrontPath);
            }

            if ($bookletBackFilename && file_exists($bookletBackPath)) {
                unlink($bookletBackPath);
            }

            // Remove the record itself
            $em->remove($record);
        }

        // Finally, remove the collection
        $em->remove($collection);
        $em->flush();

        return new JsonResponse(['message' => 'Collection and associated records deleted successfully'], Response::HTTP_OK);
    }
}