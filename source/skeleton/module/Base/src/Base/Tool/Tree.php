<?php

namespace Docapost\Base\Tool;

class Tree
{
    public static function sort($elements, $options = array())
    {
        $sortElements = array();

        foreach ($elements as $element) {
            if (!$element instanceof TreeInterface) {
                throw new \Docapost\Base\Exception\Exception('$elements must be an Array of \Docapost\Base\Tool\TreeInterface');
            }

            if (!$element->getParent()) {
                $element->setDepth(0);
                $sortElements = array_merge($sortElements, self::loadChildren($element));
            }
        }

        return $sortElements;
    }

    protected static function loadChildren(TreeInterface $element)
    {
        $elements = array();
        $elements[] = $element;

        foreach ($element->getChildren() as $child) {
            /* @var $child \Docapost\Base\Tool\TreeInterface */
            $child->setDepth($element->getDepth() + 1);
            $elements = array_merge($elements, self::loadChildren($child));
        }

        return $elements;
    }
}