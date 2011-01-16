I - Basic

1) Describe Magento codepools:
   Magento has three code pools community,local,core

2) Describe typical Magento module structure:
   for Module first of all we must have two files
   ROOT/app/code/local/Namespace/MyModule/etc/config.xml and and
   ROOT/app/etc/modules/Namespace_MyModule.xml.
   Also ...MyModule/Block,
        ...MyModule/controllers,
        ...MyModule/Model,
        ...MyModule/sql,
        ...MyModule/Controllers,
        ...MyModule/etc

3) Describe Magento templates and layout files location.
   Layouts file are located in ROOT/app/design/frontend/Package/Theme/layout
   Templates file are located in ROOT/app/design/frontend/Package/Theme/template

4) Describe Magento skin and JavaScript files location.
   Base Js files location is ROOT/js, Base skin location is ROOT/skin.
   ROOT/skin/frontend/package/theme/js
   *** see app/design/frontend/rwd/default/layout/certification_basic.xml

5) Identify and explain the main Magento design areas (adminhtml and frontend)
   Adminhtml - for admin
   frontend -  for frontend (I don't know what there I have to do)

6) Explain class naming conventions and their relationship with the autoloader
   Autoloader takes $class name as parameter , after that autoloader parse this class name,
   find "_" and replace on " " , after ucwords makes every word upper case first character
   example $class = "mage_core_model_layout"
   1) mage_core_model_layout=> Mage Core Model Layout
   2) find " "  and replace to DS "/" Mage/Core/Model/Layout
   3) include this file Mage/Core/Model/Layout.php
   4) return file Mage/Core/Model/Layout.php

7) Describe methods for resolving module conflicts
   ...

8) How does the framework interact with the various codepools?
   First magento will find class/file in local, after in community , and finally in core and lib

9) What constitutes a namespace and a module?
   modules are located in namespaces , namespaces in codePools

10) What does the structure of a complete theme look like?
    Theme has to have next components:
    ROOT/app/design/frontend/Package/Theme/etc/*.xml
    ROOT/app/design/frontend/Package/Theme/layout/*.xml (layout updates)
    ROOT/app/design/frontend/Package/Theme/template/*.* (templates)
    ROOT/app/design/frontend/Package/Theme/locale/en_US/translate.csv   (locale file)

------------------------------------------------------------------------------------------
II - Magento configuration

1) Explain how Magento loads and manipulates configuration information
   1. Mage::app()->run()
   2. in run() call baseInit() => initBaseConfig(),
   3. $this->_config->loadBase();
   4. load xml files from ROOT/app/etc/*.xml (config.xml,local.xml)
   5. load modules configuration
      1. $this->_loadDeclaredModules(); order: Mage_All (base),Mage_ (mage), Custom use for order <depends>
      2. config->loadDb() => Mage_Core_Model_Resource_Config->loadToXml()
      3. loadToXml(): loading core_website,core_store,core_config_data and extend xml with this data

2) Describe class group configuration and use in factory methods
   Magento has Model,Block,Helper and ResourceModel
   Appropriate functions in Mage:
   1.  Mage::getModel
        => config->getModelInstance($modelClass, $arguments) --- new $class
        => config->getModelClassName($modelClass)
        => config->getGroupedClassName -- check rewrite return $className
   2.  Mage::getSingleton (looking for models in registry array)
         use self::getModel put in registry
   3.  Mage::getResourceModel
         => config->getResourceModelInstance
         => getModelInstance
         =>_getResourceModelFactoryClassName (find _xml->global->models->{$module}->resourceModel) && getModelInstance (new $class)
         => getModelClassName
         => getGroupedClassName ($config->rewrite->$class)
   4.  Mage::getControllerInstance
       => new $class($request, $response, $invokeArgs);
   5.  Mage::getResourceSingleton (looking for models in registry array)
       => self::getResourceModel
   6.  Mage::getBlockSingleton (deprecated)
   7.  Mage::helper (looking in registry array)
       =>$helperClass = self::getConfig()->getHelperClassName($name);
       =>return $this->getGroupedClassName('helper', $helperName);

   8.  Mage::getResourceHelper (looking in registry array) : $this->_xml->global->resources->{$name(moduleName_setup)}->connection->use;
       =>$helperClass = self::getConfig()->getResourceHelper($moduleName);
         =>
          1. $connectionModel = $this->_getResourceConnectionModel($moduleName);
           =>
             1. $setupResource = $moduleName . '_setup';
             2. $config        = $this->getResourceConnectionConfig($setupResource);
             =>
                1. $config = $this->getResourceConfig($name); return $this->_xml->global->resources->{$name};
             3. $conn = $config->connection;
             4. if (!empty($conn->use)) return $this->getResourceConnectionConfig((string)$conn->use);
                 1. $helperClass     = sprintf('%s/helper_%s', $moduleName, $connectionModel);
          2. $helperClassName = $this->_getResourceModelFactoryClassName($helperClass);
              =>
                1. return $this->_xml->global->models->{$module}->resourceModel
          3. return $this->getModelInstance($helperClassName, $moduleName);
               =>
                1. $className = $this->getModelClassName($modelClass);
                2.  new $className.


3) Describe the process and configuration of class overrides in Magento
    <global>
       <models>
         <catalog>
            <rewrite> <!-- checking for for rewrite in 'getGroupedClassName ($config->rewrite->$class)' -->
               <product>Namespace_Module_Model_Catalog_Product</product>
            </rewrite>

4) Register an Observer
  <config>
     <global/>
     <frontend>
        <events>
          <event_name> <!-- in dispatchEvent -->
             <observers>
               <my_namespace_my_module> <!-- anything here -->
                 <type>model/object</type>
                 <class>my_namespace/observer</class>
                 <args/>
               </my_namespace_my_module>
             </observers>
          </event_name>
        </events>
     </frontend>

5) Identify the function and proper use of automatically available events, including
   *_load_after, etc.

   Function  : Mage::dispatchEvent($eventName, $data)
   class Mage_Core_Model_Abstract {
      protected $_eventPrefix = 'xxxxx';
      protected function _afterSave()
      {
          Mage::dispatchEvent('model_save_after', array('object'=>$this));
          Mage::dispatchEvent($this->_eventPrefix.'_save_after', $this->_getEventData());
      }
   }

6) Set up a cron job
   <crontab>
           <jobs>
               <write_in_log_message3>
                   <schedule>
                       <cron_expr>*/50 * * * *</cron_expr>
                   </schedule>
                   <run>
                       <model>certification_basic/cron::send</model>
                   </run>
               </write_in_log_message3>
           </jobs>
    </crontab>

