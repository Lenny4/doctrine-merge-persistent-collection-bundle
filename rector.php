<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\OrderAttributesRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

// https://github.com/rectorphp/rector-symfony
// https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md
return static function (RectorConfig $rectorConfig): void {
    // https://github.com/rectorphp/rector/blob/main/docs/auto_import_names.md
    $services = $rectorConfig->services();
    $rectorConfig->importNames();
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    $rectorConfig->parallel();
    $rectorConfig->skip([StringClassNameToClassConstantRector::class => [__DIR__ . '/config']]);
    $rectorConfig->ruleWithConfiguration(StringClassNameToClassConstantRector::class, ['Symfony\\*', 'Twig_*', 'Swift_*', 'Doctrine\\*', 'Gedmo\\*', 'Knp\\*', 'DateTime', 'DateTimeInterface']);
    // /vendor/rector/rector/vendor/rector/rector-symfony/rector.php
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SymfonySetList::SYMFONY_60,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        DoctrineSetList::DOCTRINE_GEDMO_TO_KNPLABS,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::DOCTRINE_BEHAVIORS_20,
        DoctrineSetList::DOCTRINE_COMMON_20,
        DoctrineSetList::DOCTRINE_ORM_29,
        DoctrineSetList::DOCTRINE_DBAL_30,
    ]);
    $rectorConfig->ruleWithConfiguration(OrderAttributesRector::class, [OrderAttributesRector::ALPHABETICALLY]);
    $services->remove(ClassPropertyAssignToConstructorPromotionRector::class);
    $services->remove(ClosureToArrowFunctionRector::class);
};
