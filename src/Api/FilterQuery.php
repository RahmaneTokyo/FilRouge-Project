<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Competence;
use App\Entity\GpeCompetence;
use App\Entity\Niveau;
use App\Entity\Profil;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

class FilterQuery implements QueryCollectionExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass === User::class || $resourceClass === Profil::class || $resourceClass === Competence::class || $resourceClass === Niveau::class || $resourceClass === GpeCompetence::class )
        {
            $queryBuilder->andWhere(sprintf("%s.archived = false",
            $queryBuilder->getRootAliases()[0]));
        }
    }
}