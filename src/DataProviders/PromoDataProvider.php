<?php

namespace App\DataProviders;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Promo;
use App\Repository\PromoRepository;
use App\Service\UserService;

final class PromoDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    /**
     * @var PromoRepository
     */
    private $promoRepository;

    public function __construct(PromoRepository $promoRepository)
    {
        $this->promoRepository = $promoRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Promo::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        if ($operationName == "get_apprenant_attente" || $operationName == "get_apprenant_attente_of_one_promo")
        {
            return $this->promoRepository->findApprenantAttente();
        }
    }
}