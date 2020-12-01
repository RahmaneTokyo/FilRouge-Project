<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"admin"="Admin", "apprenant"="Apprenant", "cm"="Cm", "formateur"="Formateur", "user"="User"})
 * @ApiFilter(SearchFilter::class, properties={"archived": "exact"})
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "securityMessage"="Access denied please log as Admin !"
 *     },
 *     collectionOperations={
 *          "get"={"path"="/admin/users"},
 *          "addUser"={
 *              "path"="/admin/users",
 *              "route_name"="addUser"
 *          }
 *     },
 *     itemOperations={
 *          "get"={"path"="/admin/users/{id}"},
 *          "updateUser"={
 *              "path"="/admin/users/{id}",
 *              "route_name"="updateUser"
 *          },
 *          "delete"={"path"="/admin/users/{id}"}
 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read","profiluser:read","apprenant:read","formateur:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read","profiluser:read","apprenant:read","formateur:read"})
     * @Assert\Email(
     *     message="This is not a valid email"
     * )
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $email;

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","profiluser:read","apprenant:read","formateur:read"})
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","profiluser:read","apprenant:read","formateur:read"})
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","profiluser:read","apprenant:read","formateur:read"})
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $archived = false;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read"})
     * @Assert\NotBlank(
     *     message="This fiels cannot be null !"
     * )
     */
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}
