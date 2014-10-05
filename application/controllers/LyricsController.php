<?php

class LyricsController extends Zend_Controller_Action
{

	public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
    	//config
    	$this->config = Zend_Registry::get('config');
    	$imgPath = $this->config->image->path;
    	
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		
    		try{
    			// get request params
    			$artiste = $request->getPost('artiste');
    			$title = $request->getPost('title');
    			$genre = $request->getPost('genre');
    			$lyrics = $request->getPost('lyrics');
    			$youtube = $request->getPost('youtube');
    			$feat = $request->getPost('feat');
    			$year = $request->getPost('year');
    			$producer = $request->getPost('producer');
    			$album = $request->getPost('album');
    			$toTranslate = $request->getPost('radioHaveToTranslate');
    			$image = $request->getPost('image');
    			
    			/*$params = array(
    				'artiste' => $request->getPost('artiste'),
    				'title' => $request->getPost('title'),
    				'genre' => $request->getPost('genre'),
    				'lyrics' => $request->getPost('lyrics'),
    				'youtube' => $request->getPost('youtube'),
    				'feat' => $request->getPost('feat'),
    				'year' => $request->getPost('year'),
    				'producer' => $request->getPost('producer'),
    				'album' => $request->getPost('album'),
    				'radioHaveToTranslate' => $request->getPost('radioHaveToTranslate'),
    			);
    			Zend_Debug::dump($params);*/
    			
    			// On met en place les filtres et les validateurs
    			//$filterList = array('StringTrim','StripTags');
    			//$validatorList = array();
    			// On vérifie qu'au moins une donnée est valide
    			///$filter = new Zend_Filter_Input($filterList, $validatorList);
    			//$filter->setData($params);
    			// on recupere les infos valides et filtrées
    			//$valuesFilter = $filter->getEscaped();
    			
    			//Zend_Debug::dump($valuesFilter);die('EXCEPTION ERROR');
    			
    			/*$validatorAlnum = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));
    			$validatorInt = new Zend_Validate_Int();
    			if(
    					$validatorAlnum->isValid($artiste) &&
    					$validatorAlnum->isValid($title) &&
    					//$validatorAlnum->isValid($lyrics) &&
    					//$validatorAlnum->isValid($youtube) &&
    					$validatorAlnum->isValid($feat) &&
    					$validatorAlnum->isValid($producer) &&
    					$validatorAlnum->isValid($album) &&
    					$validatorInt->isValid($year) &&
    					$validatorInt->isValid($genre) &&
    					$validatorInt->isValid($toTranslate)
    					){*/
    				
	    			// get youtube code
	    			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube, $matches);
	    			$youtubeCode = $matches[0];
	    			
	    			$filter = new Zend_Filter_StringTrim();
	    			// create object LYRICS
	    			$dataLyrics = new stdClass();
	    			$dataLyrics->title = $title;
	    			$dataLyrics->feat = $feat;
	    			$dataLyrics->youtube = $youtubeCode;
	    			$dataLyrics->genre = $genre;
	    			$dataLyrics->year = $year;
	    			$dataLyrics->producer = $producer;
	    			$dataLyrics->album = $album;
	    			$dataLyrics->image = null;
	    			
	    			// gestion upload file
	    			$isUpload = false;
	    			$upload = new Zend_File_Transfer_Adapter_Http();
	    			$files = $upload->getFileInfo();
	    			
	    			$upload->setDestination($imgPath);
	    			$upload->setOptions(array('ignoreNoFile' => true));
	    			$upload->addValidator('Extension', false, 'jpg,png,gif,jpeg');
	    			$upload->addValidator('Size', false, '5MB');
	    			
	    			foreach($files as $file => $info){
	    				if(!$upload->isUploaded($file)){
	    					continue;
	    				}
	    			
	    				if($upload->isValid($file)){
	    					$nameFile = $upload->getFileName($file,false);
	    					$upload->addFilter('Rename', array('target'=>$imgPath.$nameFile,'overwrite'=>true));
	    					$isUpload = $upload->receive($file);
	    				}
	    			}
	    			
	    			if($isUpload){
	    				$dataLyrics->image = $nameFile;
	    			}
	    			
	    			// GET current USER
	    			$auth = Zend_Auth::getInstance();
	    			$identity = $auth->getIdentity();
	    			$dataLyrics->idUser = $identity->usr_id;
	    			
	    			// SAVE ARTISTE
	    			$modelArtiste = new ArtArtiste();
	    			$artisteDB = $modelArtiste->getArtisteByName($artiste);
	    			
	    			if(!$artisteDB){
	    				$dataUser = new stdClass();
	    				$dataUser->name = $artiste;
	    				$dataUser->url = $this->_seoUrl($artiste);
	    				$idArtiste = $modelArtiste->addArtiste($dataUser);
	    			}
	    			else{
	    				$idArtiste = $artisteDB->art_id;
	    			}
	    			
	    			// SAVE LYRICS
	    			$modelLyrics = new LyrLyrics();
	    			$dataLyrics->idArtiste = $idArtiste;
	    			$dataLyrics->url = $this->_seoUrl($artiste.'-'.$title);
	    			
	    			//Zend_Debug::dump($dataLyrics);die('EXCEPTION ERROR');
	    			$idLyrics = $modelLyrics->addLyrics($dataLyrics);
	    			
