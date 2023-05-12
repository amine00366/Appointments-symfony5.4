<?php

namespace App\Repository;

use App\Entity\Typeappoinment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typeappoinment>
 *
 * @method Typeappoinment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeappoinment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeappoinment[]    findAll()
 * @method Typeappoinment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeappoinmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeappoinment::class);
    }

    public function save(Typeappoinment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Typeappoinment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
//tri des type 
    public function SortBynomtype(){
        return $this->createQueryBuilder('e')
            ->orderBy('e.nomtype','ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findBynomtype( $nomtype)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.nomtype LIKE :nomtype')
        ->setParameter('nomtype','%' .$nomtype. '%')
        ->getQuery()
        ->execute();
}
  

//    /**
//     * @return Typeappoinment[] Returns an array of Typeappoinment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typeappoinment
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



public function findEntitiesByString($str){
    return $this->getEntityManager()
        ->createQuery(
            'SELECT p
            FROM App:Typeappoinment p
            WHERE p.nomtype LIKE :str'
            
        )
        ->setParameter('str', '%'.$str.'%')
        ->getResult();
}
}
