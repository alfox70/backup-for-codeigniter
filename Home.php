<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	 function __construct()
    {
        parent::__construct();
        #... other
        $this->load->library('Alphox_bkp');
        
    }

	
	public function index()
	{
		  
        $this->Alphox_bkp->backup();
        #... other code
	}

}
