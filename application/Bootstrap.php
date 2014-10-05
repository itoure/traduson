<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initPlaceholders()
	{
		$this->bootstrap('View');
		$view = $this->getResource('View');
	
		// Précise le titre initial et le séparateur:
		$view->headTitle('TraduSon | Paroles & Traduction')->setSeparator(' | ');
		$view->headMeta()->appendName('keywords', 'traduction, paroles, lyrics, musique, chanson');
		$view->headMeta()->appendName('description', 'traduction de musique, chansons, paroles, lyrics');
	}
	
	protected function _initPlugins()
	{
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new AuthPlugin());
	}
	
	
	
}

