<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Login_Model extends CI_Model {
        
        public function __construct(){
            $this->load->database();
        }

        public function sign_in($email, $pass){
            $this->db->where('user', $email);
            $this->db->where('pass', $pass);

            $result = $this->db->get('login');
            if($result->num_rows() == 1){
                return $result->row(0)->id;
            }
            else{
                return false;
            }
        }
    }