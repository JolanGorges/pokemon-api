<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
class Species
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'create', 'update'])]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'parent_id')]
    #[Groups(['read'])]
    private ?self $child_id = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'child_id')]
    #[Groups(['read'])]
    private Collection $parent_id;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'species')]
    #[Groups(['read', 'create', 'update'])]
    private Collection $types;

    /**
     * @var Collection<int, Ability>
     */
    #[ORM\ManyToMany(targetEntity: Ability::class, inversedBy: 'species')]
    #[Groups(['read', 'create', 'update'])]
    private Collection $abilities;

    public function __construct()
    {
        $this->parent_id = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->abilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getChildId(): ?self
    {
        return $this->child_id;
    }

    public function setChildId(?self $child_id): static
    {
        $this->child_id = $child_id;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentId(): Collection
    {
        return $this->parent_id;
    }

    public function addParentId(self $parentId): static
    {
        if (!$this->parent_id->contains($parentId)) {
            $this->parent_id->add($parentId);
            $parentId->setChildId($this);
        }

        return $this;
    }

    public function removeParentId(self $parentId): static
    {
        if ($this->parent_id->removeElement($parentId)) {
            // set the owning side to null (unless already changed)
            if ($parentId->getChildId() === $this) {
                $parentId->setChildId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        $this->types->removeElement($type);

        return $this;
    }

    /**
     * @return Collection<int, Ability>
     */
    public function getAbilities(): Collection
    {
        return $this->abilities;
    }

    public function addAbility(Ability $ability): static
    {
        if (!$this->abilities->contains($ability)) {
            $this->abilities->add($ability);
        }

        return $this;
    }

    public function removeAbility(Ability $ability): static
    {
        $this->abilities->removeElement($ability);

        return $this;
    }
}
