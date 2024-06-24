<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
class Borrow
{
    /**
     * @OA\Property(
     *     description="ID of the Borrow Record",
     *     example=1
     * )
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @OA\Property(
     *     description="ID of the Book which is borrowed",
     *     example=1
     * )
     */
    #[ORM\Column]
    private ?int $book_id = null;

    /**
     * @OA\Property(
     *     description="ID of the user who is borrowed a book",
     *     example=1
     * )
     */
    #[ORM\Column]
    private ?int $user_id = null;

    /**
     * @OA\Property(
     *     description="Borrowed date",
     * )
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $checkout_date = null;

    /**
     * @OA\Property(
     *     description="Returned date",
     * )
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checkin_date = null;

    /*
    #[ORM\ManyToOne(targetEntity: Book::class)]
    private Book $book;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;
    */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?int
    {
        return $this->book_id;
    }

    public function setBookId(int $book_id): static
    {
        $this->book_id = $book_id;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getCheckoutDate(): ?\DateTimeInterface
    {
        return $this->checkout_date;
    }

    public function setCheckoutDate(\DateTimeInterface $checkout_date): static
    {
        $this->checkout_date = $checkout_date;
        return $this;
    }

    public function getCheckinDate(): ?\DateTimeInterface
    {
        return $this->checkin_date;
    }

    public function setCheckinDate(?\DateTimeInterface $checkin_date): static
    {
        $this->checkin_date = $checkin_date;
        return $this;
    }

}
