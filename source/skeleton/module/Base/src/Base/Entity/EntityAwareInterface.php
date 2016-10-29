<?php

namespace Docapost\Base\Entity;

interface EntityAwareInterface
{
    /**
     * @return AbstractEntity
     */
    public function getEntity();

    /**
     * @param AbstractEntity $entity
     * @return self
     */
    public function setEntity(AbstractEntity $entity);
}