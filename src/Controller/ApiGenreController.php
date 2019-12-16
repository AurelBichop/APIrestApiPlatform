<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     * @param GenreRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function list(GenreRepository $repository, SerializerInterface $serializer)
    {
        $genres = $repository->findAll();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
            'groups'=>['listGenreSimple']
        ]);

        return new JsonResponse($resultat,200,[],true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     * @param Genre $genre
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups'=>['listGenreSimple']
            ]);

        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/genres", name="api_create_genre", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        //$genre = new Genre();
        //$serializer->deserialize($data,Genre::class,'json',['object_to_populate'=>$genre]);
        $genre = $serializer->deserialize($data,Genre::class,'json');

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse("Le genre a bien été crée",Response::HTTP_CREATED,[
            "location"=>"api/genres/".$genre->getId()
        ],true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"PUT"})
     * @param Genre $genre
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function edit(Genre $genre,Request $request,SerializerInterface $serializer)
    {
        $data= $request->getContent();
        $serializer->deserialize($data,Genre::class,'json',['object_to_populate'=>$genre]);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse("le genre a bien été modifié",Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"DELETE"})
     * @param Genre $genre
     * @return JsonResponse
     */
    public function delete(Genre $genre)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($genre);
        $manager->flush();

        return new JsonResponse("le genre a bien été supprimé",Response::HTTP_OK,[],false);
    }

}
