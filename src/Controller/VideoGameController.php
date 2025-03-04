<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Repository\VideoGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/v1/videoGame', name: 'app_video_game')]
final class VideoGameController extends AbstractController
{
    #[Route('/', name: 'index', methods: ["GET"])]
    public function index(VideoGameRepository $repository): JsonResponse
    {
        $videoGame = $repository->findAll();
        $data = array_map(fn(VideoGame $videoGame)=>[
            'id' => $videoGame->getId(),
            'name' => $videoGame->getTitle(),
            'realease-date' => $videoGame->getReleaseDate(),
            'description' => $videoGame->getCategory(),
            'editor' => $videoGame->getEditor(),
        ], $videoGame);
        return $this->json($data,Response::HTTP_OK);
    }

    #[Route('/page={page}&limit={limit}', name: 'indexPaginate', methods:["GET"])]
    public function indexPaginate(VideoGameRepository $repository, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page',1);
        $limit = $request->get('limit',5);
        $cacheIdentifier = "getAllVideoGame" . $page. "-" .$limit;
        $data = $cachePool->get($cacheIdentifier,
            function(ItemInterface $items) use ($repository,$page,$limit){
                $items->tag("videoGameCache");
                return $repository->findAllWithPagiante($page,$limit);
            }
        );
        return $this->json($data,Response::HTTP_OK,[],['groups'=>'indexPaginateVideogame']);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        var_dump($request);

        if (!isset($data['title'], $data['release_date'],$data['description'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $videoGame = new VideoGame();
        $videoGame->setTitle($data['title']);
        $videoGame->setReleaseDate($data["release_date"]);
        $videoGame->setDescription($data["description"]);
        $videoGame->setCategory($data["category"]);
        $videoGame->setEditor($data["editor"]);

        $entityManager->persist($videoGame);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Le jeu vidéo à bien été créer'], Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, VideoGame $videoGame, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $videoGame->setTitle($data['title']);
        }

        if (isset($data['release_date'])) {
            $videoGame->setReleaseDate($data['release_date']);
        }

        if(isset($data["description"])){
            $videoGame->setDescription($data["description"]);
        }

        if(isset($data["category"])){
            $videoGame->setCategory($data["category"]);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Le jeu vidéo à bien été modifier'],Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(VideoGame $videoGame, EntityManagerInterface $entityManager, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $cachePool->invalidateTags(["videoGameCache"]);
        $entityManager->remove($videoGame);
        $entityManager->flush();

        return new JsonResponse(['message' => 'L\'editeur à bien été supprimer'],Response::HTTP_OK);
    }
}