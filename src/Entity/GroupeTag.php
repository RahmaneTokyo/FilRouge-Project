<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR')",
 *          "securityMessage"="Access denied !"
 *     },
 *     normalizationContext={"groups":{"gpetag:read"}},
 *     denormalizationContext={"groups":{"gpetag:write"}},
 *     collectionOperations={
 *          "get"={"path"="/admin/grptags"},
 *          "post"={"path"="/admin/grptags"}
 *     },
 *     itemOperations={
 *          "get"={"path"="/admin/grptags/{id}"},
 *          "put"={"path"="/admin/grptags/{id}"}
 *     }
 * )
 * @UniqueEntity(
 *     "nomGpeTag",
 *     message="Fall Bindeul lénéne!"
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gpetag:write","gpetag:read"})
     * @Assert\NotBlank
     */
    private $nomGpeTag;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     * @Groups({"gpetag:write","gpetag:read"})
     */
    private $tag;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGpeTap(): ?string
    {
        return $this->nomGpeTag;
    }

    public function setNomGpeTag(string $nomGpeTag): self
    {
        $this->nomGpeTag = $nomGpeTag;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }
}
