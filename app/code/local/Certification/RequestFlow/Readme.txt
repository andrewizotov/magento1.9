|| - Request Flow

1) Describe the steps for application initialization

   Mage::run()
     self::$_app = new Mage_Core_Model_App();
     app->run()
       $this->baseInit();
            $this->_initEnvironment();
            $this->_initBaseConfig();
                 $this->_config->loadBase();
            $this->_initCache();
       $this->_initModules();
       $this->_initCurrentStore($scopeCode, $scopeType);
       $this->_initRequest();
       Mage_Core_Model_Resource_Setup::applyAllDataUpdates();
       $this->getFrontController()->dispatch();

2) Describe the role of the system entrypoint, index.php

    1. require_once $mageFilename;
    2. Mage::run($mageRunCode, $mageRunType);

3) How and when is the include path set up and the auto loader registered?
    it happened in Mage.php
    set_include_path($appPath . PS . Mage::registry('original_include_path'));
    'Varien_Autoload::register();', Varien/Autoload.php,

4) How and when does Magento load the base configuration, the module
   configuration, and the database configuration?
   [Base]
   app->run()=> baseInit()=> _initBaseConfig()=> config->loadBase() [config.xml,local.xml]
   [Modules]
   app->run()=> initModules()=>_config->loadModules()
   =>$this->loadModulesConfiguration(array('config.xml',$resourceConfig), $this);
   [Database configuration]
   app->run()=> initModules=>_config->loadDb();

5) How and when are the two main types of setup script executed?
    in function initModules we have :
     Mage_Core_Model_Resource_Setup::applyAllUpdates();

6) When does Magento decide which store view to use, and when is the
   current locale set?
   in app->_initCurrentStore($scopeCode, $scopeType)
   [locale]
   in Mage_Core_Model_Translate->setConfig
   app()->getLocale() here we have set locale Mage::getSingleton('core/locale')

7) Which ways exist in Magento to specify the current store view?
   in Mage::run($runCode,$runType)
   $runCode and $runType comes from SERVER variable , we can specify it in server config files

8) When are the request and response objects initialized?
   [request] in app->run()->initRequest() request object represented by Mage_Core_Controller_Request_Http()
   [response] in Varien_Front::dispatch we have $this->getResponse()->sendResponse();

    Front Controller

9)  Describe the role of the front controller
    Front controller used init() function for collecting routes for each router first :
    Init:
     function init(){
        Mage::dispatchEvent('controller_front_init_before')
        $routers = getRouters();
        foreach($routers $router){
           $router->collectRoutes()
           $this->addRouter($router)
        }

        Mage::dispatchEvent('controller_front_init_routers') <<-- Mage_Cms_Controller_Router::initControllerRouters

        // add default router
        $default = new Mage_Core_Controller_Varien_Router_Default();
        $this->addRouter('default', $default);
     }

    After when request is coming
    FC use dispatch() function trying to detect "rewrites" and in loop: to check routers
    as
     while(!){
      $router->match($request)
     }
    if request is dispatched , response sent.
    also events :
            controller_front_send_response_before, (can be applied for cookie send)
            controller_front_send_response_after

10) Identify uses for events fired in the front controller

    Follow the flow of control through front controller initialization until an action
    controller is dispatched.
    event in init() controller_front_init_routers - added Mage_Cms router
                    controller_front_send_response_before - can use for set cookies


11) Which ways exist in Magento to add router classes?

    in xml and with in code Mage_Core_Controller_Varien_Front::addRouter() directly,
    in XML:
    a. <stores>
         <default> <!-- Store code -->
            <web>
              <routers>
                <standard/admin/my_route>

    b. <default>
          <web>
            <routers>
               <standard/admin/....>

12) What are the differences between the various ways to add routers?

    We can add route for specific store or add route as default .

13) Think of possible uses for each of the events fired in the front controller

    controller_front_init_routers         - added Mage_Cms router
    controller_front_send_response_before - can use for set cookies
    controller_front_send_response_after  - used for synchronizePersistentInfo in Mage_Persistent
------------------------------------------------------
    URL rewrites

14) Describe URL structure/processing in Magento

    url : /catalog/product/view - where 'catalog'-frontName, 'product' - controller productController, 'view' - action

