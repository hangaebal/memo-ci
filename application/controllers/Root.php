<?php
class Root extends CI_Controller {
    var $header_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu');
        $this->load->model('post');
        $this->header_data['menu_list'] = $this->menu->get_main_list();
        $this->header_data['post_list'] = $this->post->get_main_list();
    }


    public function index()
    {
        $this->load->view('templates/header', $this->header_data);
        $this->load->view('root/index');
        $this->load->view('templates/footer');
    }


}