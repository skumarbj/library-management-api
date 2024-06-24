<?php

namespace App\Controller;

use DateTime;
use App\Entity\Book;
use App\Repository\BookRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookController extends AbstractController
{

    /**
     * @OA\Get(
     *     path="/api/book",
     *     summary="Get all books",
     *     @OA\Response(
     *         response=200,
     *         description="Returns the list of books",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
     *     )
     * )
     */
    #[Route('/api/book', name: 'api_book_list')]
    public function index(
        BookRepository $bookRepository, SerializerInterface $serializer
    ): Response
    {
        $books = $bookRepository->findAll();
        $data = $serializer->serialize($books, 'json');

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @OA\Get(
     *     path="/api/book/{id}",
     *     summary="Get book details by ID",
     *     @OA\Response(
     *         response=200,
     *         description="Returns the details of book by given ID",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Book ID"
     *     )
     * )
     */
    #[Route('/api/book/{id}', name: 'api_book_details')]
    public function details(
        BookRepository $bookRepository, int $id, SerializerInterface $serializer
    ): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Invalid Book ID'], 400);
        }

        $data = $serializer->serialize($book, 'json');
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @OA\Post(
     *     path="/api/book",
     *     summary="Create a new book",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Book")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book created!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required fields"
     *     )
     * )
     */
    #[Route('/api/book', name: 'api_book_create')]
    public function create(
        Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer
    ): Response
    {
        $requestData = $request->getContent();
        $book = $serializer->deserialize($requestData, Book::class, 'json');
        if (
            !$book->getTitle() || !$book->getAuthor() || 
            !$book->getGenre() || !$book->getIsbn() ||
            !$book->getPublishedDate() || !$book->getStatus()
        ) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Detailed validation need to develop

        $entityManager->persist($book);
        $entityManager->flush();

        $data = $serializer->serialize($book, 'json');
        return new JsonResponse(
            ['message' => 'Book created!', 'book' => json_decode($data)], 201
        );
    }


    /**
     * @OA\Put(
     *     path="/api/book/{id}",
     *     summary="Update book by ID",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Book")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required fields | Invalid Status"
     *     )
     * )
     */
    #[Route('/api/book/{id}', name: 'api_book_update')]
    public function update(
        int $id, EntityManagerInterface $entityManager, 
        Request $request, SerializerInterface $serializer,
        BookRepository $bookRepository
    ): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Invalid Book ID'], 400);
        }

        $requestData = $request->getContent();
        $updatedBook = $serializer->deserialize($requestData, Book::class, 'json');
        if (
            !$updatedBook->getTitle() || !$updatedBook->getAuthor() || 
            !$updatedBook->getGenre() || !$updatedBook->getIsbn() ||
            !$updatedBook->getPublishedDate() || !$updatedBook->getStatus()
        ) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        if (!in_array($updatedBook->getStatus(), Book::STATUS_VALUES)) {
            return new JsonResponse(['error' => 'Invalid Status'], 400);
        }

        $book->setTitle($updatedBook->getTitle());
        $book->setAuthor($updatedBook->getAuthor());
        $book->setGenre($updatedBook->getGenre());
        $book->setIsbn($updatedBook->getIsbn());
        $book->setPublishedDate($updatedBook->getPublishedDate());
        $book->setStatus($updatedBook->getStatus());

        $entityManager->flush();
        $data = $serializer->serialize($book, 'json');
        return new JsonResponse(['message' => 'Book updated!', 'book' => json_decode($data)], 200);
    }


    /**
     * @OA\Delete(
     *     path="/api/book/{id}",
     *     summary="Delete book by ID",
     *     @OA\Response(
     *         response=200,
     *         description="Book deleted!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Book ID"
     *     )
     * )
     */
    #[Route('/api/book/{id}', name: 'api_book_delete')]
    public function delete(BookRepository $bookRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Invalid Book ID'], 400);
        }

        $book->setDeletedDate(new DateTime("now"));
        $entityManager->flush();
        return new JsonResponse(['message' => 'Book deleted!'], 204);
    }

}
