<?php

namespace App\Repository;

use App\Entity\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collection::class);
    }

    /**
     * Search collections based on a query string that matches various fields
     * 
     * @param string $query The search query string
     * @return Collection[] Returns an array of Collection objects
     */
    public function searchCollections(string $query): array
    {
        // Create QueryBuilder instance for Collection entity
        $qb = $this->createQueryBuilder('c');
        
        // Define search criteria on various fields
        $qb->where('c.collectionname LIKE :query')
            ->orWhere('c.style LIKE :query')
            ->setParameter('query', '%' . $query . '%');

        // If collections have associated records with titles, you can join and search those as well
        $qb->leftJoin('c.records', 'r')
            ->orWhere('r.title LIKE :query')
            ->orWhere('r.artist LIKE :query')
            ->orWhere('r.label LIKE :query')
            ->orWhere('r.country LIKE :query')
            ->orWhere('r.genre LIKE :query');

        // Get the results of the query
        return $qb->getQuery()->getResult();
    }
}