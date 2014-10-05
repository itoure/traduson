<?php

class TraTranslate extends Zend_Db_Table_Abstract
{
    protected $_name = 'TRA_TRANSLATE';
    protected $_primary = 'tra_id'; 
    
    
    public function getLastBeem(){
    	$select = $this->select();
    	$select->from(array('bem' => $this->_name), array('*'))
    	->join(array('cat' => 'CAT_CATEGORY'), 'bem.cat_id = cat.cat_id', array('cat_label'));
    	$select->order(array('bem_date_create DESC'));
    	$select->group('bem.bem_id');
    	$select->limit(3);
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    public function getListeBeem(){
    	$select = $this->select();
    	$select->from(array('bem' => $this->_name), array('*'))
    	->join(array('cat' => 'CAT_CATEGORY'), 'bem.cat_id = cat.cat_id', array('cat_label'))
    	->joinLeft(array('bc' => 'REL_BEM_COM'), 'bem.bem_id = bc.bem_id', array('count(com_id) as nb_comment'));
    	$select->order(array('bem_date_create DESC'));
    	$select->group('bem.bem_id');
    	$select->setIntegrityCheck(false);
    	//echo $select;die;
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    public function getBeemById($idBeem){
    	$select = $this->select();
    	$select->from(array('bem' => $this->_name), array('*'))
    	->where('bem.bem_id = ?', $idBeem)
    	->join(array('cat' => 'CAT_CATEGORY'), 'bem.cat_id = cat.cat_id', array('cat_label'));
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    public function getBeemByTypeExceptCurrent($idCat,$idBeem){
    	$select = $this->select();
    	$select->from(array('bem' => $this->_name), array('*'))
    	->join(array('cat' => 'CAT_CATEGORY'), 'bem.cat_id = cat.cat_id', array('cat_label'))
    	->where('bem.cat_id = ?', $idCat)
    	->where('bem.bem_id <> ?', $idBeem)
    	->joinLeft(array('bc' => 'REL_BEM_COM'), 'bem.bem_id = bc.bem_id', array('count(com_id) as nb_comment'));
    	$select->order(array('bem_date_create DESC'));
    	$select->group('bem.bem_id');
    	$select->setIntegrityCheck(false);
    	//echo $select;die;
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    /**
     * 
     * @param unknown_type $data
     */
    public function addLine($data){
    	
    	$data = array(
    			'tra_line'      	=> $data->line,
    			'lyr_id'			=> $data->idLyrics,
    	);
    	 
    	$this->insert($data);
    	
    }
    
    public function updateTranslate($data, $idTranslate){
    	 
    	$data = array(
    		'tra_translate' => $data->translate,
    	);
    
    	$where = $this->getAdapter()->quoteInto('tra_id = ?', $idTranslate);
 		
		return $this->update($data, $where);	
    	 
    }
    
}