	    			// SAVE TRANSLATE
	    			$expLyrics = explode(PHP_EOL, $lyrics);
	    			$expLyrics = array_map('trim', $expLyrics);
	    			$modelTranslate = new TraTranslate();
	    			foreach ($expLyrics as $line){
	    				if(!empty($line)){
	    					$dataTranslate = new stdClass();
	    					$dataTranslate->line = $line;
	    					$dataTranslate->idLyrics = $idLyrics;
	    					$modelTranslate->addLine($dataTranslate);
	    				}
	    			}
	    			
	    			// Redirection home ou translate
	    			if($toTranslate){
	    				$this->_redirector->gotoSimple('index',
	    						'index',
	    						null
	    				);
	    			}
	    			else{
	    				$this->_redirector->gotoSimple('translate',
	    						'lyrics',
	    						null,
	    						array('id' => $idLyrics)
	    				);
	    			}
    			
    			
    		}
    		catch (Exception $e){
    			Zend_Debug::dump($e);die('EXCEPTION ERROR');
    		}
    		
    	}
    	
    	// GET liste genre
    	$modelGenre = new GenGenre();
    	$listeGenre = $modelGenre->getListeGenre();
    	$this->view->listeGenre = $listeGenre;
    	
    }
    
    
    public function translateAction()
    {
    	$request = $this->getRequest();
    	$idLyrics = $request->getParam('id');
    	
    	// GET lyrics
    	$modelLyrics = new LyrLyrics();
    	$lyrics = $modelLyrics->getLyricsTranslateById($idLyrics);
    	$this->view->lyrics = $lyrics;
    	
    	// GET lyrics artiste
    	$listeLyricsArtiste = $modelLyrics->getLyricsByArtiste($lyrics[0]['art_id']);
    	$this->view->listeLyricsArtiste = $listeLyricsArtiste;
    	
    	if($request->isPost()){
    		
    		$arrTranslate = $request->getPost('translate');
    		
    		$modelTranslate = new TraTranslate();
    		$nbLigne = 0;
    		$nbLigneTranslate = 0;
    		foreach($arrTranslate as $idTranslate => $translate){
    			$nbLigne++;
    			if(!empty($translate)){
    				$nbLigneTranslate++;
    				$data = new stdClass();
    				$data->translate = trim($translate);
    				$modelTranslate->updateTranslate($data, $idTranslate);
    			}
    		}
    		
    		$taux = ($nbLigneTranslate / $nbLigne) * 100;
    		$modelLyrics->setLyricsTaux($taux, $idLyrics);
    		$modelLyrics->unblockLyrics($idLyrics);
    		
    		// redirect detail action
    		$urlOptions = array('controller'=>'lyrics', 'action'=>'detail', 'id'=>$idLyrics, 'url'=>$lyrics[0]['lyr_url']);
    		$this->_helper->redirector->gotoRoute($urlOptions, 'detail-lyrics');
    		
    	}
    	else {
    		if($idLyrics){
    			// set lyrics en cours
    			$modelLyrics->blockLyrics($idLyrics);
    		}
    	}
    	
    }
    
    
    public function detailAction()
    {
    	$request = $this->getRequest();
    	$idLyrics = $request->getParam('id');
    	if($idLyrics){
    		$modelLyrics = new LyrLyrics();

    		// GET lyrics 
    		$lyrics = $modelLyrics->getLyricsTranslateById($idLyrics);
    		$this->view->lyrics = $lyrics;
    		
    		// GET lyrics artiste
    		$listeLyricsArtiste = $modelLyrics->getLyricsByArtiste($lyrics[0]['art_id']);
    		$this->view->listeLyricsArtiste = $listeLyricsArtiste;
    	}
    }
    
    
    public function getArtisteAutocompleteAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$request = $this->getRequest();
    	if($request->isPost()){
    		$query = $request->getPost('query');
    		$modelArtiste = new ArtArtiste();
    		$listeArtiste = $modelArtiste->getArtisteAutocomplete($query);
    		
    		echo Zend_Json::encode($listeArtiste);die;
    		
    	}
    	
    }
    
    
    public function getTitleAutocompleteAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$query = $request->getPost('query');
    		$artiste = $request->getPost('artiste');
    		$modelLyrics = new LyrLyrics();
    		$listeTitle = $modelLyrics->getTitleAutocomplete($query, $artiste);
    
    		echo Zend_Json::encode($listeTitle);die;
    
    	}
    	 
    }
    
    
    public function addCommentAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	 
    	$request = $this->getRequest();
    	 
    	if($request->isPost()){
    		$idLyrics = $request->getPost('idLyrics');
    		$modelLyrics = new LyrLyrics();
    		$modelLyrics->addComment($idLyrics);
    		
    		die;
    	
    	}
    }
    
    
    public function rateAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    
    	$request = $this->getRequest();
    
    	if($request->isPost()){
    		$idLyrics = $request->getPost('idLyrics');
    		$rate = $request->getPost('rate');
    		$mark = $request->getPost('mark', 0);
    		$mark++;
    		
    		$modelLyrics = new LyrLyrics();
    		$modelLyrics->rateLyrics($idLyrics, $rate, $mark);
    
    		echo $mark;die;
    		 
    	}
    }
    
    
    protected function _seoUrl($string) {
    	//Lower case everything
    	$string = strtolower($string);
    	//Make alphanumeric (removes all other characters)
    	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    	//Clean up multiple dashes or whitespaces
    	$string = preg_replace("/[\s-]+/", " ", $string);
    	//Convert whitespaces and underscore to dash
    	$string = preg_replace("/[\s_]/", "-", $string);
    	return $string;
    }
    
}

