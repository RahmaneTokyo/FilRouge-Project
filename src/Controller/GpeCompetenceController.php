<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\GpeCompetence;
use App\Entity\Niveau;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GpeCompetenceController extends AbstractController
{
    /**
     * @Route("/gpe/competence", name="gpe_competence")
     */
    public function index(): Response
    {
        return $this->render('gpe_competence/index.html.twig', [
            'controller_name' => 'GpeCompetenceController',
        ]);
    }

    /**
     * @Route(
     *     name="putGpeCompetence",
     *     path="/api/admin/grpecompetences/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\GpeCompetenceController::putGpeCompetence",
     *          "__api_resource_class"="App\Entity\GpeCompetence::class"
     *     }
     * )
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param int $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function putGpeCompetence(Request $request, EntityManagerInterface $manager, int $id, SerializerInterface $serializer)
    {
        $request = $request->getContent();
        // Récupération des informations du groupe de competence
        $gpecompetence = $manager->getRepository(GpeCompetence::class)->find($id);
        //dd($gpecompetence);
        $gpecompetenceTab = $serializer->decode($request, 'json');

        // Modification du groupe de competence
        if(isset($gpecompetenceTab['libelle'])){
            $gpecompetence->setLibelle($gpecompetenceTab['libelle']);
        }

        // Modification de competence
        if(isset($gpecompetenceTab['competence']))
        {
            foreach ($gpecompetenceTab['competence'] as $competence)
            {
                // Modifier la competence
                if(isset($competence['id']) && isset($competence['nomCompetence']))
                {
                    $competenceUpdate = $manager->getRepository(Competence::class)->find($competence['id']);
                    $competenceUpdate ->setNomCompetence($competence['nomCompetence']);
                    $competenceUpdate ->setDescription($competence['description']);
                    $manager->persist($competenceUpdate);
                }
                // Ajout Competence
                if(!isset($competence['id']))
                {
                    $competenceAdd = new Competence();
                    $competenceAdd ->setNomCompetence($competence['nomCompetence']);
                    $competenceAdd ->setDescription($competence['description']);
                    $competenceAdd ->addGpeCompetence($gpecompetence);
                    if(!isset($competence['niveau']) || count($competence['niveau']) != 3)
                    {
                        return $this->json("Veuillez mettre 3 niveax exactement !");
                    }else{
                        foreach ($competence['niveau'] as $niveau)
                        {
                            $niveauAdd = new Niveau();
                            $niveauAdd ->setLevel($niveau['level']);
                            $niveauAdd ->setCompetence($competenceAdd);
                            $manager->persist($niveauAdd);
                        }
                    }
                    $manager->persist($competenceAdd);
                }
                // Suppression de competence
                if (isset($competence['id']) && empty($competence['nomCompetence']) && empty($competence['description']))
                {
                    $competenceRemove = $manager->getRepository(Competence::class)->find($competence['id']);
                    $competenceRemove ->removeGpeCompetence($gpecompetence);
                    $manager ->persist($competenceRemove);
                }
            }
        }
        $manager->persist($gpecompetence);
        $manager->flush();
        return new JsonResponse("Update successful !", 200, [], true);
    }
}
