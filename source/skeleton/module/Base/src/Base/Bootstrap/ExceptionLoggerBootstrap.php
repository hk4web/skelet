<?php

namespace Base\Bootstrap;

use Zend\Mvc\MvcEvent;

class ExceptionLoggerBootstrap implements BootstrapInterface
{
    /**
     * @var MvcEvent
     */
    protected $event;

    /**
     * @param MvcEvent $event
     * @return \Florian1987\Base\Bootstrap\NavigationBootstrap
     */
    protected function setEvent(MvcEvent $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return $this->getServiceManager()->get('Config');
    }

    /**
     * @return MvcEvent
     */
    protected function getEvent()
    {
        return $this->event;
    }

    /**
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

    public function init(MvcEvent $event)
    {
        $this->setEvent($event);

        /* @var $eventManager \Zend\EventManager\EventManager */
        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'initExceptionLogger'));
    }

    public function initExceptionLogger()
    {
        $exception = $this->getEvent()->getResult()->exception;

        if ($exception instanceof \Exception) {
            /* @var $toolException \Docapost\Base\Tool\Exception */
            $toolException = $this->getServiceManager()->get('Tool.Exception');
            $toolException->log($exception);
        }
    }
}