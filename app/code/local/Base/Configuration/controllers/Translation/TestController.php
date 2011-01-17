<?php

class Base_Configuration_Translation_TestController extends Mage_Core_Controller_Front_Action
{
    public function testAction()
    {

        echo $this->__('my word');
    }
}