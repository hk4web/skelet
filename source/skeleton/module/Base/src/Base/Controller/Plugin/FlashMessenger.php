<?php

namespace Docapost\Base\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\FlashMessenger as ZendFlashMessenger;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class FlashMessenger extends ZendFlashMessenger implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected function translate($message)
    {
        /* @var $viewHelperManager \Zend\View\HelperPluginManager */
        $viewHelperManager = $this->getServiceLocator()->getServiceLocator()->get('viewHelperManager');
        if (!$viewHelperManager->has('Translate')) {
            return $message;
        }

        return $viewHelperManager->get('Translate')->render($message);
    }

    /**
     * Add a message
     *
     * @param  string         $message
     * @param  null|string    $namespace
     * @param  null|int       $hops
     * @param  null|array     $params
     * @return FlashMessenger Provides a fluent interface
     */
    public function addMessage($message, $namespace = null, $hops = 1, $params = null)
    {
        $search = $replace = array();
        if (null !== $params) {
            foreach ($params as $key => $value) {
                $search[] = "%$key%";
                $replace[] = $value;
            }
        }

        parent::addMessage(str_replace($search, $replace, $this->translate($message)), $namespace, $hops);
    }

    /**
     * Add a message with "info" type
     *
     * @param  string         $message
     * @param  null|array     $params
     * @return FlashMessenger
     */
    public function addInfoMessage($message, $params = null)
    {
        $this->addMessage($message, self::NAMESPACE_INFO, null, $params);

        return $this;
    }

    /**
     * Add a message with "success" type
     *
     * @param  string         $message
     * @param  null|array     $params
     * @return FlashMessenger
     */
    public function addSuccessMessage($message, $params = null)
    {
        $this->addMessage($message, self::NAMESPACE_SUCCESS, null, $params);

        return $this;
    }

    /**
     * Add a message with "warning" type
     *
     * @param  string         $message
     * @param  null|array     $params
     * @return FlashMessenger
     */
    public function addWarningMessage($message, $params = null)
    {
        $this->addMessage($message, self::NAMESPACE_WARNING, null, $params);

        return $this;
    }

    /**
     * Add a message with "error" type
     *
     * @param  string         $message
     * @param  null|array     $params
     * @return FlashMessenger
     */
    public function addErrorMessage($message, $params = null)
    {
        $this->addMessage($message, self::NAMESPACE_ERROR, null, $params);

        return $this;
    }
}