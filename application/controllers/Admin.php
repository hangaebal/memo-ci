<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        $method = $this->router->method;
        if ($method != 'login' && $method != 'login_proc' && $method != 'logout') {
            if (!$this->session->userdata('admin_login')) {
                redirect('admin/login');
            }
        }
    }


    public function index()
    {
        $this->load->view('templates/admin_header');
        $this->load->view('admin/index');
        $this->load->view('templates/admin_footer');
    }

    public function login()
    {
        $this->load->helper('form');
        $this->load->view('admin/login');
    }

    public function login_proc()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback__login_check');

        if ($this->form_validation->run() == TRUE) {
            $this->session->set_userdata('admin_login', 'admin');
            redirect('admin/');
        } else {
            $this->load->view('admin/login');
        }
    }

    public function _login_check($password)
    {
        $username = $this->input->post('username');
        log_message('error', 'Admin Login Check ===== ['.$username.']['.$password.']');

        if ($username == $this->config->item('USERNAME') && $password == $this->config->item('PASSWORD')) {
            return TRUE;
        }

        $this->form_validation->set_message('_login_check', '입력한 정보가 맞지 않습니다.');
        return FALSE;
    }

    public function logout()
    {
        $this->session->unset_userdata('admin_login');
        session_destroy();
        redirect('admin/');
    }

    public function menu()
    {
        $this->output->enable_profiler(TRUE);
        $this->load->model('menu');
        $data['menu_list'] = $this->menu->get_list();

        $this->load->helper('form');
        $this->load->view('templates/admin_header');
        $this->load->view('admin/menu', $data);
        $this->load->view('templates/admin_footer');
    }

    public function menu_save()
    {
        $ids = $this->input->post('id');
        $titles = $this->input->post('title');

        for ($i = 0; $i < sizeof($titles); $i++) {
            $menu = array('seq' => $i+1, 'title' => $titles[$i]);

            $this->load->model('menu');
            if (!empty($ids) && sizeof($ids) > $i) {
                // update
                $menu['id'] = $ids[$i];
                $this->menu->update($menu);
            } else {
                // insert
                $this->menu->insert($menu);
            }
        }

        redirect('admin/menu');
    }

    public function menu_delete($id)
    {
        $this->load->model('menu');
        $this->menu->delete($id);
        header('Content-Type: text/plain');
        echo "success";
    }




}