<?php

namespace App\Repository;

use App\Entity\OrderAssociated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderAssociated>
 *
 * @method OrderAssociated|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderAssociated|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderAssociated[]    findAll()
 * @method OrderAssociated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderAssociatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderAssociated::class);
    }

//    /**
//     * @return OrderAssociated[] Returns an array of OrderAssociated objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrderAssociated
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
