<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"apprenant:read"}},
 *     collectionOperations={
 *          "get"={"path"="/apprenants"},
 *          "addApprenant"={
 *              "path"="/apprenants",
 *              "route_name"="addApprenant",
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR')",
 *              "security_message"="Access denied !"
 *          }
 *     },
 *     itemOperations={
 *          "showApprenantById"={
 *              "path"="/apprenant/{id}",
 *              "method"="GET"
 *          },
 *          "updateApprenant"={
 *              "path"="/apprenants/{id}",
 *              "route_name"="updateApprenant",
 *              "method"="PUT"
 *          },
 *     }
 *
 * )
 */
class Apprenant extends User
{

}
