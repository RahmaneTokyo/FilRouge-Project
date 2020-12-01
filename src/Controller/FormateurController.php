<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormateurController extends AbstractController
{
    /**
     * @Route("/formateur", name="formateur")
     */
    public function index(): Response
    {
        return $this->render('formateur/index.html.twig', [
            'controller_name' => 'FormateurController',
        ]);
    }

    /**
     * @Route(
     *     path="/api/formateurs/{id}",
     *     defaults={
     *          "__api_resource_class"="App\Entity\Formateur::class",
     *          "__api_item_operation_name"="showFormateurById"
     *     }
     * )
     * @param int $id
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function showApprenantById(int $id, UserRepository $userRepository)
    {
        $formateur = $userRepository->findByProfilById("FORMATEUR", $id);
        if($this->isGranted('ROLE_FORMATEUR')) {
            $idFormateur = $this->getUser()->getId();
            //dd($idFormateur);
            if($idFormateur == $id)
            {
                return $this->json($formateur, Response::HTTP_OK);
            }else {
                return $this->json("Sorry access denied !");
            }
        }else{
            if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CM'))
            {
                return $this->json($formateur, Response::HTTP_OK);
            }else{
                return $this->json("Sorry access denied !");
            }

        }
    }



}
