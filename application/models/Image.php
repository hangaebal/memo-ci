<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Model {

    public function get_post_image_list($post_id)
    {
        $query = $this->db->where(array('del_yn' => 'N', 'post_id' => $post_id))->order_by('seq')->get('image');
        return $query->result();
    }

    public function insert($image)
    {
        $this->db->insert('image', $image);
    }

    public function update($image)
    {
        $this->db->where('id', $image['id']);
        unset($image['id']);
        $this->db->update('image', $image);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->set('del_yn', 'Y');
        $this->db->update('image');
    }

    public function delete_post_image($post_id)
    {
        $this->db->where('post_id', $post_id);
        $this->db->set('del_yn', 'Y');
        $this->db->update('image');
    }
}