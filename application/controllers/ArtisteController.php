<?php

class ArtisteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function listeAction()
    {
		$modelArtiste = new ArtArtiste();
		$listeArtiste = $modelArtiste->getListeArtiste();
		//Zend_Debug::dump($listeArtiste);die;
		$this->view->listeArtiste = $listeArtiste;
		
		
    }

    public function lyricsAction()
    {
    	$request = $this->getRequest();
    	$idArtiste = $request->getParam('id');
    	
    	$modelLyrics = new LyrLyrics();
    	$listeLyrics = $modelLyrics->getLyricsByArtiste($idArtiste);
    	
    	$this->view->listeLyrics = $listeLyrics;
    }
}

