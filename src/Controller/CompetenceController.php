<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\Niveau;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CompetenceController extends AbstractController
{

    /**
     * @Route("/competence", name="competence")
     */
    public function index(): Response
    {
        return $this->render('competence/index.html.twig', [
            'controller_name' => 'CompetenceController',
        ]);
    }

    /**
     * @Route(
     *     name="putCompetence",
     *     path="/api/admin/competences/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\CompetenceController::putCompetence",
     *          "__api_resource_class"="App\Entity\Competence::class"
     *     }
     * )
     * @param Request $request
     * @param int $id
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     */
    public function putCompetence(Request $request, int $id, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $request = $request->getContent();
        //dd($request);
        // Récupération des informations de la compétence !
        $competence = $manager->getRepository(Competence::class)->find($id);
        //dd($competence);
        $competenceTab = $serializer->decode($request, 'json');
        if(isset($competenceTab['nomCompetence']) && !empty($competenceTab['nomCompetence']))
        {
            $competence->setNomCompetence($competenceTab['nomCompetence']);
            $competence->setDescription($competenceTab['description']);
        }
        //dd($competenceTab);
        if (isset($competenceTab['niveau']))
        {
            foreach ($competenceTab['niveau'] as $niveau) {
                $niveauRepo = $manager->getRepository(Niveau::class)->find($niveau['id']);
                $niveauRepo->setLevel($niveau['level']);
                $manager->persist($niveauRepo);
            }
        }
        $manager->persist($competence);
        $manager->flush();
        return $this->json("Update successful !");
    }

}
