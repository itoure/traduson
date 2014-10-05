<?php

class AuthPlugin extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$view->identity = $identity;
	}
	
}