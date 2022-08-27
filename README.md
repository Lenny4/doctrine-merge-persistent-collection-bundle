[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Merge persistence collection when update entity

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lenny4/doctrine-merge-persistent-collection-bundle.svg?style=flat-square)](https://packagist.org/packages/lenny4/doctrine-merge-persistent-collection-bundle)
[![Tests](https://github.com/lenny4/doctrine-merge-persistent-collection-bundle/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/lenny4/doctrine-merge-persistent-collection-bundle/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/lenny4/doctrine-merge-persistent-collection-bundle.svg?style=flat-square)](https://packagist.org/packages/lenny4/doctrine-merge-persistent-collection-bundle)

This bundle is very usefull if you are using [Api Platform](https://api-platform.com/docs/distribution/) and you don't
want to send multiple requests when you update an entity and the entity children.

## Installation

You can install the package via composer:

```bash
composer require lenny4/doctrine-merge-persistent-collection-bundle
```

## Usage

```php
class PutFatherController extends AbstractController
{
    public function __invoke(Father $data, DoctrineMergePersistentCollection $doctrineMergePersistentCollection): Father
    {
        $doctrineMergePersistentCollection->mergePersistentCollection(
            $data->getSons(),
            static function (Son $son1, Son $son2) {
                return $son1->getName() === $son2->getName();
            },
            static function (Son $son1, Son $son2) {
                return $son1->setAge($son2->getAge());
            },
        );
        return $data;
    }
}
```

## Testing

```bash
docker-compose up
./runc composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
