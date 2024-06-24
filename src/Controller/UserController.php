<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="Returns the list of users",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     )
     * )
     */
    #[Route('/api/user', name: 'api_user_list')]
    public function index(
        UserRepository $userRepository, SerializerInterface $serializer
    ): Response
    {
        $users = $userRepository->findAll();
        $data = $serializer->serialize($users, 'json');

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     summary="Get user details by ID",
     *     @OA\Response(
     *         response=200,
     *         description="Returns the details of user by given ID",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid User ID"
     *     )
     * )
     */
    #[Route('/api/user/{id}', name: 'api_user_details')]
    public function details(
        UserRepository $userRepository, int $id, SerializerInterface $serializer
    ): Response
    {
        $user = $userRepository->find($id);
        if (!$user || !empty($user->getDeletedDate())) {
            return new JsonResponse(['error' => 'Invalid User ID'], 400);
        }

        $data = $serializer->serialize($user, 'json');
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @OA\Post(
     *     path="/api/user",
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required fields"
     *     )
     * )
     */
    #[Route('/api/user', name: 'api_user_create')]
    public function create(
        EntityManagerInterface $entityManager, SerializerInterface $serializer,
        Request $request, UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $requestData = $request->getContent();
        $password = json_decode($requestData)->password ?? '';
        $user = $serializer->deserialize($requestData, User::class, 'json');

        if (
            !$user->getName() || !$user->getEmail() || 
            !$user->getRole() || empty($password)
        ) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        /*
            Detailed validation need to develop, 
            like email validation, & other fields
        */

        $user->setPassword($password);

        $entityManager->persist($user);
        $entityManager->flush();

        $data = $serializer->serialize($user, 'json');
        return new JsonResponse(
            ['message' => 'User created!', 'user' => json_decode($data)], 201
        );
    }


    /**
     * @OA\Put(
     *     path="/api/user/{id}",
     *     summary="Update user by ID",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required fields | Invalid Roles"
     *     )
     * )
     */
    #[Route('/api/user/{id}', name: 'api_user_update')]
    public function update(
        UserRepository $userRepository, int $id, Request $request,
        EntityManagerInterface $entityManager, SerializerInterface $serializer
    ): Response
    {
        $user = $userRepository->find($id);
        if (!$user || !empty($user->getDeletedDate())) {
            return new JsonResponse(['error' => 'Invalid User ID'], 400);
        }

        $requestData = $request->getContent();
        $updatedUser = $serializer->deserialize($requestData, User::class, 'json');
        $password = json_decode($requestData)->password ?? '';

        if (
            !$updatedUser->getName() || !$updatedUser->getEmail() || 
            !$updatedUser->getRole() || empty($password)
        ) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        if (!in_array($updatedUser->getRole(), User::ROLE_VALUES)) {
            return new JsonResponse(['error' => 'Invalid Roles'], 400);
        }

        $user->setName($updatedUser->getName());
        $user->setEmail($updatedUser->getEmail());
        $user->setRole($updatedUser->getRole());
        $user->setPassword($password);

        $entityManager->flush();
        
        $data = $serializer->serialize($user, 'json');
        return new JsonResponse(['message' => 'User updated!', 'user' => json_decode($data)], 200);
    }


    /**
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     summary="Delete user by ID",
     *     @OA\Response(
     *         response=200,
     *         description="User deleted!"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid User ID"
     *     )
     * )
     */
    #[Route('/api/user/{id}', name: 'api_user_delete')]
    public function delete(
        UserRepository $userRepository, int $id, EntityManagerInterface $entityManager
    ): Response
    {
        $user = $userRepository->find($id);
        if (!$user || !empty($user->getDeletedDate())) {
            return new JsonResponse(['error' => 'Invalid User ID'], 400);
        }

        $user->setDeletedDate(new DateTime("now"));
        $entityManager->flush();
        return new JsonResponse(['message' => 'User deleted!'], 200);
    }

}
