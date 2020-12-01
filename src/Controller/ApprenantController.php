<?php

namespace App\Controller;

use App\Repository\ApprenantRepository;
use App\Repository\ProfilRepository;
use App\Entity\Apprenant;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApprenantController extends AbstractController
{
    /**
     * @Route("/apprenant", name="apprenant")
     */
    public function index(): Response
    {
        return $this->render('apprenant/index.html.twig', [
            'controller_name' => 'ApprenantController',
        ]);
    }

    /**
     * @Route(
     *     name="addApprenant",
     *     path="/api/apprenants",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ApprenantController::addAprenant",
     *          "__api_ressource_class"="App\Entity\Apprenant::class",
     *          "__api_collection_operation_name"="addApprenant"
     *     }
     * )
     * @param Request $request
     * @param UserService $service
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $manager
     * @param ProfilRepository $repo
     * @return JsonResponse
     */
    public function addApprenant(Request $request, UserService $service, ValidatorInterface $validator, EntityManagerInterface $manager, ProfilRepository $repo)
    {
        $profil = "APPRENANT";
        $apprenant = $service->addApprenant($profil, $request, $validator, $repo);
        //dd($apprenant);
        $manager->persist($apprenant);
        $manager->flush();
        return new JsonResponse("Success", 200, [], true);
    }

    /**
     * @Route(
     *     name="showApprenantById",
     *     path="/api/apprenants/{id}",
     *     methods={"GET"},
     *     defaults={
     *          "__api_resource_class"="App\Entity\Apprenant::class",
     *          "__api_item_operation_name"="showApprenantById"
     *     }
     * )
     * @param int $id
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function showApprenantById(int $id, UserRepository $userRepository)
    {
        $apprenant = $userRepository->findByProfilById("APPRENANT", $id);
        if($this->isGranted('ROLE_APPRENANT')) {
            $idApprenant = $this->getUser()->getId();
            if($idApprenant == $id)
            {
                return $this->json($apprenant, Response::HTTP_OK);
            }else {
                return $this->json("Sorry access denied !");
            }
        }else{
            return $this->json($apprenant, Response::HTTP_OK);
        }
    }

    /**
     * @Route(
     *     name="updateApprenant",
     *     path="/api/apprenants/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ApprenantController::updateApprenant",
     *          "__api_resource_class"="App\Entity\Apprenant::class"
     *     }
     * )
     * @param UserRepository $userRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param int $id
     * @param UserService $service
     * @return JsonResponse
     */
    public function updateApprenant(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, int $id, UserService $service)
    {
        $apprenant = $userRepository->find($id);
        //$profil = "APPRENANT";
        //dd($apprenant);
        $apprenantUpdate = $service->UpdateUser($request, 'avatar');
        //dd($apprenantUpdate);
        foreach ($apprenantUpdate as $key => $value) {
            $setter = 'set'.ucfirst(trim(strtolower($key)));
            if(method_exists(Apprenant::class, $setter)) {
                /*if($setter == 'setProfil') {
                    $apprenant->setProfil($profil);
                }else{*/
                    $apprenant->$setter($value);
                //}
            }
        }
        $manager->persist($apprenant);
        dd($apprenant);
        $manager->flush();
        return $this->json("Update successful !");
    }

}
