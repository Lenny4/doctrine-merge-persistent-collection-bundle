<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle\Controller;

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
                return $son1->setAge($son2->getAge());
            },
        );
        return $data;
    }
}
