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

class CollectionUploadController extends AbstractController
{
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
                'created_at' => $collection->getCreated(),
                'updated_at' => $collection->getUpdated(),
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

        $em->remove($collection);
        $em->flush();

        return new JsonResponse(['message' => 'Collection deleted successfully'], Response::HTTP_OK);
    }
}