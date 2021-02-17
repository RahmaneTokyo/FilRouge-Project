<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *              "path"="/apprenants/{id}",
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
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenant", cascade={"persist"})
     */
    private $groupes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attente = false;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenant")
     */
    private $promo;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenant")
     */
    private $profilSortie;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    public function getEnAttente(): ?bool
    {
        return $this->attente;
    }

    public function setEnAttente(bool $attente): self
    {
        $this->attente = $attente;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }
}
