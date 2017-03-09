<?php
chdir( '../' );

/**
 * dflfsdkjfsdkjfsdkjfsdhkj
 */
define( 'MSF_ROOT', getcwd(  ) );
define( 'MSF_HEADTIME', microtime( true ) );
define( 'MSF_HEAD_MEMORY_USG', memory_get_usage(  ) );

require_once MSF_ROOT . "/Core/Config.php";
require_once MSF_ROOT . "/Core/Debug.php";
require_once MSF_ROOT . "/Core/Autoload.php";

Autoload::setFileExts( ['php'] );
Autoload::setNamespaces( [
  'App'        => MSF_APP,
  'Core'       => MSF_CORE,
  'Components' => MSF_CORE . "/Components",
  'Exceptions' => MSF_CORE . "/Exceptions",
] );

spl_autoload_register( ['\Autoload', 'loadClass' ] );

require_once MSF_APP . "/Config/Base.php";
//Components\Session::run();