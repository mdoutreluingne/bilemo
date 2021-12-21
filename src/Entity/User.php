<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ApiResource(
 *  normalizationContext={"groups"={"user:read"}},
 *  denormalizationContext={"groups"={"user:write"}},
 *  collectionOperations={
 *      "get"= {
 *          "openapi_context"={
 *              "summary"="Retrieves the collection of users from your company",
 *              "description"="Retrieves the collection of users from your company.",
 *           },
 *      }, 
 *      "post"={
 *          "openapi_context"={
 *              "summary"="Creates a new user resource for your company",
 *              "description"="Creates a user resource for your company.",
 *           },
 *      }
 *  },
 *  itemOperations={
 *      "get"={
 *          "security"="is_granted('USER_READ', object)",
 *          "openapi_context"={
 *              "summary"="Retrieves the the detail of a user resource from your company",
 *              "description"="Retrieves the the detail of a user resource from your company",
 *           },
 *      },
 *      "put"={
 *          "security"="is_granted('USER_EDIT', object)",
 *          "openapi_context"={
 *              "summary"="Replaces the user resource from your company",
 *              "description"="Replaces the user resource from your company",
 *           },
 *      },
 *      "delete"={
 *          "security"="is_granted('USER_DELETE', object)",
 *          "openapi_context"={
 *              "summary"="Removes the user resource from your company",
 *              "description"="Removes the user resource from your company",
 *           },
 *      },
 *  },
 *  attributes={
 *      "pagination_items_per_page" = 5,
 *      "pagination_maximum_items_per_page" = 10,
 *  },
 * )
 * @ApiFilter(SearchFilter::class, properties={"email": "partial"})
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cette adresse e-mail est déjà associée à un compte existant.")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("user:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(
     *     message="L'adresse e-mail ne peut pas être vide."
     * )
     * @Assert\Email(
     *     message="L'adresse e-mail entrée n'est pas sous un format correct."
     * )
     * 
     * @Groups({"user:read", "user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * 
     * @Groups("user:read")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups("user:write")
     * @Assert\NotBlank(
     *     message="Le mot de passe ne peut être vide."
     * )
     * @Assert\Length(
     *     min=6,
     *     minMessage="Le mot de passe doit comprendre au minimum 6 caractères."
     * )
     * 
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *     min=2,
     *     max="50",
     *     minMessage="Le prénom doit contenir au minimum 2 caractères.",
     *     maxMessage="Le prénom doit contenir au maximum 50 caractères."
     * )
     * 
     * @Groups({"user:read", "user:write"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *     min=2,
     *     max="50",
     *     minMessage="Le nom de famille doit contenir au minimum 2 caractères.",
     *     maxMessage="Le nom de famille doit contenir au maximum 50 caractères."
     * )
     * 
     * @Groups({"user:read", "user:write"})
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * 
     * @Groups("user:read")
     */
    private $customer;

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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the value of plainPassword
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
