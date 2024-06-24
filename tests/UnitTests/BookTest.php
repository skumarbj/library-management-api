<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

use App\Entity\Book;

class BookTest extends KernelTestCase
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
        $connection->executeStatement($platform->getTruncateTableSQL('book', true));
    }

    public function testCreateBook(): void
    {
        $this->clearTable();

        $book = new Book();
        $book->setTitle('ThiruKural');
        $book->setAuthor('ThiruValluvar');
        $book->setGenre('Treatise'); 
        $book->setIsbn('8796498734545'); 
        $book->setPublishedDate(new DateTime('1983-03-18')); 
        $book->setStatus(Book::STATUS_AVAILABLE); 

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $bookRepository = $this->entityManager->getRepository(Book::class);
        $bookFromDb = $bookRepository->findOneBy(['title' => 'ThiruKural']);

        $this->assertNotNull($bookFromDb);
        $this->assertEquals('ThiruValluvar', $bookFromDb->getAuthor());
    }

    public function testReadBook(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['author' => 'ThiruValluvar']);

        $this->assertNotNull($book);
        $this->assertEquals('ThiruKural', $book->getTitle());
    }

    public function testUpdateUser(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['title' => 'ThiruKural']);
        $book->setStatus(Book::STATUS_BORROWED); 

        $this->entityManager->flush();

        $updatedBook = $bookRepository->findOneBy(['title' => 'ThiruKural']);
        $this->assertNotNull($updatedBook);
        $this->assertEquals(Book::STATUS_BORROWED, $updatedBook->getStatus());
    }

    public function testDeleteBook(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['title' => 'ThiruKural']);

        $book->setDeletedDate(new DateTime("now"));
        $this->entityManager->flush();

        $deletedBook = $bookRepository->findOneBy(['title' => 'ThiruKural']);
        $this->assertNotNull($deletedBook->getDeletedDate());
    }

}
