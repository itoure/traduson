<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

    switch(APPLICATION_ENV){
    	case 'development':
    	case 'staging':
    	case 'testing':
    		$pathZendLib = realpath(APPLICATION_PATH . '/../library');
    		break;
    	case 'production':
    		$pathZendLib = '/var/lib/zend/library';
    		break;
    }
    
// Ensure library/ is on include_path
//var_dump(realpath(APPLICATION_PATH . '/models/'));die;
set_include_path(implode(PATH_SEPARATOR, array(
    $pathZendLib,
    realpath(APPLICATION_PATH . '/models/'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini',APPLICATION_ENV);
Zend_Registry::set('config', $config);

//Routage
$route = new Zend_Config_Ini(APPLICATION_PATH . '/configs/route.ini',APPLICATION_ENV);
$frontController = Zend_Controller_Front::getInstance();
$router = $frontController->getRouter();
$router->addConfig($route, 'routes');

//Autoload
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

// Helpers
Zend_Controller_Action_HelperBroker::addPrefix('Helpers');

try {
	// Connexion database
	$db = Zend_Db::factory($config->database);
	$db->getConnection();
	Zend_Db_Table_Abstract::setDefaultAdapter($db);
	Zend_Registry::set('dbAdapter', $db);
} catch (Zend_Db_Adapter_Exception $e) {
	// probablement mauvais identifiants,
	// ou alors le SGBD n'est pas joignable
	echo 'CONNECTION DATABASE ERROR';die;
} catch (Zend_Exception $e) {
// probablement que factory() n'a pas réussi à charger
		// la classe de l'adaptateur demandé
		echo 'ERROR UNKNOW';die;
}

$application->bootstrap()
            ->run();