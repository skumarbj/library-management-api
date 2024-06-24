<?php

namespace App\Repository;

use App\Entity\Borrow;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @extends ServiceEntityRepository<Borrow>
 */
class BorrowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrow::class);
    }


    public function findByUserBookCheckInNull(int $userId, int $bookId): ?Borrow
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user_id = :user_val')
            ->andWhere('b.book_id = :book_val')
            ->andWhere('b.checkin_date IS NULL')
            ->setParameter('user_val', $userId)
            ->setParameter('book_val', $bookId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    public function getAllBorrowedBooks($entityManager): ?array
    {
        /*
        return $this->createQueryBuilder('bw.checkout_date', 'u.email', 'b.title')
            ->from('borrow', 'bw')
            //->join('user', 'u')
            //->join('book', 'b')
            ->innerJoin('user', 'u', Join::ON, 'bw.user_id = u.id')
            ->innerJoin('book', 'b', Join::ON, 'bw.book_id = b.id')
            //->andWhere('bw.user_id = u.id')
            //->andWhere('bw.book_id = b.id')
            ->andWhere('bw.checkin_date IS NULL')
            ->getQuery()
            ->getResult();
        */

        $sql = "
            SELECT 
                bw.id as borrow_id, bw.user_id, bw.book_id, bw.checkout_date, u.email, b.title
            FROM borrow as bw
            INNER JOIN user as u 
                    ON bw.user_id = u.id
            INNER JOIN book as b
                    ON bw.book_id = b.id
            WHERE 
                bw.checkin_date IS NULL
        ";

        //create the prepared statement, by getting the doctrine connection
        $stmt = $entityManager->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

}
