[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Merge persistence collection when update entity

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lenny4/doctrine-merge-persistent-collection-bundle.svg?style=flat-square)](https://packagist.org/packages/lenny4/doctrine-merge-persistent-collection-bundle)
[![Tests](https://github.com/lenny4/doctrine-merge-persistent-collection-bundle/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/lenny4/doctrine-merge-persistent-collection-bundle/actions/workflows/ci.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/lenny4/doctrine-merge-persistent-collection-bundle.svg?style=flat-square)](https://packagist.org/packages/lenny4/doctrine-merge-persistent-collection-bundle)

This bundle is very usefull if you are using [ApiPlatform](https://api-platform.com/docs/distribution/) and you don't
want to send multiple requests when you update an entity and his children.

## Installation

You can install the package via composer:

```bash
composer require lenny4/doctrine-merge-persistent-collection-bundle
```

## Usage

```php
<?php

namespace App\Controller;

use Lenny4\DoctrineMergePersistentCollectionBundle\DoctrineMergePersistentCollection;
use Lenny4\DoctrineMergePersistentCollectionBundle\Entity\Father;
use Lenny4\DoctrineMergePersistentCollectionBundle\Entity\Son;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PutFatherController extends AbstractController
{
    public function __invoke(Father $data, DoctrineMergePersistentCollection $doctrineMergePersistentCollection): Father
    {
        $doctrineMergePersistentCollection->merge(
            $data->getSons(),
            static function (Son $son1, Son $son2) {
                return $son1->getName() === $son2->getName();
            },
            static function (Son $son1, Son $son2) {
                $son1->setAge($son2->getAge());
            },
        );
        return $data;
    }
}
```

## How does it work ?

Let's say you have 2 entities `Father` and `Son` as `ApiResource`

```php
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
        'denormalization_context' => ['groups' => ['w-father']],
    ],
)]
#[ORM\Entity(repositoryClass: FatherRepository::class)]
class Father
{
    #[ORM\Column]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['w-father'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Son>|Son[]
     */
    #[ORM\OneToMany(mappedBy: 'father', targetEntity: Son::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['w-father'])]
    private Collection $sons;

    // getters setters
}
```

```php
#[ApiResource()]
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

    // getters setters
}
```

With these data in your database:

| id | name    |
|----|---------|
| 1  | father1 |

| id | name | age | father_id |
|----|------|-----|-----------|
| 1  | son1 | 10  | 1         |
| 2  | son2 | 20  | 1         |

Let's say you want to update the name of the `Father` from `father1` to `father2` and the age of 1 of his
children `son1` only with 1 request. It's currently impossible only
with [ApiPlatform](https://api-platform.com/docs/distribution/). But with this Bundle you can do it now by
calling `doctrineMergePersistentCollection->merge` as shown in the Usage.

- The first argument of the function take a `PersistentCollection` which correspond to all the sons of the father
- The second argument is a callable which define how 2 sons are the same (in our example it's the
  name `$son1->getName() === $son2->getName()`)
- The second argument is a callable which define how to update a son if `$son1->getName() === $son2->getName()` return
  true;

## Use case (example)

1) Add a son `PUT /api/fathers/1`

```json
{
    "name": "father2",
    "sons": [
        {
            "name": "son1",
            "age": 10
        },
        {
            "name": "son2",
            "age": 20
        },
        {
            "name": "son3",
            "age": 30
        }
    ]
}
```

This will change the name of the father to `father2` and add a son. Result:

| id | name | age | father_id |
|----|------|-----|-----------|
| 1  | son1 | 10  | 1         |
| 2  | son2 | 20  | 1         |
| 3  | son3 | 30  | 1         |

2) Update son2 age `PUT /api/fathers/1`

```json
{
    "name": "father2",
    "sons": [
        {
            "name": "son1",
            "age": 10
        },
        {
            "name": "son2",
            "age": 30
        }
    ]
}
```

This will change the name of the father to `father2` and son2 age. Result:

| id | name | age | father_id |
|----|------|-----|-----------|
| 1  | son1 | 10  | 1         |
| 2  | son2 | 30  | 1         |

## Testing

```bash
docker-compose up
./runc composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
