<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FormateurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"formateur:read"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/formateurs",
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
 *          }
 *     },
 *     itemOperations={
 *          "showFormateurById"={
 *              "path"="/formateurs/{id}",
 *              "method"="GET"
 *          },
 *     }
 * )
 */
class Formateur extends User
{

}
