<?php
const MSF_DEBUG = true;
require_once '../Core/Bootstrap.php';



//echo file_get_contents( MSF_CORE . '/Controller/FrontController.php' );
//exit;
Core\Controller\FrontController::run();