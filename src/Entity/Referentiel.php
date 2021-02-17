<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')",
 *          "securityMessage"="Access denied !"
 *     },
 *     denormalizationContext={"groups":{"referentiel:write"}},
 *     collectionOperations={
 *          "get_ref_&_gpecompetence"={
 *              "path"="/admin/referentiels",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"referentiel:read"}}
 *          },
 *          "get_gpecompetence_&_competence"={
 *              "path"="/admin/referentiels/grpecompetences",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"gpecompetence_competence:read"}}
 *          },
 *          "addReferentiel"={
 *              "path"="/admin/referentiels",
 *              "route_name"="addReferentiel",
 *              "method"="POST",
 *              "denormalization_context"={"groups"={"postref"}}
 *          }
 *     },
 *     itemOperations={
 *          "get_ref_by_id_&_gpecompetence"={
 *              "path"="/admin/referentiels/{id}",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"referentiel:read"}}
 *          },
 *          "put"={"path"="/admin/referentiels/{id}"},
 *          "delete"={"path"="/admin/referentiels/{id}"}
 *     },
 *     subresourceOperations={
 *          "api_referentiel_gpe_competence_get_subresource"={
 *              "method"="GET",
 *              "path"="/admin/referentiels/{id}/grpecompetences",
 *              "normalization_context"={"groups"={"foobar"}}
 *          }
 *     }
 *
 * )
 * @UniqueEntity(
 *     "libelle",
 *     message="Fall Bindeul lénéne!"
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"postref","referentiel:read","referentiel:write","groupe:read","promo:read","promo_principal:read","promo_referentiel:read","promo_formateur:read","promo_attente:read","promo_ref:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"postref","referentiel:read","referentiel:write","groupe:read","promo:read","promo_principal:read","promo_referentiel:read","promo_formateur:read","promo_attente:read","promo_ref:write"})
     * @Assert\NotBlank
     */
    private $libelle;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"postref","referentiel:write", "referentiel:read"})
     */
    private $programme;

    /**
     * @ORM\ManyToMany(targetEntity=GpeCompetence::class, inversedBy="referentiels", cascade="persist")
     * @Groups({"postref","referentiel:read","referentiel:write","promo_referentiel:read"})
     * @ApiSubresource()
     */
    private $gpeCompetence;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"postref","referentiel:read","referentiel:write","groupe:read","promo:read","promo_principal:read","promo_referentiel:read","promo_formateur:read","promo_attente:read","promo_ref:write"})
     * @Assert\NotBlank
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"postref","referentiel:read","referentiel:write","groupe:read","promo:read","promo_principal:read","promo_referentiel:read","promo_formateur:read","promo_attente:read","promo_ref:write"})
     * @Assert\NotBlank
     */
    private $admission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"postref","referentiel:read","referentiel:write","groupe:read","promo:read","promo_principal:read","promo_referentiel:read","promo_formateur:read","promo_attente:read","promo_ref:write"})
     * @Assert\NotBlank
     */
    private $evaluation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = false;

    public function __construct()
    {
        $this->gpeCompetence = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    public function getProgramme(): ?string
    {
        if ($this->programme) {
            $programme_str = stream_get_contents($this->programme);
            return base64_encode($programme_str);
        }
        return null;
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    /**
     * @return Collection|GpeCompetence[]
     */
    public function getGpeCompetence(): Collection
    {
        return $this->gpeCompetence;
    }

    public function addGpeCompetence(GpeCompetence $gpeCompetence): self
    {
        if (!$this->gpeCompetence->contains($gpeCompetence)) {
            $this->gpeCompetence[] = $gpeCompetence;
        }

        return $this;
    }

    public function removeGpeCompetence(GpeCompetence $gpeCompetence): self
    {
        $this->gpeCompetence->removeElement($gpeCompetence);

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getAdmission(): ?string
    {
        return $this->admission;
    }

    public function setAdmission(string $admission): self
    {
        $this->admission = $admission;

        return $this;
    }

    public function getEvaluation(): ?string
    {
        return $this->evaluation;
    }

    public function setEvaluation(string $evaluation): self
    {
        $this->evaluation = $evaluation;

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
