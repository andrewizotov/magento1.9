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

5) Identify and explain the main Magento design areas (adminhtml and frontend)
   Adminhtml - for admin
   frontend -  for frontand (I don't know what there I have to do)

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





