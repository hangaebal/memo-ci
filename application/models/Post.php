<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Model {

    public function get_main_list()
    {
        $query = $this->db->query("SELECT *
            FROM post
            WHERE del_yn = 'N'
            ORDER BY seq
		");
        return $query->result();
    }

    public function get($id) {
        $query = $this->db->query("SELECT *
            FROM post
            WHERE id = ".$this->db->escape($id)."
            AND del_yn = 'N'
		");
        return $query->row();
    }
}
