<?php
require Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'CartController.php';

class Certification_RequestFlow_Checkout_CartController extends Mage_Checkout_CartController
{
  public function indexAction()
  {
      parent::indexAction();

  }
}