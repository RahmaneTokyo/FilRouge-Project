<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups":{"competence:read"}},
 *     denormalizationContext={"groups":{"competence:write"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/competences",
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
 *          },
 *          "post"={
 *              "path"="/admin/competences",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Access denied !"
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/competences/{id}",
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
 *          },
 *          "put"={
 *              "path"="/admin/competences/{id}",
 *              "route_name"="putCompetence",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Access denied !"
 *          },
 *          "delete"={
 *              "path"="/admin/competences/{id}"
 *          }
 *     }
 * )
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpecompetence:write","competence:read","grpecompetence:read","comp:read","grpecomp:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:read","competence:write","grpecompetence:read","comp:read","grpecomp:read","referentiel:write","promo_referentiel:read"})
     * @Assert\NotBlank
     */
    private $nomCompetence;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:read","competence:write","grpecompetence:read","comp:read","grpecomp:read","referentiel:write","promo_referentiel:read"})
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $archived = false;

    /**
     * @ORM\ManyToMany(targetEntity=GpeCompetence::class, mappedBy="competence", cascade={"persist"})
     * @Groups({"competence:write", "competence:read"})
     * @Assert\NotBlank
     */
    private $gpeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence", cascade={"persist"})
     * @Groups({"competence:read","competence:write","grpecompetence:read","referentiel:write"})
     * @Assert\Count(
     *      min = 3,
     *      max = 3,
     *      exactMessage="You must have exactly 3 niveaux !"
     * )
     */
    private $niveau;

    public function __construct()
    {
        $this->gpeCompetences = new ArrayCollection();
        $this->niveau = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCompetence(): ?string
    {
        return $this->nomCompetence;
    }

    public function setNomCompetence(string $nomCompetence): self
    {
        $this->nomCompetence = $nomCompetence;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getArchived(): ?string
    {
        return $this->archived;
    }

    public function setArchived(string $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection|GpeCompetence[]
     */
    public function getGpeCompetences(): Collection
    {
        return $this->gpeCompetences;
    }

    public function addGpeCompetence(GpeCompetence $gpeCompetence): self
    {
        if (!$this->gpeCompetences->contains($gpeCompetence)) {
            $this->gpeCompetences[] = $gpeCompetence;
            $gpeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGpeCompetence(GpeCompetence $gpeCompetence): self
    {
        if ($this->gpeCompetences->removeElement($gpeCompetence)) {
            $gpeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }

}
