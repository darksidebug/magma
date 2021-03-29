<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('Login_Model');
    }

    public function index(){
        redirect('/login/security_credintials/authenticate/');
    }
    
    public function security_credintials($page)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page.'.php')) {
			show_404();
		}

		$this->load->view('template/header');
        $this->load->view('pages/'.$page);

	}
}