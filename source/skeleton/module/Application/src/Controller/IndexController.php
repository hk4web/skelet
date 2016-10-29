<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
//        $this->flashMessenger()->addErrorMessage('Erreur avec le système.');
//        $this->flashMessenger()->addInfoMessage('Info message');

        //$smsService = $this->getServiceLocator()->get('ZfSmsZillaService');




        return new ViewModel();
    }
    public function smsAction() {
        /**
         * @var \ZfSmsZilla\Service\SenderService
         */
        $smsService = $this->getServiceLocator()->get('ZfSmsZillaService');
        $smsService->send('Lorem ipsum 1', ['+33626700645']);
        $smsService->send('Lorem ipsum 2', ['605123456', '509546985']);

        // if you need more, e.g.
        $sender = $smsService->getSender();
        $errors = $sender->getAdapter()->getErrors();
        $sender->setValidator(new SmsZilla\Validator\LibphonenumberValidator('FR'));
    }
}
