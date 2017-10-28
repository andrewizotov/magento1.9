<?php

require Mage::getModuleDir('controllers','Mage_Checkout').DS.'CartController.php';

class Base_Configuration_CartController extends Mage_Checkout_CartController
{
    public function indexAction()
    {
        echo __METHOD__.'<br>';
        parent::indexAction();
    }
}