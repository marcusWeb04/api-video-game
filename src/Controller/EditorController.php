<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/v1/editor', name: 'app_editor')]
final class EditorController extends AbstractController
{
    #[Route('/', name: 'index', methods: ["GET"])]
    public function index(EditorRepository $repository): JsonResponse
    {
        $categries = $repository->findAll();
        $data = array_map(fn(Editor $editor)=>[
            'id' => $editor->getId(),
            'name' => $editor->getName(),
            'country' => $editor->getCountry(),
        ], $categries);
        return $this->json($data);
    }

    #[Route('/page={page}&limit={limit}', name: 'indexPaginate', methods:["GET"])]
    public function indexPaginate(EditorRepository $repository, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page',1);
        $limit = $request->get('limit',5);
        $cacheIdentifier = "getAllEditor" . $page. "-" .$limit;
        $data = $cachePool->get($cacheIdentifier,
            function(ItemInterface $items) use ($repository,$page,$limit){
                $items->tag("editorCache");
                return $repository->findAllWithPagiante($page,$limit);
            }
        );
        return $this->json($data,Response::HTTP_OK,[],['groups'=>'indexPaginateEdior']);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'], $data['releaseYear'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $editor = new Editor();
        $editor->setName($data['name']);
        $editor->setCountry($data["country"]);

        $entityManager->persist($editor);
        $entityManager->flush();

        return new JsonResponse(['message' => 'L\'editeur à bien été créer'], 201);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Editor $editor, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $editor->setName($data['name']);
        }

        if (isset($data['country'])) {
            $editor->setCountry($data['country']);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'L\'editeur à bien été modifier']);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Editor $editor, EntityManagerInterface $entityManager, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $cachePool->invalidateTags(["editorCache"]);
        $entityManager->remove($editor);
        $entityManager->flush();

        return new JsonResponse(['message' => 'L\'editeur à bien été supprimer']);
    }
}