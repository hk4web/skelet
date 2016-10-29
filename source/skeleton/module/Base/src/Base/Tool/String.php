<?php

namespace Docapost\Base\Tool;

class String
{
    public static function Camelize($string)
    {
        \Zend\Filter\Word\UnderscoreToCamelCase::hasPcreUnicodeSupport();
    }

    public static function UnCamelize($string, $separator = '_')
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', $separator.'$0', $string)), $separator);
    }

    public static function replace($string, $params = array(), $delimiter = '%')
    {
        $keys = array();
        $values = array();

        foreach ($params as $key => $value) {
            $keys[] = $delimiter . $key . $delimiter;
            $values[] = $value;
        }

        return str_replace($keys, $values, $string);
    }
}