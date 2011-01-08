<?php
require  Mage::getModuleDir('controllers','Mage_Customer').DS.'AccountController.php';

class Base_Configuration2_AccountController extends Mage_Customer_AccountController
{
    public function indexAction()
    {   echo 'success 2';
        $this->loadLayout();

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Fucked Account2'));
        $this->renderLayout();
    }
}
