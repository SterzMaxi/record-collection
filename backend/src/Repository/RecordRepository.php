<?php

namespace App\Repository;

use App\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    /**
     * Search records based on a query string that matches various fields
     * 
     * @param string $query The search query string
     * @return Record[] Returns an array of Record objects
     */
    public function searchRecords(string $query): array
    {
        // Create a QueryBuilder instance for the Record entity
        $qb = $this->createQueryBuilder('r');
        
        // Define search criteria on various fields
        $qb->where('r.title LIKE :query')
            ->orWhere('r.artist LIKE :query')
            ->orWhere('r.trackTitle LIKE :query')
            ->orWhere('r.label LIKE :query')
            ->setParameter('query', '%' . $query . '%');

        // If records have associated collections or other entities, you can join and search those as well
        // Example: $qb->leftJoin('r.collection', 'c')
        //          ->orWhere('c.title LIKE :query'); // Adjust based on your actual relationships and fields

        // Get the results of the query
        return $qb->getQuery()->getResult();
    }
}