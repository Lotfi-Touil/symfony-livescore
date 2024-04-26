<?php

namespace App\Repository;

use App\Entity\ScoreType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScoreType>
 *
 * @method ScoreType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScoreType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScoreType[]    findAll()
 * @method ScoreType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoreType::class);
    }

//    /**
//     * @return ScoreType[] Returns an array of ScoreType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ScoreType
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
