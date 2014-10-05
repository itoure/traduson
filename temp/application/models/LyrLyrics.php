<?php

class LyrLyrics extends Zend_Db_Table_Abstract
{
    protected $_name = 'LYR_LYRICS';
    protected $_primary = 'lyr_id'; 
    
    
    public function getLastLyrics(){
    	$select = $this->select();
    	$select->from(array('lyr' => $this->_name), array('*'))
    	->join(array('gen' => 'GEN_GENRE'), 'lyr.gen_id = gen.gen_id', array('gen_label'))
    	->join(array('art' => 'ART_ARTISTE'), 'lyr.art_id = art.art_id', array('*'))
    	->join(array('usr' => 'USR_USER'), 'lyr.usr_id = usr.usr_id', array('usr_login'))
    	->where('lyr.lyr_taux > ?', 65);
    	$select->order(array('lyr_create_date DESC'));
    	$select->limit(10);
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    public function getPopularLyrics(){
    	$select = $this->select();
    	$select->from(array('lyr' => $this->_name), array('*'))
    	->join(array('gen' => 'GEN_GENRE'), 'lyr.gen_id = gen.gen_id', array('gen_label'))
    	->join(array('art' => 'ART_ARTISTE'), 'lyr.art_id = art.art_id', array('*'))
    	->join(array('usr' => 'USR_USER'), 'lyr.usr_id = usr.usr_id', array('usr_login'))
    	->where('lyr.lyr_taux > ?', 65);
    	$select->order(array('lyr_up DESC'));
    	$select->limit(10);
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    public function getLastRequestLyrics(){
    	$select = $this->select();
    	$select->from(array('lyr' => $this->_name), array('*'))
    	->join(array('gen' => 'GEN_GENRE'), 'lyr.gen_id = gen.gen_id', array('gen_label'))
    	->join(array('art' => 'ART_ARTISTE'), 'lyr.art_id = art.art_id', array('*'))
    	->join(array('usr' => 'USR_USER'), 'lyr.usr_id = usr.usr_id', array('usr_login'))
    	->where('lyr.lyr_taux <= ?', 65);
    	$select->order(array('lyr_create_date DESC'));
    	$select->limit(10);
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    public function getLyricsTranslateById($idLyrics){
    	$select = $this->select();
    	$select->from(array('lyr' => $this->_name), array('*'))
    	->join(array('tra' => 'TRA_TRANSLATE'), 'lyr.lyr_id = tra.lyr_id', array('*'))
    	->join(array('gen' => 'GEN_GENRE'), 'lyr.gen_id = gen.gen_id', array('gen_label'))
    	->join(array('art' => 'ART_ARTISTE'), 'lyr.art_id = art.art_id', array('art_name'))
    	->join(array('usr' => 'USR_USER'), 'lyr.usr_id = usr.usr_id', array('usr_login'))
    	->where('lyr.lyr_id = ?', $idLyrics);
    	$select->order(array('tra_id'));
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    public function addLyrics($data){
    	
    	$data = array(
    			'lyr_title'      	=> ucwords($data->title),
    			'lyr_feat'    		=> $data->feat,
    			'lyr_youtube'      	=> $data->youtube,
    			'lyr_image'      	=> $data->image,
    			'lyr_create_date' 	=> date('Y-m-d H:i:s'),
    			'gen_id'			=> $data->genre,
    			'usr_id'			=> $data->idUser,
    			'art_id'			=> $data->idArtiste,
    			'lyr_url'			=> $data->url,
    			'lyr_taux'			=> '0',
    			'lyr_album'			=> $data->album,
    			'lyr_year'			=> $data->year,
    			'lyr_producer'		=> $data->producer,
    	);
    	 
    	return $this->insert($data);
    	
    }
    
    
    public function getTitleAutocomplete($title, $artiste = null){
    	$select = $this->select();
    	$select->from(array('lyr' => $this->_name), array('*'))
    	->join(array('art' => 'ART_ARTISTE'), 'lyr.art_id = art.art_id', array('art_name'))
    	->where('lyr.lyr_title like ?', '%'.$title.'%')
    	->where('art.art_name like ?', '%'.$artiste.'%');
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	 
    	$listeTitle = array();
    	foreach($rows as $item){
    		$listeTitle[] = $item['lyr_title'];
    	}
    	 
    	return $listeTitle;
    	 
    }
    
    
    public function getLyricsByArtiste($idArtiste){
    	$select = $this->select();
    	$select->from(array('lyr' => $this->_name), array('*'))
    	->join(array('gen' => 'GEN_GENRE'), 'lyr.gen_id = gen.gen_id', array('gen_label'))
    	->join(array('art' => 'ART_ARTISTE'), 'lyr.art_id = art.art_id', array('art_name'))
    	->where('lyr.art_id = ?', $idArtiste);
    	$select->order(array('lyr_title'));
    	$select->setIntegrityCheck(false);
    	$rows = $this->fetchAll($select);
    	return $rows;
    }
    
    
    public function blockLyrics($idLyrics){
    	
    	$data = array(
    			'lyr_block' => '1'
    	);
    	
    	$where = $this->getAdapter()->quoteInto('lyr_id = ?', $idLyrics);
    	
    	$this->update($data, $where);
    	
    }
    
    public function unblockLyrics($idLyrics){
    	 
    	$data = array(
    			'lyr_block' => '0'
    	);
    	 
    	$where = $this->getAdapter()->quoteInto('lyr_id = ?', $idLyrics);
    	 
    	$this->update($data, $where);
    	 
    }
    
    public function setLyricsTaux($taux, $idLyrics){
    
    	$data = array(
    			'lyr_taux' => $taux
    	);
    
    	$where = $this->getAdapter()->quoteInto('lyr_id = ?', $idLyrics);
    
    	$this->update($data, $where);
    
    }
    
    public function rateLyrics($idLyrics, $rate, $mark){
    	 
    	$data = array(
    			'lyr_'.$rate => $mark
    	);
    	 
    	$where = $this->getAdapter()->quoteInto('lyr_id = ?', $idLyrics);
    	 
    	$this->update($data, $where);
    	 
    }
    
    
}