<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;

use App\Repository\NationaliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteurs", name="api_auteurs", methods={"GET"})
     * @param AuteurRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function list(AuteurRepository $repository, SerializerInterface $serializer)
    {
        $auteurs = $repository->findAll();
        $resultat = $serializer->serialize(
            $auteurs,
            'json',
            [
            'groups'=>['listAuteurSimple']
        ]);

        return new JsonResponse($resultat,200,[],true);
    }


    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_show", methods={"GET"})
     * @param Auteur $auteur
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(Auteur $auteur, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $auteur,
            'json',
            [
                'groups'=>['listAuteurSimple']
            ]);

        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/auteurs", name="api_create_auteur", methods={"POST"})
     * @param Request $request
     * @param NationaliteRepository $nationaliteRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function create(Request $request,NationaliteRepository $nationaliteRepository, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $auteur = new Auteur();
        $dataTab = $serializer->decode($data,'json');
        $nationalite = $nationaliteRepository->find($dataTab['nationalite']['id']);
        $serializer->deserialize($data,Auteur::class,'json',['object_to_populate'=>$auteur]);
        $auteur->setNationalite($nationalite);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($auteur);
        $manager->flush();

        return new JsonResponse("Le auteur a bien été crée",Response::HTTP_CREATED,[
            "location"=>"api/auteurs/".$auteur->getId()
        ],true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_update", methods={"PUT"})
     * @param Auteur $auteur
     * @param NationaliteRepository $nationaliteRepository
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function edit(Auteur $auteur,NationaliteRepository $nationaliteRepository,Request $request,SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $dataTab = $serializer->decode($data,'json');
        $nationalite = $nationaliteRepository->find($dataTab['nationalite']['id']);

        //Solution 1
        $serializer->deserialize($data,Auteur::class,'json',['object_to_populate'=>$auteur]);
        $auteur->setNationalite($nationalite);

        //Solution 2
        //$serializer->denormalize($auteur['auteur'],Auteur::class,null,['object_to_populate'=>$auteur]);


        $manager = $this->getDoctrine()->getManager();
        $manager->persist($auteur);
        $manager->flush();

        return new JsonResponse("le auteur a bien été modifié",Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_delete", methods={"DELETE"})
     * @param Auteur $auteur
     * @return JsonResponse
     */
    public function delete(Auteur $auteur)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($auteur);
        $manager->flush();

        return new JsonResponse("le auteur a bien été supprimé",Response::HTTP_OK,[],false);
    }


}
