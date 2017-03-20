<?php
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

}
