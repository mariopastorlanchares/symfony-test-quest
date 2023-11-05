<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }


    /**
     * Finds a random set of questions for a given category.
     *
     * @param Category $category The category entity
     * @param int $numberOfQuestionsPerCategory The number of random questions to retrieve
     * @return array An array of Question objects
     */
    public function findRandomByCategory(
        Category $category,
        int      $numberOfQuestionsPerCategory
    ): array
    {
        $query = $this->createQueryBuilder('q')
            ->where('q.category = :category')
            ->setParameter('category', $category)
            ->orderBy('RANDOM()')
            ->setMaxResults($numberOfQuestionsPerCategory)
            ->getQuery();

        return $query->getResult();
    }
}
