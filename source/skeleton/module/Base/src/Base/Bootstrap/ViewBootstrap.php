<?php

namespace Base\Bootstrap;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class ViewBootstrap implements BootstrapInterface
{
    /**
     * @var MvcEvent
     */
    protected $event;

    /**
     * @param MvcEvent $event
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
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
     * @return string
     */
    protected function getApplicationName()
    {
        $config = $this->getConfig();

        if (isset($config['application']['name'])) {
            return $config['application']['name'];
        }

        return 'Application';
    }

    /**
     * @return string
     */
    protected function getVersion()
    {
        if ($this->getServiceManager()->has('Version')) {
            return $this->getServiceManager()->get('Version');
        }

        $config = $this->getConfig();
        if (isset($config['application']['version']) && $config['application']['version']) {
            return $config['application']['version'];
        }

        return '0.0.0';
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

        $this->initSession()
             ->initDoctype()
             ->initHeadLink()
             ->initHeadMeta()
             ->initHeadScript()
             ->initHeadTitle()
             ->initVariables();
    }

    /**
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
     */
    protected function initSession()
    {
        $this->getServiceManager()->get('Session');
        return $this;
    }

    /**
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
     */
    protected function initDoctype()
    {
        $this->getView()->doctype('XHTML5');
        return $this;
    }

    /**
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
     */
    protected function initHeadLink()
    {
        $config = $this->getConfig();

        //  Favicon Link
        $favicon = '/images/favicon.ico';
        if (isset($config['application']['favicon']) && $config['application']['favicon']) {
            $favicon = $config['application']['favicon'];
        }

        $headLink = $this->getView()->headLink(array(
            'rel'  => 'shortcut icon',
            'type' => 'image/vnd.microsoft.icon',
            'href' => $favicon
        ));

        //  Stylesheet Loading
        if (isset($config['application']['files']['css']) && is_array($config['application']['files']['css'])) {
            foreach ($config['application']['files']['css'] as $file) {
                if (is_array($file) && isset($file['href']) && $file['href']) {
                    $href = $file['href'] . '?' . $this->getVersion();
                    $media = (isset($file['media']) && $file['media']) ? $file['media'] : 'screen';
                    $conditional = (isset($file['conditional']) && $file['conditional']) ? $file['conditional'] : '';
                    $extras = (isset($file['extras']) && is_array($file['extras'])) ? $file['extras'] : array();
                    $headLink->appendStylesheet($href, $media, $conditional, $extras);
                } elseif ($file) {
                    $headLink->appendStylesheet($file . '?' . $this->getVersion());
                }
            }
        }

        return $this;
    }

    /**
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
     */
    protected function initHeadMeta()
    {
        $this->getView()->headMeta()
                        ->appendName('viewport', 'width=device-width, initial-scale=1.0')
                        ->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8')
                        ->appendHttpEquiv('X-UA-Compatible', 'IE=edge');

        return $this;
    }

    /**
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
     */
    protected function initHeadScript()
    {
        $config = $this->getConfig();

        $headScript = $this->getView()->headScript();

        //  Script Loading
        if (isset($config['application']['files']['js']) && is_array($config['application']['files']['js'])) {
            foreach ($config['application']['files']['js'] as $file) {
                if (is_array($file) && isset($file['href']) && $file['href']) {
                    $href = $file['href'] . '?' . $this->getVersion();
                    $type = (isset($file['type']) && $file['type']) ? $file['type'] : 'text/javascript';
                    $attributes = (isset($file['attributes']) && is_array($file['attributes'])) ? $file['attributes'] : array();
                    $headScript->appendFile($href, $type, $attributes);
                } elseif ($file) {
                    $headScript->appendFile($file . '?' . $this->getVersion());
                }
            }
        }

        return $this;
    }

    /**
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
     */
    protected function initHeadTitle()
    {
        $this->getView()->headTitle($this->getApplicationName())
                        ->setSeparator(' - ')
                        ->setAutoEscape(false);

        return $this;
    }

    /**
     * @return \Docapost\Base\Bootstrap\ViewBootstrap
     */
    protected function initVariables()
    {
        $config = $this->getConfig();

        //  Application Owner
        $applicationOwner = '';
        if (isset($config['application']['owner']) && $config['application']['owner']) {
            $applicationOwner = $config['application']['owner'];
        }
        //  Application Year
        $applicationYear = date('Y');
        if (isset($config['application']['year']) && $config['application']['year']) {
            $applicationYear = $config['application']['year'];
        }

        $this->getEvent()->getViewModel()->setVariables(array(
            'applicationName'    => $this->getApplicationName(),
            'applicationOwner'   => $applicationOwner,
            'applicationYear'    => $applicationYear,
            'applicationVersion' => $this->getVersion(),
        ));

        return $this;
    }
}