<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Ingredients;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('name')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(1441)]
    private ?int $time = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPeople = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(6)]
    private ?int $difficulty = null;

    #[ORM\Column]
    private ?bool $isFavorite = null;

    #[ORM\Column]
    #[Assert\NotNull(type:'datetime_immutable')]
    private ?DateTimeImmutable $createAt = null;

    #[ORM\Column]
    #[Assert\NotNull(type:'datetime_immutable')]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToMany(targetEntity: Ingredients::class)]
    private Collection $ingredients;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable;
        $this->updateAt = new \DateTimeImmutable;
        $this->ingredients = new ArrayCollection();
    }

    #[ORM\PrePersist()]
    public function setUpdateAtValue()
    {
        $this->updateAt = new \DateTimeImmutable;

    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getNbPeople(): ?int
    {
        return $this->nbPeople;
    }

    public function setNbPeople(?int $nbPeople): self
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }
}
