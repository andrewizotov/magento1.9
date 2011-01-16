<?php
class Certification_Basic_Model_Observer
{
    public function layoutRenderBeforeCatalogCategoryView(Varien_Event_Observer $observer)
    {
    }

    public function deleteProductAfterDone(Varien_Event_Observer $observer, $args)
    {
      /* Mage::log('here',null,'base.log');
       Mage::log(get_class($observer),null,'base.log');
       Mage::log($args,null,'base.log');*/
    }

    public function catalogProductLoadBefore(Varien_Event_Observer $observer)
    {
         // Mage::log($observer->getEvent()->getObject(),null,'base.log');
    }
}