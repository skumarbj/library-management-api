<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;
use App\Entity\Borrow;
use DateTime;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Types;
// use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]

// class User implements PasswordAuthenticatedUserInterface
class User
{
    const ROLE_VALUES = ['ADMIN', 'MEMBER'];
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_MEMBER = 'MEMBER';

    /**
     * @OA\Property(
     *     description="ID of the user",
     *     example=1
     * )
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @OA\Property(
     *     description="Name of the user",
     *     example="Name LastName"
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @OA\Property(
     *     description="Email of the user",
     *     example="user@example.com"
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @OA\Property(
     *     description="Role of the user",
     *     example="ADMIN | MEMBER"
     * )
     */
    #[ORM\Column(type: "string", columnDefinition: "ENUM('ADMIN', 'MEMBER')")]
    private ?string $role = null;

    /**
     * @OA\Property(
     *     description="user deleted with deleted date",
     *     example="2024-02-27"
     * )
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $deleted_date = null;

    /**
     * @OA\Property(
     *     description="Password of the user",
     *     example="password"
     * )
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        if (!in_array($role, self::ROLE_VALUES)) {
            throw new \InvalidArgumentException("Invalid role");
        }
        $this->role = $role;
        return $this;
    }

    public function setPassword(string $password): static
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /*
    public function getPassword(): ?string
    {
        return $this->password;
    }
    */

    public function getDeletedDate(): ?\DateTimeInterface
    {
        return $this->deleted_date;
    }

    public function setDeletedDate(\DateTimeInterface $deleted_date): static
    {
        $this->deleted_date = $deleted_date;
        return $this;
    }


    public function borrowBook(
        EntityManagerInterface $entityManager, Book $book, DateTime $checkoutDate
    ): bool
    {
        if (!$book || !empty($book->getDeletedDate())) {
            throw new \Exception('Book not found');
        }

        $borrow = new Borrow();
        $borrow->setUserId($this->id);
        $borrow->setBookId($book->getId());
        $borrow->setCheckoutDate($checkoutDate);

        $entityManager->persist($borrow);
        $book->setStatus(Book::STATUS_BORROWED);
        $entityManager->flush();

        return true;
    }

}