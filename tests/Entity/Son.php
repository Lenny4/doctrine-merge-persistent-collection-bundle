<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle\Tests\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Lenny4\DoctrineMergePersistentCollectionBundle\Tests\Repository\SonRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        'get',
    ],
    itemOperations: [
        'get',
    ],
    attributes: [
        'normalization_context' => ['groups' => ['r-son']],
        'denormalization_context' => ['allow_extra_attributes' => false, 'groups' => ['w-son']],
    ],
)]
#[ORM\Entity(repositoryClass: SonRepository::class)]
class Son
{
    #[ORM\Column]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['w-father'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['w-father'])]
    private ?int $age = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'sons')]
    private ?Father $father = null;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getFather(): ?Father
    {
        return $this->father;
    }

    public function setFather(?Father $father): self
    {
        $this->father = $father;

        return $this;
    }
}
