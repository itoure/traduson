<?php

class ArtArtiste extends Zend_Db_Table_Abstract
{
    protected $_name = 'ART_ARTISTE';
    protected $_primary = 'art_id'; 
    
    
	public function addArtiste($data){
    	
    	$data = array(
    			'art_name' => ucwords($data->name),
    			'art_url' => $data->url,
    	);
    	 
    	return $this->insert($data);
    	
    }
    
    public function getArtisteByName($name){
    	$select = $this->select();
    	$select->from(array('art' => $this->_name), array('*'))
    	->where('art.art_name like ?', '%'.$name.'%');
    	$rows = $this->fetchRow($select);
    	return $rows;
    }
    
    public function getArtisteAutocomplete($name){
    	$select = $this->select();
    	$select->from(array('art' => $this->_name), array('*'))
    	->where('art.art_name like ?', $name.'%');
    	$rows = $this->fetchAll($select);
    	
    	$listeArtiste = array();
    	foreach($rows as $item){
    		$listeArtiste[] = $item['art_name'];
    	}
    	
    	return $listeArtiste;
    }
    
    public function getListeArtiste(){
    	$select = $this->select();
    	$select->from(array('art' => $this->_name), array('*'));
    	$select->order(array('art_name ASC'));
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    
}