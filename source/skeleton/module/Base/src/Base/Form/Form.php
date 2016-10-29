<?php

namespace Docapost\Base\Form;

use Docapost\Base\Tool;

class Form extends \Zend\Form\Form
{
    protected $translatePrefix = '';

    public function __construct($name = null, $options = array())
    {
        if (isset($options['translate_prefix'])) {
            $this->translatePrefix = $options['translate_prefix'];
        } elseif ($name) {
            $this->translatePrefix = Tool\String::UnCamelize($name);
        }

        parent::__construct($name, $options);
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