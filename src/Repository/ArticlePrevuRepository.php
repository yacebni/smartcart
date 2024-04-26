<?php

namespace App\Repository;

use App\Entity\ArticlePrevu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticlePrevu>
 *
 * @method ArticlePrevu|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticlePrevu|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticlePrevu[]    findAll()
 * @method ArticlePrevu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlePrevuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticlePrevu::class);
    }

    //    /**
    //     * @return ArticlePrevu[] Returns an array of ArticlePrevu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ArticlePrevu
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
