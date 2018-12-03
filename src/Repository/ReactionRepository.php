<?php

namespace App\Repository;

use App\Entity\Reaction;
use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reaction[]    findAll()
 * @method Reaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reaction::class);
    }

    public function countReactions(int $sessionId): array
    {
        $rows = $this
            ->createQueryBuilder('r')
            ->select('r.type, COUNT(r.id) as nb')
            ->where('r.session = :session_id')
            ->setParameter('session_id', $sessionId)
            ->groupBy('r.type')
            ->getQuery()
            ->getArrayResult();

        $data = [];
        foreach ($rows as $row) {
            $data[$row['type']] = (int) $row['nb'];
        }

        return $data;
    }

    // /**
    //  * @return Reaction[] Returns an array of Reaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reaction
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
