<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle;

use Doctrine\ORM\PersistentCollection;

class DoctrineMergePersistentCollection
{
    public function merge(
        PersistentCollection $collection,
        callable             $areIdentique,
        callable|null        $mergeElement = null
    ): void
    {
        $collection->setDirty(true);
        $collection->setInitialized(false);
        $collection->initialize();
        // region remove deleted elements
        $indexToRemove = [];
        foreach ($collection as $indexElementWithId => $elementWithId) {
            if (is_null($elementWithId->getId())) {
                continue;
            }
            $found = false;
            foreach ($collection as $indexElementNoId => $elementNoId) {
                if ($indexElementWithId === $indexElementNoId || !is_null($elementNoId->getId())) {
                    continue;
                }
                if ($areIdentique($elementWithId, $elementNoId)) {
                    $found = true;
                }
            }
            if (!$found) {
                $indexToRemove[] = $indexElementWithId;
            }
        }
        $this->removeIndexElements($indexToRemove, $collection);
        // endregion
        // region remove elements with id and same property with no id
        $indexToRemove = [];
        foreach ($collection as $indexElementWithId => $elementWithId) {
            if (is_null($elementWithId->getId())) {
                continue;
            }
            foreach ($collection as $indexElementNoId => $elementNoId) {
                if ($indexElementWithId === $indexElementNoId || !is_null($elementNoId->getId())) {
                    continue;
                }
                if ($areIdentique($elementWithId, $elementNoId)) {
                    if ($mergeElement) {
                        $mergeElement($elementWithId, $elementNoId);
                    }
                    $indexToRemove[] = $indexElementNoId;
                }
            }
        }
        $this->removeIndexElements($indexToRemove, $collection);
        // endregion
        // region remove elements with id and same property
        $indexToRemove = [];
        foreach ($collection as $indexElement1 => $element1) {
            if (is_null($element1->getId())) {
                continue;
            }
            foreach ($collection as $indexElement2 => $element2) {
                if ($indexElement1 >= $indexElement2 || is_null($element2->getId())) {
                    continue;
                }
                if ($areIdentique($element1, $element2)) {
                    $indexToRemove[] = $indexElement1;
                }
            }
        }
        $this->removeIndexElements($indexToRemove, $collection);
        // endregion

        // It doesn't work if we don't remove all elements and then add them
        $elementToAdd = $collection->toArray();
        $collection->clear();
        foreach ($elementToAdd as $element) {
            $collection->add($element);
        }
    }

    private function removeIndexElements(array $indexToRemove, PersistentCollection $collection): void
    {
        $indexToRemove = array_unique($indexToRemove);
        foreach ($collection as $index => $element) {
            if (in_array($index, $indexToRemove, true)) {
                $collection->removeElement($element);
            }
        }
    }
}
