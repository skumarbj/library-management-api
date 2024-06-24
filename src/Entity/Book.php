<?php

namespace App\Entity;

use App\Repository\BookRepository;
use DateTime;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    const STATUS_VALUES = ['AVAILABLE', 'BORROWED'];
    const STATUS_AVAILABLE = 'AVAILABLE';
    const STATUS_BORROWED = 'BORROWED';

    /**
     * @OA\Property(
     *     description="ID of the Book",
     *     example=1
     * )
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @OA\Property(
     *     description="Title of the book",
     *     example="Book Title"
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @OA\Property(
     *     description="Author of the book",
     *     example="Name Lastname"
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $author = null;

    /**
     * @OA\Property(
     *     description="Genre of the book",
     *     example="science | History"
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    /**
     * @OA\Property(
     *     description="ISBN of the book",
     *     example="7623873037643"
     * )
     */
    #[ORM\Column(length: 20)]
    private ?string $isbn = null;

    /**
     * @OA\Property(
     *     description="Book Published date",
     *     example="1860-01-24"
     * )
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $published_date = null;


    /**
     * @OA\Property(
     *     description="status of the book",
     *     example="AVAILABLE | BORROWED"
     * )
     */
    #[ORM\Column(type: "string", columnDefinition: "ENUM('AVAILABLE', 'BORROWED')")]
    private ?string $status = null;

    /**
     * @OA\Property(
     *     description="Book deleted with date",
     *     example="1860-01-24"
     * )
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $deleted_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;
        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;
        return $this;
    }

    public function getPublishedDate(): ?\DateTimeInterface
    {
        return $this->published_date;
    }

    public function setPublishedDate(\DateTimeInterface $published_date): static
    {
        $this->published_date = $published_date;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        if (!in_array($status, self::STATUS_VALUES)) {
            throw new \InvalidArgumentException("Invalid status");
        }
        $this->status = $status;
        return $this;
    }

    public function getDeletedDate(): ?\DateTimeInterface
    {
        return $this->deleted_date;
    }

    public function setDeletedDate(\DateTimeInterface $deleted_date): static
    {
        $this->deleted_date = $deleted_date;
        return $this;
    }

    public function returnBook(
        EntityManagerInterface $entityManager, Borrow $borrow, DateTime $checkinDate
    ): bool
    {
        $borrow->setCheckinDate($checkinDate);
        $this->setStatus(self::STATUS_AVAILABLE);

        $entityManager->flush();

        return true;
    }
    
}
