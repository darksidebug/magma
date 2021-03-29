<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FullCalendar extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Fullcalendar_model');
    }

    // function index()
    // {
    //     $this->load->view('fullcalendar');
    // }

    function load()
    {
        $event_data = $this->Fullcalendar_model->fetch_all_event();
        foreach($event_data->result_array() as $row)
        {
            $data[] = array(
                'id'    => $row['id'],
                'title' => $row['title'],
                'start' => $row['start_event'],
                'end'   => $row['end_event']
            );
        }
        echo json_encode($data);
    }

    public function insert()
    {
        if($this->input->post('title'))
        {
            $data = array(
                'title'      => $this->input->post('title'),
                'start_event'=> $this->input->post('start'),
                'end_event'  => $this->input->post('end')
            );
            $this->Fullcalendar_Model->insert_event($data);
        }
    }

    public function cal_event($action)
    {
        if($action == 'update'){
            $id = $this->input->post('id');
            $data = array(
                'title'       => $this->input->post('title'),
                'start_event' => $this->input->post('start'),
                'end_event'   => $this->input->post('end')
            );
            $return_result = $this->Fullcalendar_model->update_event($data, $id);
            echo json_encode($return_result);
        }  
        else if($action == 'insert'){
            $id = $this->input->post('id');
            $data = array(
                'title'       => $this->input->post('title'),
                'start_event' => $this->input->post('start'),
                'end_event'   => $this->input->post('end')
            );
            $return_result = $this->Fullcalendar_model->insert_event($data, $id);
            echo json_encode($return_result);
        }   
        else if($action == 'delete'){
            $id = $this->input->post('id');
            $return_result = $this->Fullcalendar_model->delete_event($id);
            echo json_encode($return_result);
        } 
    }

    public function delete()
    {
        if($this->input->post('id'))
        {
            $this->Fullcalendar_Model->delete_event($this->input->post('id'));
        }
    }

}

