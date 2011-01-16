<?php

class Certification_Basic_TranslateController extends Mage_Core_Controller_Front_Action
{
    public function testAction()
    {
        $this->loadLayout();
        echo $this->__('my module');
        $this->renderLayout();


    }
}