7 ) How does the framework discover active modules and their
     configuration?
     1. config->loadModules()
        =>
          1. config->_loadDeclaredModules();
             =>
             1. $this->_getDeclaredModuleFiles(); [base,mage,custom]
          2. config->loadModulesConfiguration
             => if ($module->is('active')) {

8)  What are the common methods with which the framework accesses its
    configuration values and areas?
    $config->getNode(patch, scope, scopeCode),
    Mage::getStoreConfig()

9) How are per-store configuration values established in the XML DOM?
  <stores>
          <german>
              <general>
                  <country>
                      <default>UA</default>
                  </country>
                  <store_information>
                      <name>My Shope</name>
                  </store_information>
              </general>
          </german>
      </stores>

10) By what process do the factory methods and autoloader enable class
    instantiation?
    $config->getModelInstance : will do new $class
    1. $className = $this->getModelClassName($modelClass);
    2. new $className

11) Which class types have configured prefixes, and how does this relate to
    class overrides?
    Mage_Core_Model_
12) Which class types and files have explicit paths?
    Varien_

13) What configuration parameters are available for event observers?
    <type> model/object </type> <class>namespace_module/mymodel</class><class>Namespace_Module_Model_MyModel</class>
    <args/> <method/>

14) What are the interface and configuration options for automatically fired
    events?
    $_eventPrefix, $_eventObject, $observer->getEvent()->getObject()

15) What is the structure of event observers, and how are properties
    accessed therein?
    Mage::dispatchEvent($eventName,$args);
    $observer->getEvent()->getObject(), $observer->getEventName();

16) What configuration parameters are available for cron jobs?
    schedule->config_path, ->schedule->cron_expr
    <crontab>
                <jobs>
                    <write_in_log_message3>
                        <schedule>
                            <cron_expr>*/50 * * * *</cron_expr>
                        </schedule>
                        <run>
                            <model>certification_basic/cron::send</model>
                        </run>
                    </write_in_log_message3>
                </jobs>
     </crontab>





