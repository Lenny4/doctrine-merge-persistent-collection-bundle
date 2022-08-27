<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Lenny4\DoctrineMergePersistentCollectionBundle\Entity\Father;
use Lenny4\DoctrineMergePersistentCollectionBundle\Entity\Son;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $father = new Father();
        $father->setName('father1');
        $son1 = new Son();
        $son1
            ->setName('son1')
            ->setAge(10);
        $son2 = new Son();
        $son2
            ->setName('son2')
            ->setAge(20);
        $manager->flush();
    }
}
