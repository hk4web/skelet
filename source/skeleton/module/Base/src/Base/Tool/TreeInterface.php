<?php

namespace Docapost\Base\Tool;

interface TreeInterface
{
    public function getDepth();
    public function setDepth($depth);
    public function getParent();
    public function getChildren();
}