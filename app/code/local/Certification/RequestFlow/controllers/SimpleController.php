<?php

class Certification_RequestFlow_SimpleController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
       $this->loadLayout();
       $this->renderLayout();
    }
}