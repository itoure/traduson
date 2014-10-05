<?php

class UsrUser extends Zend_Db_Table_Abstract
{
    protected $_name = 'USR_USER';
    protected $_primary = 'usr_id'; 
    
    
    public function getUserByEmail($email){
    	$select = $this->select();
    	$select->from(array('usr' => $this->_name), array('*'))
    	->where('usr.usr_email = ?', $email);
    	$rows = $this->fetchRow($select);
    	return $rows;
    }
    
    
    /**
     * 
     * @param unknown_type $data
     */
    public function addUser($data){
    	
    	$data = array(
    			'usr_login'      	=> $data->login,
    			'usr_email'      	=> $data->email,
    			'usr_password'      => $data->password,
    	);
    	 
    	return $this->insert($data);
    	
    }
    
    protected function _getAuthAdapter()
    {
    	$dbAdapter = Zend_Db_Table::getDefaultAdapter();
    	$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
    
    	$authAdapter->setTableName($this->_name)
    	->setIdentityColumn('usr_email')
    	->setCredentialColumn('usr_password');
    
    	return $authAdapter;
    }
    
    public function processAuth($username, $password)
    {
    	// Get our authentication adapter and check credentials
    	$adapter = $this->_getAuthAdapter();
    	$adapter->setIdentity($username);
    	$adapter->setCredential($password);
    
    	$auth = Zend_Auth::getInstance();
    	$result = $auth->authenticate($adapter);
    	if ($result->isValid()) {
    		$user = $adapter->getResultRowObject();
    		$auth->getStorage()->write($user);
    		return true;
    	}
    	return false;
    }
    
}