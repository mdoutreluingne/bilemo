<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  collectionOperations={"get"},
 *  itemOperations={"get"},
 *  attributes={
 *      "pagination_items_per_page" = 10,
 *      "pagination_maximum_items_per_page" = 20,
 *  },
 * )
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Le nom ne peut pas être vide."
     * )
     * @Assert\Length(
     *     min=2,
     *     max="255",
     *     minMessage="Le nom du produit doit contenir au minimum 2 caractères.",
     *     maxMessage="Le nom du produit doit contenir au maximum 255 caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\NotBlank(
     *     message="Le prix entré ne peut être vide."
     * )
     * @Assert\Type(
     *     type="float",
     *     message="Le prix entré doit être un nombre."
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     message="L'année du produit ne peut pas être vide."
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="L'année du produit doit être un nombre."
     * )
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="La couleur ne peut pas être vide."
     * )
     * @Assert\Length(
     *     max="100",
     *     maxMessage="La couleur doit contenir au maximum 100 caractères."
     * )
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     message="La mémoire ne peut pas être vide."
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="La mémoire doit être un nombre."
     * )
     */
    private $memory;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *     min=20,
     *     minMessage="La description du produit doit contenir au minimum 20 caractères."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     message="La capacité de stockage ne peut pas être vide."
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="La capacité de stockage doit être un nombre."
     * )
     */
    private $storage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStorage(): ?int
    {
        return $this->storage;
    }

    public function setStorage(int $storage): self
    {
        $this->storage = $storage;

        return $this;
    }
}
