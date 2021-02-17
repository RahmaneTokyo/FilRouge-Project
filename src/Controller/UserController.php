<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route(
     *     name="addUser",
     *     path="/api/admin/users",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::addUser",
     *          "__api_resource_class"="App\Entity\User::class",
     *          "__api_collection_operation_name"="addUser"
     *     }
     * )
     * @param ProfilRepository $repos
     * @param UserService $service
     * @param UserPasswordEncoderInterface $encoder
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param UserRepository $repo
     * @return JsonResponse
     */
    public function addUser(ProfilRepository $repo, UserService $service, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager, ValidatorInterface $validator, UserRepository $repos)
    {

        $profil =$request->request->get("profil");
        $user = $service->addUser($profil, $request, $validator);
        //dd($apprenant);
        $manager->persist($user);
        $manager->flush();
        return $this->json('Success', 201);

    }

    /**
     * @Route(
     *     name="updateUser",
     *     path="/api/admin/users/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::updateUser",
     *          "__api_resource_class"="App\Entity\User::class"
     *     }
     * )
     * @param UserPasswordEncoderInterface $encoder
     * @param UserService $service
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @param $id
     * @return JsonResponse
     */
    public function updateUser(UserPasswordEncoderInterface $encoder, UserService $service, Request $request, EntityManagerInterface $manager, UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);
        //$user = $request->attributes->get('data');
        //dd($user);
        $userUpdate = $service->UpdateUser($request, 'avatar');
        ///dd($userUpdate);
        foreach ($userUpdate as $key => $value) {
            $setter = 'set'.ucfirst(trim(strtolower($key)));
            //dd($setter);
            if(method_exists(User::class, $setter)) {
                if ($setter == 'setPassword') {
                    $password = $encoder->encodePassword($user, $userUpdate['password']);
                    /*dd($password);*/
                    $user->setPassword($userUpdate['password']);
                    /*dd($user);*/
                }
                if($setter == 'setProfil') {
                    $user->setProfil($userUpdate['profil']);
                }else{
                    $user->$setter($value);
                }

                //dd($user);
            }
        }
        $manager->persist($user);
        //dd($user);
        $manager->flush();
        return $this->json("Update successful !");
    }

}
