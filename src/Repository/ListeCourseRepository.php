<?php

namespace App\Repository;

use App\Entity\ListeCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeCourse>
 *
 * @method ListeCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeCourse[]    findAll()
 * @method ListeCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeCourse::class);
    }
    public function findByUserId($userId)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :val')
            ->setParameter('val', $userId)
            ->getQuery()
            ->getResult()
            ;
    }
    public function getStatisticsForUser($userId): array
    {
        $qb = $this->createQueryBuilder('lc');

        // Sous-requête pour obtenir l'article associé au prix minimum
        $minPriceArticleSubquery = $this->getEntityManager()->createQueryBuilder()
            ->select('MIN(articleMin.nomArticle)')
            ->from('App\Entity\ArticlePrevu', 'apMin')
            ->leftJoin('apMin.article', 'articleMin')
            ->leftJoin('apMin.listeCourse', 'lcMin')
            ->andWhere('lcMin.user = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('articleMin.prix = (SELECT MIN(article2.prix) FROM App\Entity\ArticlePrevu ap2 JOIN ap2.article article2)');

        // Sous-requête pour obtenir l'article associé au prix maximum
        $maxPriceArticleSubquery = $this->getEntityManager()->createQueryBuilder()
            ->select('MAX(articleMax.nomArticle)')
            ->from('App\Entity\ArticlePrevu', 'apMax')
            ->leftJoin('apMax.article', 'articleMax')
            ->leftJoin('apMax.listeCourse', 'lcMax')
            ->andWhere('lcMax.user = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('articleMax.prix = (SELECT MAX(article3.prix) FROM App\Entity\ArticlePrevu ap3 JOIN ap3.article article3)');

        $qb->select('SUM(article.prix * ap.quantite) as totalSpent')
            ->addSelect('COUNT(lc.id) as numLists')
            ->addSelect('MIN(article.prix) as minPrice')
            ->addSelect('MAX(article.prix) as maxPrice')
            ->addSelect('AVG(article.prix * ap.quantite) as averageSpent')
            ->addSelect('(' . $minPriceArticleSubquery->getDQL() . ') as minArticleName')
            ->addSelect('(' . $maxPriceArticleSubquery->getDQL() . ') as maxArticleName')
            ->addSelect('typeArticle.nomType as typeName')
            ->leftJoin('lc.ArticlesPrevus', 'ap')
            ->leftJoin('ap.article', 'article')
            ->leftJoin('article.Type', 'typeArticle')
            ->andWhere('lc.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('typeArticle.nomType');

        $result = $qb->getQuery()->getArrayResult();

        // Si aucun résultat n'est retourné, initialisez les statistiques à zéro
        if (empty($result)) {
            return [
                'totalSpent' => 0,
                'numLists' => 0,
                'minPrice' => 0,
                'minArticleName' => null,
                'maxPrice' => 0,
                'maxArticleName' => null,
                'averageSpent' => 0,
                'foodTypeCounts' => [],
            ];
        }

        // Initialisation des valeurs par défaut
        $totalSpent = 0;
        $numLists = 0;
        $minPrice = PHP_INT_MAX;
        $maxPrice = PHP_INT_MIN;
        $averageSpent = 0;
        $foodTypeCounts = [];

        foreach ($result as $row) {
            $totalSpent += $row['totalSpent'];
            $numLists += $row['numLists'];
            if ($row['minPrice'] == 0) {
                $row['minPrice'] = PHP_INT_MAX;
            }
            $minPrice = min($minPrice, $row['minPrice']);
            $maxPrice = max($maxPrice, $row['maxPrice']);
            $averageSpent += $row['totalSpent'];
            $foodTypeCounts[$row['typeName']] = $row['totalSpent'];
        }

        if ( $totalSpent > 0 ) {
            foreach ($foodTypeCounts as $typeName => $spentAmount) {
                $foodTypeCounts[$typeName] = number_format(($spentAmount / $totalSpent) * 100,2);
            }
        }

        // Calcul de la moyenne
        if ($numLists > 0) {
            $averageSpent /= $numLists;
        }

        //Calcul du prix max
        if ($maxPrice == PHP_INT_MIN) {
            $maxPrice = 0;
        }

        return [
            'totalSpent' => number_format($totalSpent,2),
            'numLists' => $numLists,
            'minPrice' => number_format($minPrice,2),
            'minArticleName' => $result[0]['minArticleName'],
            'maxPrice' => number_format($maxPrice,2),
            'maxArticleName' => $result[0]['maxArticleName'],
            'averageSpent' => number_format($averageSpent,2),
            'foodTypeCounts' => $foodTypeCounts,
        ];
    }
    //    /**
    //     * @return ListeCourse[] Returns an array of ListeCourse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ListeCourse
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
