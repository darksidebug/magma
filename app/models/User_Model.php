<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class User_Model extends CI_Model {

        public function insert($table, $data){
            $result = $this->db->insert($table, $data);
            if($result){
                return ['insert' => TRUE, 'id' => $this->db->insert_id()];
            }
            else{
                return ['insert' => FALSE, 'id' => ''];
            }
        }

        public function get_username($table, $user){
            $this->db->where('username', $user);
            $result = $this->db->get($table);
            if($result->num_rows() > 0){
                return ['result' => TRUE];
            }
            else{
                return ['result' => FALSE];
            }
        }

        public function add($table, $data){
            $result = $this->db->insert($table, $data);
            if($result){
                return ['insert' => TRUE];
            }
            else{
                return ['insert' => FALSE];
            }
        }

        public function sign_in($table, $username, $pass)
        {
                      $this->db->where('username', $username);
            $result = $this->db->get($table);
            if($result->num_rows() > 0){

                foreach ($result->result() as $row => $value) {
                    
                    if($this->hash_verify($pass, $value->user_pass))
                    {
                        return ['result' => TRUE, 'user-type' => $value->user_type, 'user_call_sign' => $value->user_call_sign, 
                        'chapter' => $value->chapter, 'user-id' => $value->user_id];
                    }
                }
            }
            else{
                return ['result' => FALSE, 'user-type' => '', 'user_call_sign' => '', 'chapter' => ''];
            }
        }

        public function verify($table, $call_sign){
                      $this->db->where('user_call_sign', $call_sign);
            $result = $this->db->get($table);
            if($result->num_rows() > 0){
                return ['result' => TRUE];
            }
            else{
                return ['result' => FALSE];
                return FALSE;
            }
        }

        public function get($table){
            $this->db->where('chapter', $this->session->userdata('chapter'));
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get($table);
            if($query->num_rows() > 0){
                return $query->result();
            }
            else{
                return false;
            }
        }

        public function get_payments($table, $id){
            $this->db->where('user_id', $id);
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get($table);
            if($query->num_rows() > 0){
                return $query->result();
            }
            else{
                return false;
            }
        }

        public function get_my_bills($table){
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get($table);
            return $query->result();
        }

        public function get_bills($table1, $table2){
            $this->db->where('chapter', $this->session->userdata('chapter'));
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get($table1)->result();
            foreach ($query as $key => $value) {
                $sql = "SELECT COUNT(*) AS num_of_months FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
                $count_month = $this->db->query($sql)->result();

                foreach ($count_month as $key => $value1) {
                    $count_month = $value1->num_of_months;
                }
                $value->count_month = $count_month;

                $sql = "SELECT MAX(bill_month) AS month_from FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
                $month_from = $this->db->query($sql)->result();

                $sql = "SELECT MIN(bill_month) AS month_to FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
                $month_to = $this->db->query($sql)->result();

                foreach ($month_from as $key => $value1) {
                    $month_from = $value1->month_from;
                }
                foreach ($month_to as $key => $value1) {
                    $month_to = $value1->month_to;
                }
                if($month_from != $month_to){
                    $value->month = $month_from.' - '.$month_to;
                }
                else{
                    $value->month = $month_from;
                }

                $sql = "SELECT SUM(bill_amount) AS amount FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
                $total = $this->db->query($sql)->result();

                foreach ($total as $key => $value1) {
                    $total = $value1->amount;
                }
                $value->total = $total;
            }
            return ['result' => $query];
        }

        public function get_all($table){
            $this->db->where('chapter', $this->session->userdata('chapter'));
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get($table);
            return $query->result();
        }

        public function get_where($table, $value){
            $this->db->where("id", $value);
            $query = $this->db->get($table);
            if($query->num_rows() > 0){
                return $query->result();
            }
            else{
                return false;
            }
        }

        public function get_all_comments($table, $id){
            $this->db->where("announcement_id", $id);
            $this->db->where("chapter", $this->session->userdata('chapter'));
            $this->db->order_by("id", 'DESC');
            $query = $this->db->get($table)->result();
            foreach ($query as $key => $value) {
                $this->db->where("user_id", $value->user_id);
                $this->db->where("chapter", $this->session->userdata('chapter'));
                $value->account = $this->db->get('user_account')->result();
                $value->date_comment = date('F d, Y h:m:s', strtotime($value->date_commented));
            }
            return $query;
        }

        public function get_announcement($table){
            $sql = "SELECT MAX(id) AS id FROM ".$table." WHERE chapter = '".$this->session->userdata('chapter')."' ";
            $result = $this->db->query($sql)->result();
            foreach ($result as $key => $value) {
                $this->db->where('id', $value->id);
                $query = $this->db->get($table);
            }
            return $query->result();
        }

        public function get_announcement_byId($table, $id){
            $this->db->where("id", $id);
            $this->db->where("chapter", $this->session->userdata('chapter'));
            $result = $this->db->get($table)->result();
            return $result;
        }

        public function delete_announcement($table, $id){
            $this->db->where("id", $id);
            $result = $this->db->delete($table);
            if($result){
                $this->db->where("announcement_id", $id);
                $delete = $this->db->delete('comments');
                if($delete){
                    return ['delete' => TRUE];
                }
                else{
                    return ['delete' => FALSE];
                }
            }
            else{
                return ['delete' => FALSE];
            }
        }

        public function get_billing($table, $value, $status, $month){
            $this->db->where("user_id", $value);
            $this->db->where("bill_month", $month);
            $this->db->where("bill_status", $status);
            $this->db->where('chapter', $this->session->userdata('chapter'));
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get($table);
            if($query->num_rows() > 0){
                return $query->result();
            }
            else{
                return false;
            }
        }

        public function get_info($table, $id, $call_sign){
            $this->db->where('id', $id);
            $result = $this->db->get($table)->result();
            
            $this->db->where('user_id', $id);
            $this->db->where('user_call_sign', $call_sign);
            $other = $this->db->get('other_info')->result();

            $this->db->where('user_id', $id);
            $this->db->where('user_call_sign', $call_sign);
            $equip = $this->db->get('equipment')->result();

            $this->db->where('user_id', $id);
            $this->db->where('user_call_sign', $call_sign);
            $siblings = $this->db->get('members_siblings_childrens')->result_array();

            $this->db->where('user_id', $id);
            $this->db->where('user_call_sign', $call_sign);
            $school = $this->db->get('school_attended')->result_array();

            $this->db->where('user_id', $id);
            $this->db->where('user_call_sign', $call_sign);
            $acc = $this->db->get('user_account')->result();

            return ['personnal_info' => $result, 'other_info' => $other, 'equipment' => $equip, 
                    'children_sibling' => $siblings, 'school_attended' => $school, 'account' => $acc];
        }

        public function update($table, $data,  $value){
            $this->db->where("id", $value);
            $this->db->set($data);
            $query = $this->db->update($table); 
            if($query){
                return ['update' => TRUE];
            }
            else{
                return ['update' => FALSE];
            }
        }

        public function reset($table, $data,  $value){
            $this->db->where("username", $value);
            $this->db->set($data);
            $query = $this->db->update($table); 
            if($query){
                return ['update' => TRUE];
            }
            else{
                return ['update' => FALSE];
            }
        }

        public function update_billing($table, $data,  $value){
            $this->db->where("id", $value);
            $this->db->set($data);
            $query = $this->db->update($table); 
            if($query){
                return ['update' => TRUE];
            }
            else{
                return ['update' => FALSE];
            }
        }

        public function update_info($table, $data, $value){
            $this->db->where("user_id", $value);
            $this->db->set($data);
            $query = $this->db->update($table); 
            if($query){
                return ['update' => TRUE];
            }
            else{
                return ['update' => FALSE];
            }
        }

        public function delete($table, $value){
            $this->db->where("id", $value);
            $query = $this->db->delete($table); 
            if($query){
                return ['delete' => TRUE];
            }
            else{
                return ['delete' => FALSE];
            }
        }

        public function delete_info($table, $value){
            $this->db->where("user_id", $value);
            $query = $this->db->delete($table); 
            if($query){
                return ['delete' => TRUE];
            }
            else{
                return ['delete' => FALSE];
            }
        }

        private function hash_verify($password, $hashed_password){
            return password_verify($password, $hashed_password);
        }

        // public function get__borrower_details($id, $action_taken){
        //     $this->db->select('*');
        //     $this->db->from('register_borrower');
        //     $this->db->join('borrow', 'borrow.ID_num = register_borrower.ID_num');
        //     $this->db->where('register_borrower.ID_num', $id); 
        //     $this->db->where('borrow.action_taken', $action_taken);
        //     $query_result = $this->db->get();
        //     return $query_result->result();
        // }

    }