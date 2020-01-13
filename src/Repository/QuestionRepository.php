<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Provides access to the collection of Question entities.
 */
class QuestionRepository extends ServiceEntityRepository
{
    /**
     * QuestionRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
     public function __construct(ManagerRegistry $registry)
     {
         parent::__construct($registry, Question::class);
     }

    /**
     * Returns a page. Note: Fixed sorting for now of question text.
     *
     * @param int $limit   The most amount of Question entities to return.
     * @param int $offset  Skip this amount of records.
     *
     * @return Question[]
     */
     public function getPage($limit, $offset): array
     {
         $query = $this->getEntityManager()->createQuery(
             'SELECT q FROM App\Entity\Question q LEFT JOIN q.answers a ' .
             'ORDER BY q.question ASC, a.rank ASC'
         )
             ->setMaxResults($limit)
             ->setFirstResult($offset);

         $paginator = new Paginator($query, true);
         $elements = iterator_to_array($paginator->getIterator());

         return $elements;
     }

    /**
     * @param Question $question
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
     public function save(Question $question): void
     {
        $this->getEntityManager()->persist($question);
        $this->getEntityManager()->flush();
     }
}
