<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 * @ApiResource(
 *     denormalizationContext={"groups":{"niveau:write"}}
 * )
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"competence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"competence:read","competence:write","niveau:write","grpecompetence:write","grpecompetence:read","referentiel:write"})
     * @Assert\NotBlank
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:read","competence:write","niveau:write","grpecompetence:write","grpecompetence:read","referentiel:write"})
     * @Assert\NotBlank
     */
    private $admission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:read","competence:write","niveau:write","grpecompetence:write","grpecompetence:read","referentiel:write"})
     * @Assert\NotBlank
     */
    private $evaluation;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competence:write"})
     */
    private $archived = false;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveau", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $competence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

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

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

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
}
