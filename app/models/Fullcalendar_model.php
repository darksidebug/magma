<?php

class Fullcalendar_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function fetch_all_event(){
        $this->db->order_by('id');
        return $this->db->get('events');
    }

    public function insert_event($data)
    {
        $this->db->insert('events', $data);
    }

    public function update_event($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('events', $data);
    }

    public function delete_event($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('events');
    }
}
