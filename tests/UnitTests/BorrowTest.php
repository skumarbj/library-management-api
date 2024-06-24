<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

use App\Entity\Borrow;
use App\Entity\Book;
use App\Entity\User;

class BorrowTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    private function clearTable(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('borrow', true));
        $connection->executeStatement($platform->getTruncateTableSQL('book', true));
        $connection->executeStatement($platform->getTruncateTableSQL('user', true));
    }

    protected function createTestUsers(): void
    {
        $user = new User();
        $user->setName('Admin');
        $user->setEmail('Admin@example.com');
        $user->setRole('ADMIN'); 
        $user->setPassword('Admin'); 
        $this->entityManager->persist($user);

        $user1 = new User();
        $user1->setName('User');
        $user1->setEmail('User@example.com');
        $user1->setRole('MEMBER'); 
        $user1->setPassword('User'); 
        $this->entityManager->persist($user1);

        $user2 = new User();
        $user2->setName('Reader');
        $user2->setEmail('Reader@example.com');
        $user2->setRole('MEMBER'); 
        $user2->setPassword('Reader'); 
        $this->entityManager->persist($user2);
    }

    protected function createTestBooks(): void
    {
        $book = new Book();
        $book->setTitle('ThiruKural');
        $book->setAuthor('ThiruValluvar');
        $book->setGenre('Treatise'); 
        $book->setIsbn('8796498734545'); 
        $book->setPublishedDate(new DateTime('1983-03-18')); 
        $book->setStatus(Book::STATUS_AVAILABLE); 
        $this->entityManager->persist($book);

        $book1 = new Book();
        $book1->setTitle('Symfony 5: The Fast Track');
        $book1->setAuthor('Fabien Potencier');
        $book1->setGenre('Programming Language'); 
        $book1->setIsbn('978-2918390374'); 
        $book1->setPublishedDate(new DateTime('2019-11-01')); 
        $book1->setStatus(Book::STATUS_AVAILABLE); 
        $this->entityManager->persist($book1);

        $book2 = new Book();
        $book2->setTitle('Python Crash Course, 3rd Edition: A Hands-On, Project-Based Introduction to Programming 3rd Edition');
        $book2->setAuthor('Eric Matthes');
        $book2->setGenre('Programming Language'); 
        $book2->setIsbn('978-1718502703'); 
        $book2->setPublishedDate(new DateTime('2023-01-10')); 
        $book2->setStatus(Book::STATUS_AVAILABLE); 
        $this->entityManager->persist($book2);
    }


    public function testBorrowBook(): void
    {
        $this->clearTable();
        $this->createTestUsers();
        $this->createTestBooks();
        $this->entityManager->flush();

        $userRepository = $this->entityManager->getRepository(User::class);
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $borrowRepository = $this->entityManager->getRepository(Borrow::class);

        $user = $userRepository->find(2);
        $book = $bookRepository->find(1);
        $checkoutDate = new DateTime('2024-06-20');

        $user->borrowBook($this->entityManager, $book, $checkoutDate);

        $bookFromDb = $bookRepository->find($book->getId());
        $borrowFromDb = $borrowRepository->findByUserBookCheckInNull($user->getId(), $book->getId());

        $this->assertNotNull($borrowFromDb);
        $this->assertEquals(Book::STATUS_BORROWED, $bookFromDb->getStatus());
    }


    public function testReturnBook(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $borrowRepository = $this->entityManager->getRepository(Borrow::class);

        $user = $userRepository->find(2);
        $book = $bookRepository->find(1);
        $checkinDate = new DateTime('2024-06-21');

        $borrow = $borrowRepository->findByUserBookCheckInNull($user->getId(), $book->getId());

        $book->returnBook($this->entityManager, $borrow, $checkinDate);

        $bookFromDb = $bookRepository->find($book->getId());
        $borrowFromDb = $borrowRepository->find($borrow->getId());

        $this->assertNotNull($borrowFromDb->getCheckinDate());
        $this->assertEquals(Book::STATUS_AVAILABLE, $bookFromDb->getStatus());
    }
}
