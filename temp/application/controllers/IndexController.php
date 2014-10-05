<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$modelLyrics = new LyrLyrics();
		
		// last lyrics
		$listeLyrics = $modelLyrics->getLastLyrics();
		$this->view->listeLyrics = $listeLyrics;
		
		// popular lyrics
		$listePopularLyrics = $modelLyrics->getPopularLyrics();
		$this->view->listePopularLyrics = $listePopularLyrics;
		
		// in progress lyrics
		$listeRequestLyrics = $modelLyrics->getLastRequestLyrics();
		$this->view->listeRequestLyrics = $listeRequestLyrics;
		
		$request = $this->getRequest();
		$uok = $request->getParam('uok');
		$this->view->uok = $uok;
		
    }
	
    public function sitemapAction()
    {
    	
    }

}

