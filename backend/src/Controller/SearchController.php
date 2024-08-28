<?php

namespace App\Controller;

use App\Repository\CollectionRepository;
use App\Repository\RecordRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    private $collectionRepository;
    private $recordRepository;

    public function __construct(CollectionRepository $collectionRepository, RecordRepository $recordRepository)
    {
        $this->collectionRepository = $collectionRepository;
        $this->recordRepository = $recordRepository;
    }

    /**
     * @Route("/api/search", name="api_search", methods={"GET"})
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('query');

        if (!$query) {
            return new JsonResponse(['message' => 'Query parameter is missing'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Perform search on collections and records based on query
        $collectionResults = $this->collectionRepository->searchCollections($query);
        $recordResults = $this->recordRepository->searchRecords($query);

        // Combine results if needed
        $results = [
            'collections' => $collectionResults,
            'records' => $recordResults,
        ];

        return new JsonResponse($results);
    }
}
