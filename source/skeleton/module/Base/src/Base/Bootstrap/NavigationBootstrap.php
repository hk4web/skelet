<?php

namespace Base\Bootstrap;

use Docapost\Base\Tool\String;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class NavigationBootstrap implements BootstrapInterface
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

    /**
     * @return \Zend\View\Renderer\PhpRenderer
     */
    protected function getView()
    {
        return $this->getServiceManager()->get('ViewHelperManager')->getRenderer();
    }

    public function init(MvcEvent $event)
    {
        if (!$event->getRequest() instanceof \Zend\Http\Request) {
            return;
        }

        $this->setEvent($event);

        /* @var $eventManager \Zend\EventManager\EventManager */
        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'initNavigation'));
    }

    /**
     * @return \Florian1987\Base\Bootstrap\NavigationBootstrap
     */
    public function initNavigation()
    {
        if (!$this->getServiceManager()->has('Navigation')) {
            return $this;
        }

        /* @var $navigation \Zend\Navigation\Navigation */
        $navigation = $this->getServiceManager()->get('Navigation');
        $activePage = $navigation->findOneBy('active', true);
        /* @var $translator \Zend\I18n\Translator\Translator */
        $translator = $this->getServiceManager()->get('Translator');
        $viewModel = $this->getEvent()->getViewModel();
        $params = $viewModel->getVariable('params', array());

        if ($activePage instanceof \Zend\Navigation\Page\AbstractPage) {
            $label = String::replace($translator->translate($viewModel->getVariable('label', $activePage->getLabel() ? $activePage->getLabel() : '')), $params);
            $title = String::replace($translator->translate($viewModel->getVariable('title', $activePage->getTitle() ? $activePage->getTitle() : $label)), $params);
        } else {
            $title = $label = '';
        }

        if ($title) {
            $this->getView()->headTitle()->prepend($title);
        }

        $viewModel->setVariables(array(
            'label' => $label,
            'title' => $title,
        ));

        return $this;
    }
}