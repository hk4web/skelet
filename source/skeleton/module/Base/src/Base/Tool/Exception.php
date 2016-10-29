<?php

namespace Base\Tool;

class Exception implements \Zend\ServiceManager\ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    public function log(\Exception $exception)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $logger \Zend\Log\Logger */
        if ($serviceManager->has('Log.Exception')) {
            $logger = $serviceManager->get('Log.Exception');
        } else if ($serviceManager->has('Log.Application')) {
            $logger = $serviceManager->get('Log.Application');
        } else {
//            throw new Exception("No logger found");
            die('No logger found');
        }

        $trace = $exception->getTraceAsString();
        $i = 1;
        do {
            $messages[] = $i++ . ": " . $exception->getMessage();
        } while ($exception = $exception->getPrevious());

        $log = PHP_EOL . "Exception: " . PHP_EOL . implode(PHP_EOL, $messages);
        $log .= PHP_EOL . "Trace:" . PHP_EOL . $trace;

        $logger->err($log);
    }
}