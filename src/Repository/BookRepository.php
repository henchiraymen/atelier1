<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchBookByRef($ref){
        $req = $this->createQueryBuilder('b')
        ->where('b.ref like :ref')
        ->setParameter(':ref', '%'.$ref.'%')
        ->getQuery();
        return $req->getResult();
    }
    function BooksByAuthor(){
        $req=$this->createQueryBuilder('b')
        ->join('b.author','a')
        ->addSelect('a')
        ->orderBy('a.username', 'ASC')->getQuery();
        return $req->getResult();
    }
    public function BooksByDateNb(){
        $req=$this->createQueryBuilder('b')
        ->join('b.author','a')
        ->addSelect('a')
        ->where('b.publicationDate < ?1')
        ->andWhere('a.nbBooks > ?2')
        ->setParameter(1,date('2023-01-01'))
        ->setParameter(2, 4)
        ->getQuery();
        return $req->getResult();
    }

    public function BookByDateDQL(){
        $em = $this->getEntityManager();
        $req=$em->createQuery('SELECT b FROM App\Entity\Book b WHERE b.publicationDate between :d1 and :d2')
        ->setParameters(['d1'=>'2021-01-01', 'd2'=>'2025-01-01']);
        return $req->getResult();
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
