<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('User_Model', 'user_model');
	}

	public function index()
	{
		redirect('page/security/sign-in.html');
	}

	public function view($page)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page)) {
			show_404();
		}

		if($this->session->userdata('logged_in') == FALSE)
		{
			redirect('page/security/sign-in.html');
		}

		$pages = array('members-list.html', 'members-bill.html', 'users-list.html', 'announcement.html', 'my-bills.html');
		if(!in_array($page, $pages)){
			show_404();
		}

		$this->load->view('templates/head.html');
		$this->load->view('templates/navigation.html');
		if($page == 'members-list.html'){
			$data['list'] = $this->user_model->get('personnal_informations');
			$this->load->view('pages/'.$page, $data);
		}
		if($page == 'members-bill.html'){
			$data['list'] = $this->user_model->get_bills('personnal_informations', 'billings');
			$this->load->view('pages/'.$page, $data);
		}
		if($page == 'my-bills.html'){
			$data['list'] = $this->user_model->get_my_bills('billings');
			$this->load->view('pages/'.$page, $data);
		}
		if($page == 'users-list.html'){
			$data['list'] = $this->user_model->get('user_account');
			$this->load->view('pages/'.$page, $data);
		}
		if($page == 'announcement.html'){
			$this->load->view('pages/'.$page);
		}

		$this->load->view('templates/footer.html');
	}

	public function form($page)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page)) {
			show_404();
		}

		if($this->session->userdata('logged_in') == FALSE)
		{
			redirect('page/security/sign-in.html');
		}

		$this->load->view('templates/head.html');
		// $this->load->view('templates/navbar.html');
		$this->load->view('pages/'.$page);
		
		$this->load->view('templates/footer.html');
	}

	public function payments($page, $id)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page)) {
			show_404();
		}

		if($this->session->userdata('logged_in') == FALSE)
		{
			redirect('page/security/sign-in.html');
		}

		$this->load->view('templates/head.html');
		$this->load->view('templates/navigation.html');
		$data['result'] = $this->user_model->get_payments('payments', $id);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer.html');
	}

	public function update($page, $id, $call_sign)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page)) {
			show_404();
		}

		if($this->session->userdata('logged_in') == FALSE)
		{
			redirect('page/security/sign-in.html');
		}

		$pages = array('information.html', 'update-announcement.html');
		if(!in_array($page, $pages)){
			show_404();
		}

		$this->load->view('templates/head.html');
		$this->load->view('templates/navigation.html');
		if($page == 'information.html'){
			$data['result'] = $this->user_model->get_info('personnal_informations', $id, $call_sign);
			$this->load->view('pages/'.$page, $data);
		}
		if($page == 'update-announcement.html'){
			$data['result'] = $this->user_model->get_where('announcement', $id, $call_sign);
			$this->load->view('pages/'.$page, $data);
		}
		// else{
		// 	$this->load->view('pages/'.$page);
		// }
		
		$this->load->view('templates/footer.html');
	}

	public function security($page)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page)) {
			show_404();
		}

		$this->load->view('templates/head.html');
		$this->load->view('pages/'.$page);
		$this->load->view('templates/footer.html');
	}

	public function user($page, $id = NULL)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page)) {
			show_404();
		}

		if($this->session->userdata('logged_in') == FALSE)
		{
			redirect('page/security/sign-in.html');
		}

		$pages = array('update-user.html', 'add-system-user.html', 'profile.html', 'change-password.html');
		if(!in_array($page, $pages)){
			show_404();
		}

		$this->load->view('templates/head.html');
		$this->load->view('templates/navigation.html');
		if($page == 'update-user.html'){
			$data['result'] = $this->user_model->get_where('user_account', $id);
			$this->load->view('pages/'.$page, $data);
		}
		if($page == 'add-system-user.html'){
			$this->load->view('pages/'.$page);
		}
		if($page == 'profile.html'){
			$data['result'] = $this->user_model->get_info('personnal_informations', $this->session->userdata('user_id'), $id);
			$this->load->view('pages/'.$page, $data);
		}
		if($page == 'change-password.html'){
			$data['user'] = $id;
			$this->load->view('pages/'.$page, $data);
		}
		
		$this->load->view('templates/footer.html');
	}

	public function announcement($page)
	{
		if (!file_exists(APPPATH.'views/pages/'.$page)) {
			show_404();
		}

		if($this->session->userdata('logged_in') == FALSE)
		{
			redirect('page/security/sign-in.html');
		}

		$this->load->view('templates/head.html');
		$this->load->view('templates/navigation.html');
		$this->load->view('pages/'.$page);
		$this->load->view('templates/footer.html');
	}

	public function logout()
	{
		redirect('pages/secure/login.html/');
	}
	
}