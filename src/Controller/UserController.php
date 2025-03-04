<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/users', name: 'app_category')]
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user_index", methods={"GET"})
     */
    #[Route('/', name: 'user_index', methods: ["GET"])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->json($users);
    }
    
    /**
     * @Route("/user/create", name="user_create", methods={"POST"})
     */
    #[Route('/create', name: 'user_create', methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer un nouvel utilisateur
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));

        // Enregistrer dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     */
    #[Route('/select/{id}', name: 'user_show', methods: ["GET"])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer un utilisateur par son ID
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", methods={"PUT"})
     */
    #[Route('/edit/{id}', name: 'user_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Trouver l'utilisateur par ID
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Mettre à jour les informations de l'utilisateur
        $user->setEmail($request->get('email'));
        if ($request->get('password')) {
            $user->setPassword($request->get('password'));
        }

        // Sauvegarder les modifications
        $entityManager->flush();

        return $this->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    #[Route('/delete/{id}', name: 'user_show', methods: ["DELETE"])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
