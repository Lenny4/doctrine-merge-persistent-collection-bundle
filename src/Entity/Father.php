<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lenny4\DoctrineMergePersistentCollectionBundle\Controller\PutFatherController;
use Lenny4\DoctrineMergePersistentCollectionBundle\Repository\FatherRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        'get',
    ],
    itemOperations: [
        'get',
        'put' => [
            'controller' => PutFatherController::class,
        ],
    ],
    attributes: [
        'normalization_context' => ['groups' => ['r-father']],
        'denormalization_context' => ['allow_extra_attributes' => false, 'groups' => ['w-father']],
    ],
)]
#[ORM\Entity(repositoryClass: FatherRepository::class)]
class Father
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['w-father'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'father', targetEntity: Son::class, orphanRemoval: true)]
    #[Groups(['w-father'])]
    private Collection $sons;

    public function __construct()
    {
        $this->sons = new ArrayCollection();
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

    /**
     * @return Collection<int, Son>
     */
    public function getSons(): Collection
    {
        return $this->sons;
    }

    public function addSon(Son $son): self
    {
        if (!$this->sons->contains($son)) {
            $this->sons->add($son);
            $son->setFather($this);
        }

        return $this;
    }

    public function removeSon(Son $son): self
    {
        if ($this->sons->removeElement($son)) {
            // set the owning side to null (unless already changed)
            if ($son->getFather() === $this) {
                $son->setFather(null);
            }
        }

        return $this;
    }
}
