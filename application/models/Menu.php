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
}