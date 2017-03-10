<?php
Core\Controller\Router::setRule( '/contact', ['Index', 'Contact'] );

define( 'DB_HOST', 'localhost' );
define( 'DB_BASE', 'test1' );
define( 'DB_USER', 'test1' );
define( 'DB_PASS', '12345q' );

define( 'DEVEL_UA', 'jfvdmncvmv bgvhnjmjgbffgv' );

date_default_timezone_set( 'Etc/GMT+0' );