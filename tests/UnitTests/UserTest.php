<?php
declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class UserTest extends KernelTestCase
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
        $connection->executeStatement($platform->getTruncateTableSQL('user', true));
    }

    public function testCreateUser(): void
    {
        $this->clearTable();

        $user = new User();
        $user->setName('User');
        $user->setEmail('User@example.com');
        $user->setRole('MEMBER'); 
        $user->setPassword('User'); 

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (PDOException $e) {

            $this->expectException(UniqueConstraintViolationException::class);
            $this->expectExceptionMessage('Error: Email duplicated');
        }
        $userRepository = $this->entityManager->getRepository(User::class);
        $userFromDb = $userRepository->findOneBy(['email' => 'User@example.com']);

        $this->assertNotNull($userFromDb);
        $this->assertEquals('User@example.com', $userFromDb->getEmail());
    }

    public function testReadUser(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'User@example.com']);

        $this->assertNotNull($user);
        $this->assertEquals('User@example.com', $user->getEmail());
    }

    public function testUpdateUser(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'User@example.com']);
        $user->setEmail('Userupdated@example.com');

        $this->entityManager->flush();

        $updatedUser = $userRepository->findOneBy(['email' => 'Userupdated@example.com']);
        $this->assertNotNull($updatedUser);
        $this->assertEquals('Userupdated@example.com', $updatedUser->getEmail());
    }

    public function testDeleteUser(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'Userupdated@example.com']);

        $user->setDeletedDate(new DateTime("now"));
        $this->entityManager->flush();

        $deletedUser = $userRepository->findOneBy(['email' => 'Userupdated@example.com']);
        $this->assertNotNull($deletedUser->getDeletedDate());
    }

}
