<?php

namespace App\Repository;

use App\Entity\VideoGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoGame>
 */
class VideoGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoGame::class);
    }

    public function findAllWithPagiante($page, $limit)
    {
        $request = $this->createQueryBuilder('b')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        return $request->getQuery()->getResult();
    }

    public function findGamesReleasingInNext7Days(): array
    {
        $now = new \DateTime();
        $endDate = new \DateTime('+7 days');

        return $this->createQueryBuilder('v')
            ->where('v.releaseDate BETWEEN :now AND :endDate')
            ->setParameter('now', $now)
            ->setParameter('endDate', $endDate)
            ->orderBy('v.releaseDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return VideoGame[] Returns an array of VideoGame objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VideoGame
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
