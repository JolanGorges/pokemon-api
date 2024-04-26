<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AbilityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AbilityRepository::class)]
class Ability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'create', 'update'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['read', 'create', 'update'])]
    private ?int $damage = null;

    #[ORM\Column]
    #[Groups(['read', 'create', 'update'])]
    private ?int $pp = null;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\OneToMany(targetEntity: Type::class, mappedBy: 'abilities')]
    #[Groups(['read', 'create', 'update'])]
    private Collection $type;

    /**
     * @var Collection<int, Species>
     */
    #[ORM\ManyToMany(targetEntity: Species::class, mappedBy: 'abilities')]
    #[Groups(['read', 'create', 'update'])]
    private Collection $species;

    public function __construct()
    {
        $this->type = new ArrayCollection();
        $this->species = new ArrayCollection();
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

    public function getDamage(): ?int
    {
        return $this->damage;
    }

    public function setDamage(int $damage): static
    {
        $this->damage = $damage;

        return $this;
    }

    public function getPp(): ?int
    {
        return $this->pp;
    }

    public function setPp(int $pp): static
    {
        $this->pp = $pp;

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Type $type): static
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
            $type->setAbilities($this);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        if ($this->type->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getAbilities() === $this) {
                $type->setAbilities(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Species>
     */
    public function getSpecies(): Collection
    {
        return $this->species;
    }

    public function addSpecies(Species $species): static
    {
        if (!$this->species->contains($species)) {
            $this->species->add($species);
            $species->addAbility($this);
        }

        return $this;
    }

    public function removeSpecies(Species $species): static
    {
        if ($this->species->removeElement($species)) {
            $species->removeAbility($this);
        }

        return $this;
    }
}
