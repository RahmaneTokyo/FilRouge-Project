<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 * @UniqueEntity("libelle")
 * @ApiResource(
 *     denormalizationContext={"groups":{"sortie:write"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/profilsorties"
 *          },
 *          "post"={
 *              "path"="/admin/profilsorties"
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/profilsorties/{id}",
 *              "normalization_context"={"groups"={"users_of_profils_sortie:read"}}
 *          },
 *          "put"={
 *              "path"="/admin/profilsorties/{id}"
 *          },
 *          "delete"={
 *              "path"="/admin/profilsorties/{id}"
 *          }
 *     }
 * )
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_of_profils_sortie:read","sortie:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"users_of_profils_sortie:read","sortie:write"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie", cascade={"persist"})
     * @Groups({"users_of_profils_sortie:read","sortie:write"})
     */
    private $apprenant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = false;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }
}
