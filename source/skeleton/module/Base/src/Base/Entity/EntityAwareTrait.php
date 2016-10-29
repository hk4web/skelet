<?php

namespace Docapost\Base\Entity;

trait EntityAwareTrait
{
    /**
     * @var \Doctrine\ORM\Mapping\Entity
     */
    protected $entity;

    /**
     * @return \Doctrine\ORM\Mapping\Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param \Doctrine\ORM\Mapping\Entity $entity
     * @return self
     */
    public function setEntity(\Doctrine\ORM\Mapping\Entity $entity)
    {
        $this->entity = $entity;
        return $this;
    }
}