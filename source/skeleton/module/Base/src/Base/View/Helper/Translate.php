<?php

namespace Docapost\Base\View\Helper;

use Zend\I18n\Exception\RuntimeException;
use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class Translate extends AbstractTranslatorHelper
{
    public function __invoke($message, $textDomain = null, $locale = null, $params = null, $default = null)
    {
        return $this->render($message, $textDomain, $locale, $params, $default);
    }

    public function render($message, $textDomain = null, $locale = null, $params = null, $default = null)
    {
        $translator = $this->getTranslator();
        if (null === $translator) {
            throw new RuntimeException('Translator has not been set');
        }
        if (null === $textDomain) {
            $textDomain = $this->getTranslatorTextDomain();
        }

        $search = $replace = array();
        if (null !== $params) {
            foreach ($params as $key => $value) {
                if (!is_object($value)) {
                    $search[] = "%$key%";
                    $replace[] = $value ? $value : '';
                }
            }
        }

        $message = str_replace($search, $replace, $message);
        $translation = $translator->translate($message, $textDomain, $locale);
        if ($translation == $message && !empty($default)) {
            $translation = $default;
        }

        return str_replace($search, $replace, $translation);
    }
}