<?php

class Image extends CI_Model {

    public function get_post_image_list($post_id)
    {
        $query = $this->db->query("SELECT *
            FROM image
            WHERE
                post_id = ".$this->db->escape($post_id)."
                AND del_yn = 'N'
            ORDER BY seq
		");
        return $query->result();
    }
}