15) Describe the URL rewrite process

    In code we have several places where url rewrites works

    1. In Mage_Core_Controller_Varien_Front we can see function dispatch()
       $this->_getRequestRewriteController()->rewrite(); -- before main loop
       _getRequestRewriteController() return/create instance of 'Mage_Core_Model_Url_Rewrite_Request'
       and run rewrite() function.

       Mage_Core_Model_Url_Rewrite_Request {
          protected $_routers;
          /**
           * Instance of url rewrite model
           *
           * @var Mage_Core_Model_Url_Rewrite
           */
          protected $_rewrite;

          public function rewrite()
          {
            if (!$this->_request->isStraight()) {
                $this->_rewriteDb();
            }
            $this->_rewriteConfig();
          }

          protected function _rewriteDb()
          {
              $this->_rewrite->loadByRequestPath($requestCases);
              if (!$this->_rewrite->getId() && $fromStore) {
                  $this->_setStoreCodeCookie($currentStore->getCode());
                  $this->_sendRedirectHeaders($targetUrl, true);
              }
              /* !!!!!!! */
              $this->_request->setAlias(
                 Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                 $this->_rewrite->getRequestPath()
              );

              $this->_processRedirectOptions(); /* be redirected only if options 'R'or'RP' are presented
                to target_path path 301 / 302
              */
          }

          protected function _processRedirectOptions()
          {
             if ('RP' or 'R') {
                $this->_sendRedirectHeaders($targetUrl, $isPermanentRedirectOption);
             }
             $this->_request->setRequestUri($targetUrl);
             $this->_request->setPathInfo($this->_rewrite->getTargetPath());
          }
       }


    2.  Support for controllers rewrites:
        this option is reachable in Mage_Core_Controller_Varien_Action::preDispatch() and allow rewrite controller to new controller


16) What is the purpose of each of the fields in the core_url_rewrite
    table?

     +----------------+----------------------+------+-----+---------+----------------+
     | Field          | Type                 | Null | Key | Default | Extra          |
     +----------------+----------------------+------+-----+---------+----------------+
     | url_rewrite_id | int(10) unsigned     | NO   | PRI | NULL    | auto_increment |
     | store_id       | smallint(5) unsigned | NO   | MUL | 0       |                |
     | id_path        | varchar(255)         | YES  | MUL | NULL    |                |
     | request_path   | varchar(255)         | YES  | MUL | NULL    |                |
     | target_path    | varchar(255)         | YES  | MUL | NULL    |                |
     | is_system      | smallint(5) unsigned | YES  |     | 1       |                |
     | options        | varchar(255)         | YES  |     | NULL    |                |
     | description    | varchar(255)         | YES  |     | NULL    |                |
     | category_id    | int(10) unsigned     | YES  | MUL | NULL    |                |
     | product_id     | int(10) unsigned     | YES  | MUL | NULL    |                |
     +----------------+----------------------+------+-----+---------+----------------+
     10 rows in set (0.01 sec)


17) When does Magento created the rewrite records for categories and
    products?

    when reindex catalog_url is working

18) How and where does Magento find a matching record for the current
    request?

    in database table 'core_url_rewrite' and in config xml : $config = $this->_config->getNode('global/rewrite');

19) Describe request routing/request flow in Magento

    in app::run(), $this->getFrontController()->dispatch();
    $this->getFrontController() does instance of Mage_Core_Controller_Varien_Front
    $this->getFrontController()->init() - collect routes from config Mage::app()->getStore()->getConfig(self::XML_STORE_ROUTERS_PATH);
    and add routers.
    // Add default router at the last
    $default = new Mage_Core_Controller_Varien_Router_Default();
    $this->addRouter('default', $default);

    "Mage_Core_Controller_Varien_Front->dispatch()"

    1) $request = $this->getRequest();
    2) set dispatched false : $request->setPathInfo()->setDispatched(false);
    3) trying to rewrite    : $this->_getRequestRewriteController()->rewrite();
    4) match procedure :
          while(!$request->isDispatched()){
              foreach($routers){
                  $router->match()
              }
          }
    5) $this->getResponse()->sendResponse();


20) Describe how Magento determines which controller to use and how to customize
    route-to-controller resolution.

    In Mage_Core_Controller_Varien_Front->dispatch()
    first router Admin->match(), second Standard->match() trying to detect controller and make instance
    1. $modules = $this->getModuleByFrontName($module);
    2. Going through modules to find appropriate controller
       $found = false;
       foreach ($modules as $realModule) {
           $controllerClassName = $this->_validateControllerClassName($realModule, $controller);
           if (!$controllerClassName) {
                continue;
           }

            // instantiate controller class
            $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $front->getResponse());
       }

       $found = true;

    3. $request->setDispatched(true);
       $controllerInstance->dispatch($action);

