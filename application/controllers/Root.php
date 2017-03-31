<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

    public function post($id)
    {

        $data['post'] = $this->post->get($id);
        if (empty($data['post'])) {
            show_404();
        }
        $type = $data['post']->type;
        if ($type === 'image' || $type === 'video') {
            $this->load->model('image');
            $data['image_list'] = $this->image->get_post_image_list($data['post']->id);
        }

        $this->load->view('templates/header', $this->header_data);
        $this->load->view('root/post', $data);
        $this->load->view('templates/footer');
    }
}