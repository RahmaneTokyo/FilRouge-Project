<?php


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competence;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;

class CompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var NiveauRepository
     */
    private $niveauRepository;

    /**
     *
     * @param EntityManagerInterface $entityManager
     * @param NiveauRepository $niveauRepository
     */

    public function __construct(EntityManagerInterface $entityManager, NiveauRepository $niveauRepository)
    {
        $this->entityManager = $entityManager;
        $this->niveauRepository = $niveauRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Competence;
    }

    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $data->setArchived(true);
        $id = $data->getId();
        $niveau = $this->niveauRepository->findBy(['niveau' =>$id]);
        foreach ($niveau as $niveaux)
        {
            $niveaux->setArchived(false);
            $this->entityManager->persist($niveaux);
        }
        $this->entityManager->flush();
    }
}