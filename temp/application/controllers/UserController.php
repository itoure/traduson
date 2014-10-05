<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }
	
    public function signInAction()
    {
    	$request = $this->getRequest();
    	 
    	if($request->isPost()){
    
    		try{
    			// request params
    			$email = $request->getPost('email');
    			$password = $request->getPost('password');
    			
    			$validatorAlnum = new Zend_Validate_Alnum();
    			$validatorEmail = new Zend_Validate_EmailAddress();
    			if ($validatorAlnum->isValid($password) 
    					&& $validatorEmail->isValid($email)) {
    				$modelUser = new UsrUser();
    				$authUser = $modelUser->processAuth($email, $password);
    				 
    				if($authUser){
    					// redirect detail action
    					$urlOptions = array('controller'=>'index', 'action'=>'index', 'uok'=>1);
    					$this->_helper->redirector->gotoRoute($urlOptions, 'connexion-ok');
    				}
    				else{
    					$this->view->unok = 1;
    				}
    			}
    				 
    		}
    		catch (Exception $e){
    			Zend_Debug::dump($e);die('EXCEPTION ERROR');
    		}
    	}
    
    }
    
    public function signUpAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		
    		try{
    			// request params
    			$login = $request->getPost('login');
    			$email = $request->getPost('email');
    			$password = $request->getPost('password');
    			
    			$validatorAlnum = new Zend_Validate_Alnum();
				$validatorEmail = new Zend_Validate_EmailAddress();
    			if ($validatorAlnum->isValid($login) && 
    					$validatorEmail->isValid($email) && 
    					$validatorAlnum->isValid(password)) {
    				
    				$dataUser = new stdClass();
    				$dataUser->login = $login;
    				$dataUser->email = $email;
    				$dataUser->password = $password;
    				 
    				// ajout user en bdd
    				$modelUser = new UsrUser();
    				$return = $modelUser->addUser($dataUser);
    				
    				if($return){
    					$authUser = $modelUser->processAuth($email, $password);
    						
    					// redirect detail action
    					$urlOptions = array('controller'=>'index', 'action'=>'index', 'uok'=>1);
    					$this->_helper->redirector->gotoRoute($urlOptions, 'connexion-ok');
    				}
    			}
    			else{
    				$this->view->unok = 1;
    			}
    			
    		}
    		catch (Exception $e){
    			Zend_Debug::dump($e);die('EXCEPTION ERROR');
    		}
    	}
    	
    }
    
    public function logoutAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	
    	// redirect home
    	$this->_redirector->gotoSimple('index',
    			'index',
    			null
    	);
    }
    
    public function checkIfExistAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$request = $this->getRequest();
    	 
    	if($request->isPost()){
    
    		try{
    			$email = $request->getPost('email');
    			
    			// check if exist en bdd
    			$modelUser = new UsrUser();
    			$user = $modelUser->getUserByEmail($email);
    			$countUser = count($user);
    			
    			echo $countUser;die;
    		}
    		catch (Exception $e){
    			Zend_Debug::dump($e);die('EXCEPTION ERROR');
    		}
    		
    	}
    }
    
    public function profileAction()
    {
    	
    }
    
}

