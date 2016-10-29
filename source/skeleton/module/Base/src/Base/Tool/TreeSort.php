<?php

namespace Docapost\Base\Tool;

class TreeSort
{
    public static function sort($elements, $options = array())
    {
        $groups = self::prepareElements($elements);

        $list = array();
        foreach ($groups['0'] as $root) {
            $list = array_merge($list, self::loadChildren($root, $groups));
        }

        return $list;
    }

    protected static function prepareElements($elements)
    {
        $groups = array();
        foreach ($elements as $element) {
            $groupId = $element->getParent() ? $element->getParent()->getId() : 0;
            $groups["$groupId"][] = $element;
        }

        return $groups;
    }

    protected static function loadChildren($parent, $groups)
    {
        $elements = array($parent);

        $parentId = $parent->getId();
        if (isset($groups["$parentId"])) {
            foreach ($groups[$parent->getId()] as $child) {
                $elements = array_merge($elements, self::loadChildren($child, $groups));
            }
        }

        return $elements;
    }
}