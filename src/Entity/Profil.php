<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"archived": "exact"})
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "securityMessage"="Acces denied please log as Admin !"
 *     },
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/profils",
 *              "normalization_context"={"groups"={"profil:read"}}
 *          },
 *          "post"={"path"="/admin/profils"}
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/profils/{id}",
 *              "normalization_context"={"groups"={"profil:read"}}
 *          },
 *          "get_users_of_one_profile"={
 *              "path"="/admin/profils/{id}/users",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"profiluser:read"}}
 *          },
 *          "put"={"path"="/admin/profils/{id}"},
 *          "delete"={"path"="/admin/profils/{id}"}
 *     }
 * )
 * @UniqueEntity(
 *     "libelle",
 *     message="Fall Bindeul lénéne!"
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"profil:read","user:read","profiluser:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read","user:read","profiluser:read"})
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = false;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @Groups({"profiluser:read"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getArchived(): ?int
    {
        return $this->archived;
    }

    public function setArchived(int $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
