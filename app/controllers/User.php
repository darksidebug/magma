<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('zip');
        $this->load->library('upload');
        $this->load->model('User_Model', 'user_model');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache"); 
    }

    public function add_members(){
        $data = array(
            'full_name'                => $this->input->post('fullname'), 
            'user_call_sign'           => $this->input->post('call_sign'), 
            'chapter'                  => $this->input->post('chapter'), 
            'contact'                  => $this->input->post('contact'), 
            'present_employment'       => $this->input->post('employment'), 
            'home_address'             => $this->input->post('address'), 
            'date_of_birth'            => $this->input->post('date_of_birth'), 
            'place_of_birth'           => $this->input->post('palace_of_birth'), 
            'citizenship'              => $this->input->post('citizen'), 
            'gender'                   => $this->input->post('gender'), 
            'height'                   => $this->input->post('height'), 
            'weight'                   => $this->input->post('weight'), 
            'blood_type'               => $this->input->post('blood_type'), 
        );

        $result = $this->user_model->insert('personnal_informations', $data);
        $user_id = $result['id'];
        if($result['insert'] == TRUE){

            $other_data = array(
                'user_id'                  => $user_id,
                'user_call_sign'           => $this->input->post('call_sign'), 
                'emergency_contact_person' => $this->input->post('contact_person'), 
                'emergency_contact_num'    => $this->input->post('contact_person_num'), 
                'relation_to_member'       => $this->input->post('relation'), 
                'name_of_father'           => $this->input->post('name_of_father'), 
                'fathers_occupation'       => $this->input->post('father_occupation'), 
                'name_of_mother'           => $this->input->post('name_of_mother'), 
                'mothers_occupation'       => $this->input->post('mother_occupation')
            );
            $result = $this->user_model->insert('other_info', $other_data);

            $data_equipment = array(
                'user_id'        => $user_id,
                'user_call_sign' => $this->input->post('call_sign'), 
                'hf_radio'       => (!empty($this->input->post('hf_radio')) ? $this->input->post('hf_radio') : 0), 
                'vhf_radio'      => (!empty($this->input->post('vhf_radio')) ? $this->input->post('vhf_radio') : 0),
                'uhf_radio'      => (!empty($this->input->post('uhf_radio')) ? $this->input->post('uhf_radio') : 0), 
                'aerial_antenna' => (!empty($this->input->post('antenna')) ? $this->input->post('antenna') : 0),
                'spare_battery'  => (!empty($this->input->post('spare_battery')) ? $this->input->post('spare_battery') : 0),
                'generator'      => (!empty($this->input->post('generator')) ? $this->input->post('generator') : 0),
                'solar_panel'    => (!empty($this->input->post('solar_panel')) ? $this->input->post('solar_panel') : 0), 
                'battery'        => (!empty($this->input->post('battery')) ? $this->input->post('battery') : 0),
                'mobile_set_up'  => (!empty($this->input->post('mobile_set_up')) ? $this->input->post('mobile_set_up') : 0),
                'drone'          => (!empty($this->input->post('drone')) ? $this->input->post('drone') : 0)
            );

            $result_equip = $this->user_model->insert('equipment', $data_equipment);
            if($result_equip['insert'] == TRUE){

                if(!empty($this->input->post('child_siblings'))){
                    for ($i = 0; $i < count($this->input->post('child_siblings')); $i++) { 

                        $data_siblings_children = array(
                            'user_id'                 => $user_id,
                            'user_call_sign'          => $this->input->post('call_sign'), 
                            'siblings_childrens_name' => $this->input->post('child_siblings')[$i]
                        );

                        $result_sib = $this->user_model->insert('members_siblings_childrens', $data_siblings_children);
                    }
                }
                else{
                    $data_siblings_children = array(
                        'user_id'                 => $user_id,
                        'user_call_sign'          => $this->input->post('call_sign'), 
                        'siblings_childrens_name' => ""
                    );

                    $result_sib = $this->user_model->insert('members_siblings_childrens', $data_siblings_children);
                }

                if($result_sib['insert'] == TRUE){

                    if(!empty($this->input->post('school'))){
                        for ($i = 0; $i < count($this->input->post('school')); $i++) { 

                            $data_school_attended = array(
                                'user_id'        => $user_id,
                                'user_call_sign' => $this->input->post('call_sign'), 
                                'school'         => $this->input->post('school')[$i],
                                'date_graduate'  => $this->input->post('date_grad')[$i]
                            );
    
                            $result_school = $this->user_model->insert('school_attended', $data_school_attended);
                        }
                    }
                    else{
                        $data_school_attended = array(
                            'user_id'        => $user_id,
                            'user_call_sign' => $this->input->post('call_sign'), 
                            'school'         => "",
                            'date_graduate'  => ""
                        );

                        $result_school = $this->user_model->insert('school_attended', $data_school_attended);
                    }

                    if($result_school['insert'] == TRUE){
                        $data_account = array(
                            'user_id'        => $user_id,
                            'user_call_sign' => $this->input->post('call_sign'), 
                            'username'       => $this->input->post('username'),
                            'user_pass'      => $this->hash_password($this->input->post('pass')),
                            'user_type'      => 'Member',
                            'chapter'        => $this->input->post('chapter')
                        );

                        $result_account = $this->user_model->insert('user_account', $data_account);
                        if($result_account['insert'] == TRUE){
                            
                            if($this->session->userdata('user_type') == 'Guest'){
                                $this->session->unset_userdata(['logged_in', 'user_type', 'user_call_sign']);
                            }
                            $session = array(
                                'logged_in'      => TRUE,
                                'user_type'      => 'Member',
                                'user_id'        => $user_id,
                                'user_call_sign' => $this->input->post('call_sign'),
                                'chapter'        => $this->input->post('chapter')
                            );
                            $this->session->set_userdata($session);
                            $user_data = array(
                                'insert'    => true,
                                'message'   => 'Members information successfully save!',
                                'user_type' => 'Member'
                            );
                            return $user_data;
                        }
                        else{
                            $user_data = array(
                                'insert'    => false,
                                'message'   => 'There was a problem in saving members account information!',
                                'user_type' => $this->session->userdata('user_type')
                            );
                            return $user_data;
                        }
                    }
                    else{
                        $user_data = array(
                            'insert'    => false,
                            'message'   => 'There was a problem in saving members educational background information!',
                            'user_type' => $this->session->userdata('user_type')
                        );
                        return $user_data;
                    }
                
                }
                else{
                    $user_data = array(
                        'insert'    => false,
                        'message'   => 'There was a problem in saving members siblings/childrens information!',
                        'user_type' => $this->session->userdata('user_type')
                    );
                    return $user_data;
                }
            
            }
            else{
                $user_data = array(
                    'insert'    => false,
                    'message'   => 'There was a problem in saving members set-up/equipment information!',
                    'user_type' => $this->session->userdata('user_type')
                );
                return $user_data;
            }
        }
        else{
            $user_data = array(
                'insert'    => false,
                'message'   => 'There was a problem in saving members personnal information!',
                'user_type' => ($this->session->userdata('user_type') != "" ? $this->session->userdata('user_type') : "")
            );
            return $user_data;
        }
    }

    public function update(){
        $data = array(
            'full_name'                => $this->input->post('fullname'), 
            'user_call_sign'           => $this->input->post('call_sign'), 
            'chapter'                  => $this->input->post('chapter'), 
            'contact'                  => $this->input->post('contact'), 
            'present_employment'       => $this->input->post('employment'), 
            'home_address'             => $this->input->post('address'), 
            'date_of_birth'            => $this->input->post('date_of_birth'), 
            'place_of_birth'           => $this->input->post('palace_of_birth'), 
            'citizenship'              => $this->input->post('citizen'), 
            'gender'                   => $this->input->post('gender'), 
            'height'                   => $this->input->post('height'), 
            'weight'                   => $this->input->post('weight'), 
            'blood_type'               => $this->input->post('blood_type'), 
        );

        $result = $this->user_model->update('personnal_informations', $data, $this->input->post('id'));
        if($result['update'] == TRUE){

            $other_data = array(
                'user_call_sign'           => $this->input->post('call_sign'), 
                'emergency_contact_person' => $this->input->post('contact_person'), 
                'emergency_contact_num'    => $this->input->post('contact_person_num'), 
                'relation_to_member'       => $this->input->post('relation'), 
                'name_of_father'           => $this->input->post('name_of_father'), 
                'fathers_occupation'       => $this->input->post('father_occupation'), 
                'name_of_mother'           => $this->input->post('name_of_mother'), 
                'mothers_occupation'       => $this->input->post('mother_occupation'),
                'sign_at'                  => $this->input->post('sign_at'),
                'date_register'            => $this->input->post('date_sign')
            );
            $result = $this->user_model->update_info('other_info', $other_data, $this->input->post('id'));

            $data_equipment = array(
                'user_call_sign' => $this->input->post('call_sign'), 
                'hf_radio'       => (!empty($this->input->post('hf_radio')) ? $this->input->post('hf_radio') : 0), 
                'vhf_radio'      => (!empty($this->input->post('vhf_radio')) ? $this->input->post('vhf_radio') : 0),
                'uhf_radio'      => (!empty($this->input->post('uhf_radio')) ? $this->input->post('uhf_radio') : 0), 
                'aerial_antenna' => (!empty($this->input->post('antenna')) ? $this->input->post('antenna') : 0),
                'spare_battery'  => (!empty($this->input->post('spare_battery')) ? $this->input->post('spare_battery') : 0),
                'generator'      => (!empty($this->input->post('generator')) ? $this->input->post('generator') : 0),
                'solar_panel'    => (!empty($this->input->post('solar_panel')) ? $this->input->post('solar_panel') : 0), 
                'battery'        => (!empty($this->input->post('battery')) ? $this->input->post('battery') : 0),
                'mobile_set_up'  => (!empty($this->input->post('mobile_set_up')) ? $this->input->post('mobile_set_up') : 0),
                'drone'          => (!empty($this->input->post('drone')) ? $this->input->post('drone') : 0)
            );

            $result_equip = $this->user_model->update_info('equipment', $data_equipment, $this->input->post('id'));
            if($result_equip['update'] == TRUE){

                if(!empty($this->input->post('child_siblings'))){
                    for ($i = 0; $i < count($this->input->post('child_siblings')); $i++) { 

                        $data_siblings_children = array(
                            'user_call_sign'          => $this->input->post('call_sign'), 
                            'siblings_childrens_name' => $this->input->post('child_siblings')[$i]
                        );

                        $result_sib = $this->user_model->update_info('members_siblings_childrens', $data_siblings_children, $this->input->post('id'));
                    }
                }
                else{
                    $data_siblings_children = array(
                        'user_call_sign'          => $this->input->post('call_sign'), 
                        'siblings_childrens_name' => ""
                    );

                    $result_sib = $this->user_model->update_info('members_siblings_childrens', $data_siblings_children, $this->input->post('id'));
                }

                if($result_sib['update'] == TRUE){

                    if(!empty($this->input->post('school'))){
                        for ($i = 0; $i < count($this->input->post('school')); $i++) { 

                            $data_school_attended = array(
                                'user_call_sign' => $this->input->post('call_sign'), 
                                'school'         => $this->input->post('school')[$i],
                                'date_graduate'  => $this->input->post('date_grad')[$i]
                            );

                            $result_school = $this->user_model->update_info('school_attended', $data_school_attended, $this->input->post('id'));
                        }
                    }
                    else{
                        $data_school_attended = array(
                            'user_call_sign' => $this->input->post('call_sign'), 
                            'school'         => "",
                            'date_graduate'  => ""
                        );

                        $result_school = $this->user_model->update_info('school_attended', $data_school_attended, $this->input->post('id'));
                    }

                    if($result_school['update'] == TRUE){
                            $user_data = array(
                                'update'    => true,
                                'message'   => 'Members information successfully updated!',
                                'user_type' => $this->session->userdata('user-type')
                            );
                            echo json_encode($user_data);
                    }
                    else{
                        $user_data = array(
                            'update'    => false,
                            'message'   => 'There was a problem in updating members educational background information!',
                            'user_type' => $this->session->userdata('user-type')
                        );
                        echo json_encode($user_data);
                    }
                }
                else{
                    $user_data = array(
                        'update'    => false,
                        'message'   => 'There was a problem in updating members siblings/childrens information!',
                        'user_type' => $this->session->userdata('user-type')
                    );
                    echo json_encode($user_data);
                }
            }
            else{
                $user_data = array(
                    'update'    => false,
                    'message'   => 'There was a problem in updating members set-up/equipment information!',
                    'user_type' => $this->session->userdata('user-type')
                );
                echo json_encode($user_data);
            }
        }
        else{
            $user_data = array(
                'update'    => false,
                'message'   => 'There was a problem in updating members personnal information!',
                'user_type' => ($this->session->userdata('user-type') != "" ? $this->session->userdata('user-type') : "")
            );
            echo json_encode($user_data);
        }
    }

    public function verify_call_sign($verifier, $table){
        $return_result = $this->user_model->verify($table, $this->input->post('call_sign'));
        if($return_result['result'] == TRUE){
            $user_data = array(
                'taken'    => true,
                'message'   => 'The call sign is already taken, choose another one.',
            );
            echo json_encode($user_data);
        }
        else{
            
            $result = $this->user_model->get_username('user_account', $this->input->post('user'));
            if($result['result'] == TRUE){
                $user_data = array(
                    'taken'    => true,
                    'message'   => 'The username is already taken, choose another one.',
                );
                echo json_encode($user_data);
            }
            else{
                if($verifier == 'add-members'){
                    $result = $this->add_members();
                    echo json_encode($result);
                }
                elseif($verifier == 'system-user'){
                    $result = $this->add_system_user();
                    echo json_encode($result);
                }
            }
        }
    }

    public function add_system_user(){
        $data_account = array(
            'user_call_sign' => $this->input->post('call_sign'), 
            'username'       => $this->input->post('user'),
            'user_pass'      => $this->hash_password($this->input->post('pass')),
            'user_type'      => $this->input->post('user_type'),
            'chapter'        => $this->session->userdata('chapter'),
        );
        $result = $this->user_model->add('user_account', $data_account);
        if($result['insert'] == TRUE){
            $user_data = array(
                'insert'    => true,
                'message'   => 'System user account successfully save!'
            );
            return $user_data;
        }
        else{
            $user_data = array(
                'insert'    => false,
                'message'   => 'There was a problem in saving system user account!'
            );
            return $user_data;
        }
    }

    public function insert_into_table($table, $data){
        return $result = $this->user_model->insert($table, $data);
    }

    public function sign_in(){
        if(isset($_POST['user'])){

            $return_result = $this->user_model->sign_in('user_account', $this->input->post('user'), 
                                $this->input->post('pass'));
            if($return_result['result'] == TRUE){
                $session = array(
                    'logged_in'      => $return_result['result'],
                    'user_type'      => $return_result['user-type'],
                    'user_id'        => $return_result['user-id'],
                    'user_call_sign' => $return_result['user_call_sign'],
                    'chapter'        => $return_result['chapter']
                );
                $this->session->set_userdata($session);
                $user_data = array(
                    'login'          => true,
                    'user_type'      => $return_result['user-type'],
                    'user_call_sign' => $return_result['user_call_sign'],
                    'message'        => 'You are now logged in successfully.'
                );
                echo json_encode($user_data);
            }
            else{
                $user_data = array(
                    'login'      => false,
                    'user_type'  => '',
                    'message'    => "Username or password is invalid!",
                );
                echo json_encode($user_data);
            }
        }
    }

    public function update_user(){
        $data = array(
            'username' => $this->input->post('user'),
            'user_pass' => $this->hash_password($this->input->post('pass')),
            'user_type'=> $this->input->post('user_type')
        );
        $return_result = $this->user_model->update('user_account', $data, $this->input->post('id'));
        if($return_result['update'] == TRUE){
            $user_data = array(
                'update'          => true,
                'message'        => 'User credential updated successfully.'
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'update'      => false,
                'message'    => "Cannot update user credential.",
            );
            echo json_encode($user_data);
        }
    }

    public function change_pass(){
        $data = array(
            'user_pass' => $this->hash_password($this->input->post('pass')),
        );
        $return_result = $this->user_model->reset('user_account', $data, $this->input->post('user'));
        if($return_result['update'] == TRUE){

            $return_result = $this->user_model->sign_in('user_account', $this->input->post('user'), 
                                $this->input->post('pass'));
            if($return_result['result'] == TRUE){
                $session = array(
                    'logged_in'      => $return_result['result'],
                    'user_type'      => $return_result['user-type'],
                    'user_id'        => $return_result['user-id'],
                    'user_call_sign' => $return_result['user_call_sign'],
                    'chapter'        => $return_result['chapter']
                );
                $this->session->set_userdata($session);
                $user_data = array(
                    'login'          => true,
                    'user_type'      => $return_result['user-type'],
                    'user_call_sign' => $return_result['user_call_sign'],
                    'message'        => 'You are now logged in successfully.'
                );
                echo json_encode($user_data);
            }
            else{
                $user_data = array(
                    'login'      => false,
                    'user_type'  => '',
                    'message'    => "Username or password is invalid!",
                );
                echo json_encode($user_data);
            }
        }
        else{
            $user_data = array(
                'update'      => false,
                'message'    => "Cannot update user credential.",
            );
            echo json_encode($user_data);
        }
    }

    public function reset(){
        $data = array(
            'user_pass' => $this->hash_password($this->input->post('pass')),
        );
        $return_result = $this->user_model->reset('user_account', $data, $this->input->post('user'));
        if($return_result['update'] == TRUE){
            $user_data = array(
                'update'         => true,
                'message'        => 'User credential updated successfully.',
                'user_call_sign' => $this->session->userdata('user_call_sign')
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'update'      => false,
                'message'    => "Cannot update user credential.",
            );
            echo json_encode($user_data);
        }
    }

    public function delete(){
        $result = $this->user_model->delete('user_account', $this->input->post('id'));
        if($return_result['delete'] == TRUE){
            $user_data = array(
                'delete'          => true,
                'message'        => 'User credential deleted successfully.'
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'delete'      => false,
                'message'    => "Cannot delete user credential.",
            );
            echo json_encode($user_data);
        }
    }

    public function set_bill(){
        $result = $this->user_model->get_all('personnal_informations');
        foreach ($result as $key => $value) {
            $data = array(
                'user_id'        => $value->id,
                'user_call_sign' => $value->user_call_sign,
                'bill_month'     => $this->input->post('bill_month'),
                'bill_amount'    => $this->input->post('bill_amount'),
                'bill_status'    => 'Unpaid'
            );

            $insert = $this->user_model->insert('billings', $data);
        }

        if($insert['insert'] == TRUE){
            $user_data = array(
                'set_bill'       => true,
                'message'        => 'Members billing for the month of '.date('F - Y', strtotime($this->input->post('bill_month'))).' is set.'
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'set_bill'   => false,
                'message'    => "There was a problem in setting up the bill for members.",
            );
            echo json_encode($user_data);
        }
    }

    // public function get_bills($table = 'personnal_informations', $table2 = 'billings'){
    //     $query = $this->db->get($table)->result();
    //     foreach ($query as $key => $value) {
    //         $sql = "SELECT COUNT(*) AS num_of_months FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
    //         $count_month = $this->db->query($sql)->result();

    //         foreach ($count_month as $key => $value1) {
    //             $count_month = $value1->num_of_months;
    //         }
    //         $value->count_month = $count_month;

    //         $sql = "SELECT MAX(bill_month) AS month_from FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
    //         $month_from = $this->db->query($sql)->result();

    //         $sql = "SELECT MIN(bill_month) AS month_to FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
    //         $month_to = $this->db->query($sql)->result();

    //         foreach ($month_from as $key => $value1) {
    //             $month_from = $value1->month_from;
    //         }
    //         foreach ($month_to as $key => $value1) {
    //             $month_to = $value1->month_to;
    //         }
    //         $value->month = $month_from.' - '.$month_to;

    //         $sql = "SELECT COUNT(bill_amount) AS amount FROM ".$table2." WHERE user_id = '".$value->id."' AND bill_status = 'Unpaid' ";
    //         $total = $this->db->query($sql)->result();

    //         foreach ($total as $key => $value1) {
    //             $total = $value1->amount;
    //         }
    //         $value->total = $total;
    //     }
    //     echo '<pre>';
    //     print_r(['result' => $query]);
    // }
    
    public function pay_bill(){
        $total = 0;
        $id;
        $result = $this->user_model->get_billing('billings', $this->input->post('user_id'), 'Unpaid', 
                    $this->input->post('bill_month'));
        foreach ($result as $key => $value) {
            $id = $value->id;
            $total = $value->bill_amount;
        }

        $total = $total - $this->input->post('bill_amount');
        if($total  == 0){
            $data = array(
                'bill_amount'    => $total,
                'bill_status'    => ($total == 0 ? 'Paid' : 'Unpaid'),
                'date_bill_paid' => date('Y-m-d')
            );
        }
        else{
            $data = array(
                'bill_amount'    => $total,
                'bill_status'    => ($total == 0 ? 'Paid' : 'Unpaid')
            );
        }

        $update = $this->user_model->update_billing('billings', $data, $id);
        if($update['update'] == TRUE){

            $insert = array(
                'user_id'        => $this->input->post('user_id'),
                'user_call_sign' => $this->input->post('call_sign'),
                'bill_month'     => $this->input->post('bill_month'),
                'payment_amount' => $this->input->post('bill_amount'),
                'date_paid'      => $this->input->post('date')
            );
            $insert_result = $this->user_model->insert('payments', $insert);
            if($insert_result['insert'] == TRUE){
                $user_data = array(
                    'pay_bill'       => true,
                    'message'        => 'Members billing for the month of '.date('F - Y', strtotime($this->input->post('bill_month'))).' is set.'
                );
                echo json_encode($user_data);
            }
            else{
                $user_data = array(
                    'pay_bill'   => false,
                    'message'    => "There was a problem in setting up the bill for members.",
                );
                echo json_encode($user_data);
            }
        }
        else{
            $user_data = array(
                'pay_bill'   => false,
                'message'    => "There was a problem in setting up the bill for members.",
            );
            echo json_encode($user_data);
        }
    }

    public function delete_info(){
        $result = $this->user_model->delete('personnal_informations', $this->input->post('id'));
        if($return_result['delete'] == TRUE){

            $other = $this->user_model->delete_info('other_info', $this->input->post('id'));
            if($other['delete'] == TRUE){

                $equip = $this->user_model->delete_info('equipment', $this->input->post('id'));
                if($equip['delete'] == TRUE){

                    $members_sib_chil = $this->user_model->delete_info('members_siblings_childrens', $this->input->post('id'));
                    if($members_sib_chil['delete'] == TRUE){

                        $school = $this->user_model->delete_info('school_attended', $this->input->post('id'));
                        if($school['delete'] == TRUE){

                            $user = $this->user_model->delete_info('user_account', $this->input->post('id'));
                            if($user['delete'] == TRUE){
                                $user_data = array(
                                    'delete'          => true,
                                    'message'        => 'User credential deleted successfully.'
                                );
                                echo json_encode($user_data);
                            }
                            else{
                                $user_data = array(
                                    'delete'      => false,
                                    'message'    => "Cannot delete user credential.",
                                );
                                echo json_encode($user_data);
                            }
                        }
                        else{
                            $user_data = array(
                                'delete'      => false,
                                'message'    => "Cannot delete member school information.",
                            );
                            echo json_encode($user_data);
                        }
                    }
                    else{
                        $user_data = array(
                            'delete'      => false,
                            'message'    => "Cannot delete member children/siblings.",
                        );
                        echo json_encode($user_data);
                    }
                }
                else{
                    $user_data = array(
                        'delete'      => false,
                        'message'    => "Cannot delete members setup/equipment.",
                    );
                    echo json_encode($user_data);
                }
            }
            else{
                $user_data = array(
                    'delete'      => false,
                    'message'    => "Cannot delete member other contact information.",
                );
                echo json_encode($user_data);
            }
        }
        else{
            $user_data = array(
                'delete'      => false,
                'message'    => "Cannot delete member personnal information.",
            );
            echo json_encode($user_data);
        }
    }

    public function upload_image($id){
        if(isset($_FILES['file']['name'])){
            $config['upload_path']          = './upload';
            $config['allowed_types']        = 'jpeg|jpg|png|pdf|docx';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file'))
            {
                $error = array('error' => $this->upload->display_errors());
                $error = explode('<p>', $error['error']);
                $error = explode('</p>', $error[1]);
                $user_data = array(
                    'upload'    => false,
                    'message'   => $error[0]
                );
                echo json_encode($user_data);
            }
            else
            {
                $file_name = $this->upload->data('file_name');
                $data = array(
                    'image_name'   => $file_name
                );
                $result = $this->user_model->update('user_account', $data, $id);
                if($result['update'] == TRUE){
                    $user_data = array(
                        'upload'    => true,
                        'message'   => 'Image uploaded successfully.'
                    );
                    echo json_encode($user_data);
                }
                else{
                    $user_data = array(
                        'upload'    => false,
                        'message'   => 'There was a problem in uploading the image!'
                    );
                    echo json_encode($user_data);
                }
            }
        }
    }

    public function create_announcement(){
        $data = array(
            're'      => $this->input->post('re'),
            'subject' => $this->input->post('subject'),
            'date'    => $this->input->post('date'),
            'content' => $this->input->post('content'),
            'chapter' => $this->session->userdata('chapter'),
            'closing' => $this->input->post('closing')
        );
        $insert_result = $this->user_model->insert('announcement', $data);
        if($insert_result['insert'] == TRUE){
            $user_data = array(
                'insert'   => true,
                'message'  => 'Announcement created successfully.'
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'insert'     => false,
                'message'    => "There was a problem in creating announcement.",
            );
            echo json_encode($user_data);
        }
    }

    public function update_announcement(){
        $data = array(
            're'      => $this->input->post('re'),
            'subject' => $this->input->post('subject'),
            'date'    => $this->input->post('date'),
            'content' => $this->input->post('content'),
            'chapter' => $this->session->userdata('chapter'),
            'closing' => $this->input->post('closing')
        );
        $insert_result = $this->user_model->update('announcement', $data, $this->input->post('id'));
        if($insert_result['update'] == TRUE){
            $user_data = array(
                'update'   => true,
                'message'  => 'Announcement updated successfully.'
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'update'     => false,
                'message'    => "There was a problem in updating announcement.",
            );
            echo json_encode($user_data);
        }
    }

    public function get_comments(){
        $result = $this->user_model->get_all_comments('comments', $this->input->post('id'));
        echo json_encode($result);
    }

    public function save_comment(){
        $data = array(
            'announcement_id' => $this->input->post('id'),
            'message'         => $this->input->post('comment'),
            'user_id'         => $this->session->userdata('user_id'),
            'chapter'         => $this->session->userdata('chapter'),
            'user_call_sign'  => $this->session->userdata('user_call_sign')
        );
        $insert_result = $this->user_model->insert('comments', $data);
        if($insert_result['insert'] == TRUE){
            $user_data = array(
                'insert'   => true,
                'message'  => 'Comment successfull.'
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'insert'     => false,
                'message'    => "Comment failed.",
            );
            echo json_encode($user_data);
        }
    }

    public function delete_announcement(){
        $result = $this->user_model->delete_announcement('announcement', $this->input->post('id'));
        if($result['delete'] == TRUE){
            $user_data = array(
                'delete'   => true,
                'message'  => 'Deletion successfull.'
            );
            echo json_encode($user_data);
        }
        else{
            $user_data = array(
                'delete'     => false,
                'message'    => "Deletion failed.",
            );
            echo json_encode($user_data);
        }
    }

    public function get_announcement(){
        $insert_result = $this->user_model->get_announcement('announcement');
        echo json_encode($insert_result);
    }

    public function get_announcement_byId(){
        $insert_result = $this->user_model->get_announcement_byId('announcement', $this->input->post('id'));
        echo json_encode($insert_result);
    }

    public function logout(){
        $this->session->unset_userdata(['logged_in', 'user_type', 'user_call_sign']);
        redirect('page/security/sign-in.html');
    }

    public function hash_password($password){
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

}