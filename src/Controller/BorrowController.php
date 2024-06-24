<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use DateTime;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Borrow;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\BorrowRepository;

class BorrowController extends AbstractController
{

    /**
     * @OA\Post(
     *     path="/api/borrow",
     *     summary="Create a new book",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Borrow")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book borrowed successfully!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required fields | Invalid (date | book id | user id)"
     *     )
     * )
     */
    #[Route('/api/borrow', name: 'api_borrow')]
    public function borrow(
        Request $request, EntityManagerInterface $entityManager,
        BookRepository $bookRepository, UserRepository $userRepository
    ): Response
    {
        $requestData = json_decode($request->getContent(),true);
        if (
            empty($requestData['userId']) || empty($requestData['bookId']) || 
            empty($requestData['checkoutDate'])
        ) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        if (strtotime($requestData['checkoutDate']) === false) {
            return new JsonResponse(['error' => 'Invalid date format or date value, ex: YYYY-MM-DD'], 400);
        }
        $checkoutDate = new DateTime($requestData['checkoutDate']);
        
        $user = $userRepository->find($requestData['userId']);
        if (!$user || !empty($user->getDeletedDate())) {
            return new JsonResponse(['error' => 'Invalid User ID'], 400);
        }

        $book = $bookRepository->find($requestData['bookId']);
        if (!$book || !empty($book->getDeletedDate())) {
            return new JsonResponse(['error' => 'Invalid Book ID'], 400);
        }

        if ($book->getStatus() == Book::STATUS_BORROWED) {
            return new JsonResponse(['error' => 'Currently book not available, its borrowed by someone'], 400);
        }

        $user->borrowBook($entityManager, $book, $checkoutDate);
        return new JsonResponse(['message' => 'Book borrowed successfully!'], 201);
    }


    /**
     * @OA\Post(
     *     path="/api/borrow",
     *     summary="Create a new book",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Borrow")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book returned successfully!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required fields | Invalid (date | book id | user id)"
     *     )
     * )
     */
    #[Route('/api/borrow/return', name: 'api_borrow_return')]
    public function return(
        EntityManagerInterface $entityManager, BookRepository $bookRepository, 
        Request $request, UserRepository $userRepository, BorrowRepository $borrowRepository
    ): Response
    {
        $requestData = json_decode($request->getContent(),true);
        if (
            empty($requestData['userId']) || empty($requestData['bookId']) || 
            empty($requestData['checkinDate'])
        ) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        if (strtotime($requestData['checkinDate']) === false) {
            return new JsonResponse(['error' => 'Invalid date format or date value, ex: YYYY-MM-DD'], 400);
        }
        $checkinDate = new DateTime($requestData['checkinDate']);
        
        $user = $userRepository->find($requestData['userId']);
        if (!$user || !empty($user->getDeletedDate())) {
            return new JsonResponse(['error' => 'Invalid User ID'], 400);
        }

        $book = $bookRepository->find($requestData['bookId']);
        if (!$book || !empty($book->getDeletedDate())) {
            return new JsonResponse(['error' => 'Invalid Book ID'], 400);
        }

        $borrow = $borrowRepository->findByUserBookCheckInNull($user->getId(), $book->getId());
        if (!$borrow) {
            $error = [ 
                'message' => 'This book not borrowed by this user',
                'book' => $book->getTitle(),
                'user' => $user->getEmail()
            ];
            return new JsonResponse(['error' => $error], 400);
        }
        if ($checkinDate < $borrow->getCheckoutDate()) {
            return new JsonResponse(['error' => 'Checkin date should be greater or equal to Checkout date'], 400);
        }
        $book->returnBook($entityManager, $borrow, $checkinDate);

        return new JsonResponse(['message' => 'Book returned successfully!'], 201);
    }


    /**
     * @OA\Post(
     *     path="/api/borrow/history",
     *     summary="Returns Borrow history",
     *     @OA\Response(
     *         response=200,
     *         description="shows borrow history",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Borrow"))
     *     )
     * )
     */
    #[Route('/api/borrow/history', name: 'api_borrow_history')]
    public function history(
        SerializerInterface $serializer, BorrowRepository $borrowRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $books = $borrowRepository->getAllBorrowedBooks($entityManager);
        $data = $serializer->serialize($books, 'json');

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

}
