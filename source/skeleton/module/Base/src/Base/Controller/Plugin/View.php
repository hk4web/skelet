<?php

namespace Docapost\Base\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Exception\DomainException;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Model\ModelInterface as Model;

class View extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var MvcEvent
     */
    protected $event;

    /**
     * @return View
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * Return all view params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->getViewModel()->getVariable('params', array());
    }

    /**
     *
     * @param array $params
     * @return \Docapost\Base\Controller\Plugin\View
     */
    public function setParams($params)
    {
        foreach ($params as $param => $value) {
            $this->setParam($param, $value);
        }
        return $this;
    }

    /**
     * Return a view param
     *
     * @param string $param
     * @return mixed
     */
    public function getParam($param)
    {
        $params = $this->getViewModel()->getVariable('params');
        return isset($params[$param]) ? $params[$param] : null;
    }

    /**
     * Define a view param
     *
     * @param string $param
     * @param mixed $value
     * @return View
     */
    public function setParam($param, $value)
    {
        $params = $this->getViewModel()->getVariable('params');
        $params[$param] = $value;
        $this->getViewModel()->setVariable('params', $params);
        return $this;
    }

    /**
     * Get the event
     *
     * @return MvcEvent
     * @throws DomainException if unable to find event
     */
    protected function getEvent()
    {
        if ($this->event) {
            return $this->event;
        }

        $controller = $this->getController();
        if (!$controller instanceof InjectApplicationEventInterface) {
            throw new DomainException('Layout plugin requires a controller that implements InjectApplicationEventInterface');
        }

        $event = $controller->getEvent();
        if (!$event instanceof MvcEvent) {
            $params = $event->getParams();
            $event  = new MvcEvent();
            $event->setParams($params);
        }
        $this->event = $event;

        return $this->event;
    }

    /**
     * Retrieve the root view model from the event
     *
     * @return Model
     * @throws DomainException
     */
    protected function getViewModel()
    {
        $event     = $this->getEvent();
        $viewModel = $event->getViewModel();
        if (!$viewModel instanceof Model) {
            throw new DomainException('Layout plugin requires that event view model is populated');
        }
        return $viewModel;
    }
}