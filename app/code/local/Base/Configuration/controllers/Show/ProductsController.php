<?php

class Base_Configuration_Show_ProductsController extends Mage_Core_Controller_Front_Action
{
   public function indexAction()
   {
       $this->loadLayout();
       echo 'my action';
       $this->renderLayout();
   }
}
