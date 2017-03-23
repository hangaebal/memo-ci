<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Model {

    public function get_main_list()
    {
        $query = $this->db->query("SELECT
                *
                ,IF(y.year_menu_id IS NULL, 'N', 'Y') AS has_year
            FROM menu
            LEFT OUTER JOIN (
                SELECT menu_id AS year_menu_id FROM post
                WHERE del_yn = 'N'
                AND year != ''
                GROUP BY year_menu_id
            ) y
            ON menu.id = y.year_menu_id
            WHERE del_yn = 'N'
            ORDER BY seq
		");
        return $query->result();
    }

    public function get_list()
    {
        $query = $this->db->where('del_yn', 'N')->order_by('seq')->get('menu');
        return $query->result();
    }

    public function insert($menu)
    {
        $this->db->insert('menu', $menu);
    }

    public function update($menu)
    {
        $this->db->where('id', $menu['id']);
        unset($menu['id']);
        $this->db->update('menu', $menu);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->set('del_yn', 'Y');
        $this->db->update('menu');
    }



}