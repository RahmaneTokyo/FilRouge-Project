<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GpeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GpeCompetenceRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups":{"grpecompetence:read"}},
 *     denormalizationContext={"groups":{"grpecompetence:write"}},
 *     collectionOperations={
 *          "get_competence"={
 *              "path"="/admin/grpecompetences",
 *              "method"="GET",
 *              "normalization_context"={"groups":{"comp:read"}},
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
 *          },
 *          "get_gpecompetence_&_competence"={
 *              "path"="/admin/grpecompetences/competences",
 *              "method"="GET",
 *              "normalization_context"={"groups":{"grpecomp:read"}},
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
 *          },
 *          "post"={
 *              "path"="/admin/grpecompetences",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Access denied !"
 *          }
 *     },
 *     itemOperations={
 *          "get_competence_by_id"={
 *              "path"="/admin/grpecompetences/{id}",
 *              "method"="GET",
 *              "normalization_context"={"groups":{"comp:read"}},
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
 *          },
 *          "get_gpecompetence_&_competence_by_id"={
 *              "path"="/admin/grpecompetences/{id}/competences",
 *              "method"="GET",
 *              "normalization_context"={"groups":{"grpecomp:read"}},
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
 *          },
 *          "put"={
 *              "path"="/admin/grpecompetences/{id}",
 *              "route_name"="putGpeCompetence",
 *              "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *              "security_message"="Access denied !"
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
     * @Groups({"postref","referentiel:read","grpecompetence:read","grpecomp:read","comp:read","competence:write","competence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"referentiel:read","competence:read","comp:read","grpecompetence:write","grpecompetence:read","grpecomp:read","referentiel:write","promo_referentiel:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"comp:read","grpecompetence:write","grpecompetence:read","grpecomp:read","referentiel:write","promo_referentiel:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = false;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="gpeCompetences", cascade={"persist"})
     * @Groups({"grpecompetence:write","grpecompetence:read","comp:read","grpecomp:read","referentiel:write","promo_referentiel:read"})
     * @ApiSubresource()
     * @Assert\NotBlank
     */
    private $competence;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="gpeCompetence")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGpeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGpeCompetence($this);
        }

        return $this;
    }

}