21) Which routers exist in a native Magento implementation?

    Admin,Standard,Cms,Install, Default

22) How does the standard router map a request to a controller class?
    $controller = $p[1];
    getControllerFileName($realModule, $controller)

23) How does the standard router build the filesystem path to a file that
    might contain a matching action controller?

    $controllerClassName = $this->_validateControllerClassName($realModule, $controller);
    function  _validateControllerClassName()
    {
       $controllerFileName = $this->getControllerFileName($realModule, $controller);
       $controllerClassName = $this->getControllerClassName($realModule, $controller);
       $this->_includeControllerClass($controllerFileName, $controllerClassName))
    }


24) How does Magento process requests that cannot be mapped?
    if request is not matched in $router->match() magento trying next router

    while(!$request->dispatch()){
       foreach($routers){

         $router->match()
         if(!$found){
           return false;
         }
       }
    }

    if finally in all routers nothing found the last router Mage_Core_Controller_Varien_Router_Default
    will set in request:

    $noRoute  = explode('/', $this->_getNoRouteConfig()); // Mage::app()->getStore()->getConfig('web/default/no_route'); // cms/index/noRoute

    $request->setModuleName($moduleName)          // cms
            ->setControllerName($controllerName)  // index
            ->setActionName($actionName);         // noroute

    after Standard router will processed it .


25) After a matching action controller is found, what steps occur before the
    action method is executed?

    // set values only after all the checks are done
    $request->setModuleName($module);
    $request->setControllerName($controller);
    $request->setActionName($action);
    $request->setControllerModule($realModule);

    // set parameters from pathinfo
    for ($i = 3, $l = sizeof($p); $i < $l; $i += 2) {
       $request->setParam($p[$i], isset($p[$i+1]) ? urldecode($p[$i+1]) : '');
    }

     // dispatch action
     $request->setDispatched(true);
     $controllerInstance->dispatch($action); executing

----------------------------------------------------------------------------------------------
Module initialization

1 Describe the steps needed to create and register a new module
   1. in code/local we have to create folder Namespace_MyModule
   2. in Namespace_MyModule we have to create etc/config.xml file
   3. in app/etc/modules we have to create Namespace_MyModule.xml file

2 Describe the effect of module dependencies

  in a file app/etc/modules/Namespace_MyModule.xml we can use tag
  <depends>
     <Namespace_AnotherModule/>
  </depends>
  this means that current module will be loaded after "Namespace_AnotherModule"

3 Describe different types of configuration files and the priorities of their loading

   1. Base config files are located in "app/etc" local.xml and config.xml (will be loaded first)
      This is base settings of Magento. these files will be loaded in
      function app::run(){
        $this->baseInit($options);
      }
   2. In app/etc/modules/*.xml - this is modules information. (declared modules)
      this files are loading in : _initModules()

      class Mage_Core_Model_App {
         /* Mage_Core_Model_Config */
         protected $_config;
         function app::run()
         {
             $this->_initModules();
         }

         function _initModules()
         {
             $this->_config->loadModules();
             if ($this->_config->isLocalConfigLoaded() && !$this->_shouldSkipProcessModulesUpdates()) {
                 Mage_Core_Model_Resource_Setup::applyAllUpdates();
             }
         }
      }

      class Mage_Core_Model_Config {
        function loadModules(){
            $this->_loadDeclaredModules();
            $resourceConfig = sprintf('config.%s.xml', $this->_getResourceConnectionModel('core'));
            $this->loadModulesConfiguration(array('config.xml',$resourceConfig), $this);
        }

        function _loadDeclaredModules() {
           /* loading from app/etc/modules/*.xml */
           Mage_All, Mage_ , Custom
        }

        function loadModulesConfiguration(){
              loading config files from mymodule/etc/*.xml
        }
      }

   3. In app/code/codePool/Namespace_MyModule/etc/*.xml
      There could be [config.xml,config.mysql4.xml,jstranslator.xml]

4. What does "Magento loads modules" mean?

   Magento loads config files and merge their instructions

5. In which order are Magento modules loaded?

   From Mage_all, Mage_, Custom

6. Which core class loads modules?

   Mage_Core_Model_Config

7. What are the consequences of one module depending on another
   module?

   if module has definitions like "<depends/>" magento will try load module in "<depends/>" first

8. During the initialization of Magento, when are modules loaded in?

   when base config are loaded.

9. Why is the load order important?

   Because some modules can depends from other modules

10.What is the difference regarding module loading between
   Mage::run() and Mage::app() ?

   couldn't find the difference between











