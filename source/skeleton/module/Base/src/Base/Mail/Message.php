<?php

namespace Docapost\Base\Mail;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Message extends \Zend\Mail\Message implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $config;

    public function init()
    {
        $sender = $this->getConfig('sender');
        if (is_array($sender)) {
            foreach ($sender as $name => $address) {
                if (!is_string($name)) {
                    $name = null;
                }
                $this->addFrom($address, $name);
            }
        }
    }

    public function setSubject($subject)
    {
        if (($application = $this->getConfig('application'))) {
            $subject = "[$application] - $subject";
        }
        return parent::setSubject($subject);
    }

    protected function getConfig($key = null)
    {
        if ($this->config === null) {
            $globalConfig = $this->getServiceLocator()->get('Config');
            $config = (isset($globalConfig['email']) && is_array($globalConfig['email'])) ? $globalConfig['email'] : [];
            if (isset($config['application']) && $config['application']) {
            } elseif (isset($globalConfig['application']['name']) && $globalConfig['application']['name']) {
                $config['application'] = $globalConfig['application']['name'];
            }
            $this->config = $config;
        }

        if ($key) {
            return isset($this->config[$key]) ? $this->config[$key] : null;
        }

        return $this->config;
    }
}