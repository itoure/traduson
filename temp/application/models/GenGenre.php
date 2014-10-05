<?php

class GenGenre extends Zend_Db_Table_Abstract
{
    protected $_name = 'GEN_GENRE';
    protected $_primary = 'gen_id'; 
    
    
    public function getListeGenre(){
    	$select = $this->select();
    	$select->from(array('gen' => $this->_name), array('*'));
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    
}