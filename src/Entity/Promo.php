<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromoRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @UniqueEntity(
 *     "libelle",
 *     message="Bindeul lénéne"
 * )
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN') || is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM') ",
 *          "securityMessage"="Access denied !"
 *     },
 *     collectionOperations={
 *          "get_ref_formateur_gpe"={
 *              "path"="/admin/promo",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo:read"}}
 *          },
 *          "get_ref_formateur_apprenant"={
 *              "path"="/admin/promo/principal",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo_principal:read"}}
 *          },
 *          "get_apprenant_attente"={
 *              "path"="/admin/promo/apprenants/attente",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo_attente:read"}}
 *          },
 *          "post"={
 *              "path"="/admin/promo",
 *              "denormalization_context"={"groups"={"promo:write"}}
 *          }
 *     },
 *     itemOperations={
 *          "get_ref_formateur_gpe_of_one_promo"={
 *              "path"="/admin/promo/{id}",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo:read"}}
 *          },
 *          "get_ref_formateur_apprenant_of_one_promo"={
 *              "path"="/admin/promo/{id}/principal",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo_principal:read"}}
 *          },
 *          "get_ref_promo_gpecompetence_competence_of_one_promo"={
 *              "path"="/admin/promo/{id}/referentiels",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo_referentiel:read"}}
 *          },
 *          "get_apprenant_attente_of_one_promo"={
 *              "path"="/admin/promo/{id}/apprenants/attente",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo_attente:read"}}
 *          },
 *          "get_ref_formateur_groupe_of_one_promo"={
 *              "path"="/admin/promo/{id}/formateurs",
 *              "method"="GET",
 *              "normalization_context"={"groups"={"promo_formateur:read"}}
 *          },
 *          "put_promo_&_ref"={
 *              "path"="/admin/promo/{id}/referentiels",
 *              "method"="PUT",
 *              "denormalization_context"={"groups"={"promo_ref:write"}},
 *          },
 *          "put_apprenant"={
 *              "path"="/admin/promo/{id}/apprenants",
 *              "method"="PUT",
 *              "denormalization_context"={"groups"={"promo_apprenant:write"}}
 *          },
 *          "put_formateur"={
 *              "path"="/admin/promo/{id}/formateurs",
 *              "method"="PUT",
 *              "denormalization_context"={"groups"={"promo_formateur:write"}}
 *          },
 *
 *     }
 * )
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"groupe:read","promo:write","promo_referentiel:read","promo_attente:read","promo_ref:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promo:write","promo_referentiel:read","promo_attente:read","promo_ref:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promo:write","promo_referentiel:read","promo_ref:write"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promo:write","promo_referentiel:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promo:write","promo_referentiel:read"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promo:write","promo_referentiel:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promo:write","promo_referentiel:read"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="date")
     * @Groups({"groupe:read","promo:write","promo_referentiel:read"})
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="date")
     * @Groups({"groupe:read","promo:write","promo_referentiel:read"})
     */
    private $dateFinReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos", cascade="persist")
     * @Groups({"groupe:read","promo:read","promo_principal:read","promo_referentiel:read","promo_formateur:read","promo_ref:write"})
     */
    private $referentiel;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo", cascade={"persist"})
     * @Groups({"promo:read","promo_principal:read","promo:write","promo_formateur:read","promo_formateur:write"})
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo")
     * @Groups({"promo_attente:read","promo_apprenant:write"})
     */
    private $apprenant;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateFinProvisoire(): ?DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getDateFinReelle(): ?DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
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
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

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
            $apprenant->setPromo($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromo() === $this) {
                $apprenant->setPromo(null);
            }
        }

        return $this;
    }
}
