<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GpeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GpeCompetenceRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups":{"grpecompetence:read"}},
 *     denormalizationContext={"groups":{"grpecompetence:write"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/grpecompetences"
 *          },
 *          "post"={
 *              "path"="/admin/grpecompetences"
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/grpecompetences/{id}"
 *          },
 *          "put"={
 *              "path"="/admin/grpecompetences/{id}"
 *          },
 *          "delete"={
 *              "path"="/admin/grpecompetences/{id}"
 *          }
 *     }
 * )
 */
class GpeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpecompetence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetence:write","grpecompetence:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = false;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="gpeCompetences", cascade={"persist"})
     * @Groups({"grpecompetence:write","grpecompetence:read"})
     */
    private $competence;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
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
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competence->removeElement($competence);

        return $this;
    }
}
