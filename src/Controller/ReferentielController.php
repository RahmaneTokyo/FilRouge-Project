<?php

namespace App\Controller;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ReferentielController extends AbstractController
{

    /**
     * @Route(
     *     name="addReferentiel",
     *     path="/api/admin/referentiels",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::addReferentiel",
     *          "__api_resource_class"="App\Entity\Referentiel::class",
     *          "__api_collection_operation_name"="addReferentiel"
     *     }
     * )
     * @param Request $request
     * @param DenormalizerInterface $denormalizer
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function addReferentiel(Request $request, DenormalizerInterface $denormalizer, EntityManagerInterface $manager) {
        $ref = $request->request->all();
        $referentiel = $denormalizer->denormalize($ref, Referentiel::class, true);
        $p = $request->files->get('programme');
        if ($p) {
            $referentiel->setProgramme(fopen($p->getRealPath(), "r+"));
        }
        $manager->persist($referentiel);
        $manager->flush();
        return $this->json("success", 201);
    }

}
