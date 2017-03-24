<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Model {

    public function get_main_list()
    {
        $query = $this->db->where('del_yn', 'N')->order_by('seq')->get('post');
        return $query->result();
    }

    public function get($id) {
        $this->db->where('id', $id);
        $this->db->where('del_yn', 'N');
        $query = $this->db->get('post');

        return $query->row();
    }

    public function get_list($menu_id)
    {
        if (empty($menu_id)) {
            $this->db->where('menu_id = (SELECT id FROM menu ORDER BY seq LIMIT 1)');
        } else {
            $this->db->where('menu_id', $menu_id);
        }
        $query = $this->db->where('del_yn', 'N')->order_by('seq')->get('post');

        return $query->result();

    }

    public function update_seq($post)
    {
        $this->db->set('seq', $post['seq']);
        $this->db->where('id', $post['id']);
        $this->db->update('post');
    }


}
