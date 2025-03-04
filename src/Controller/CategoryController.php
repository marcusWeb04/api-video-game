<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/v1/categories', name: 'app_category')]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'index', methods: ["GET"])]
    public function index(CategoryRepository $repository): JsonResponse
    {
        $categries = $repository->findAll();
        $data = array_map(fn(Category $category)=>[
            'id' => $category->getId(),
            'name' => $category->getName(),
        ], $categries);
        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/page={page}&limit={limit}', name: 'indexPaginate', methods:["GET"])]
    public function indexPaginate(CategoryRepository $repository, Request $request,TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page',1);
        $limit = $request->get('limit',5);
        $cacheIdentifier = "getAllCategory" . $page. "-" .$limit;
        $data = $cachePool->get($cacheIdentifier,
            function(ItemInterface $items) use ($repository,$page,$limit){
                $items->tag("categoryCache");
                return $repository->findAllWithPagiante($page,$limit);
            }
        );
        return $this->json($data,Response::HTTP_OK,[],['groups'=>'indexPaginateCategory']);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'], $data['releaseYear'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $category = new Category();
        $category->setName($data['title']);

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Le film à bien été créer'], Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'La catégori à bien été modifier'], Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $entityManager,TagAwareCacheInterface $cachePool): JsonResponse
    {
        $cachePool->invalidateTags(["categoryCache"]);
        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'La catégori à bien été supprimer'], Response::HTTP_OK);
    }
}
