<?php

namespace Docapost\Base\InputFilter;

use Zend\InputFilter\InputFilter as ZendInputFilter;

class InputFilter extends ZendInputFilter
{
    protected $translatePrefix = '';

    public function __construct($name = null, $options = array())
    {
        if (isset($options['translate_prefix'])) {
            $this->translatePrefix = $options['translate_prefix'];
        } elseif ($name) {
            $this->translatePrefix = Tool\String::UnCamelize($name);
        }

		return parent::__construct();
    }

    public function getTranslatePrefix()
    {
        return $this->translatePrefix . '__';
    }

    public function setTranslatePrefix($translatePrefix)
    {
        $this->translatePrefix = $translatePrefix;
        return $this;
    }
}