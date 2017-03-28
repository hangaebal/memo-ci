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
        $this->output->enable_profiler(TRUE);   // 개발 후 제거

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

        if ($this->form_validation->run() === TRUE) {
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

        if ($username === $this->config->item('USERNAME') && $password === $this->config->item('PASSWORD')) {
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

    /**
     * 메뉴관리
     */

    public function menu()
    {
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

        $this->output->set_content_type('text/plain')
            ->set_output('success');
    }


    /**
     * 포스트 관리
     */

    public function post()
    {
        $this->output->enable_profiler(TRUE);   // 개발 후 제거

        $menu_id = $this->input->get('menuId');

        $this->load->model('menu');
        $this->load->model('post');
        $data['menu_list'] = $this->menu->get_list();
        $data['post_list'] = $this->post->get_list_by_menu($menu_id);

        $this->load->helper('form');
        $this->load->view('templates/admin_header');
        $this->load->view('admin/post/list', $data);
        $this->load->view('templates/admin_footer');
    }

    public function post_seq()
    {
        $ids = $this->input->post('id');
        for ($i = 0; $i < sizeof($ids); $i++) {
            $post = array('seq' => $i+1, 'id' => $ids[$i]);

            $this->load->model('post');
            $this->post->update_seq($post);
        }

        $this->output->set_content_type('text/plain')
            ->set_output('success');
    }

    public function post_create_view()
    {
        $this->load->model('menu');
        $data['menu_list'] = $this->menu->get_list();

        $this->load->helper('form');
        $this->load->view('templates/admin_header');
        $this->load->view('admin/post/create', $data);
        $this->load->view('templates/admin_footer');
    }

    public function post_create()
    {
        $post['menu_id'] = $this->input->post('menuId');
        $post['type'] = $this->input->post('type');
        $post['title'] = $this->input->post('title');
        $post['year'] = $this->input->post('year');
        $post['contents'] = $this->input->post('contents');

        $this->load->model('post');
        $this->post->insert($post);

        $post_id = $this->db->insert_id();
        $imgIds = $this->input->post('imgId');
        if (isset($imgIds)) {
            $this->load->model('image');
            $seq = 1;
            foreach ($imgIds as $imgId) {
                $image = array('id' => $imgId, 'seq' => $seq, 'post_id' => $post_id);
                $this->image->update($image);
                $seq++;
            }
        }

        redirect('admin/post?menuId='.$this->input->post('menuId'));
    }

    public function post_delete($id)
    {
        $this->load->model('image');
        $this->load->model('post');

        $post = $this->post->get($id);
        $this->image->delete_post_image($id);
        $this->post->delete($id);

        $this->output->set_content_type('text/plain')
            ->set_output($this->config->site_url('admin/post?menuId='.$post->menu_id));
    }

    public function post_edit_view($id)
    {
        $this->load->model('post');
        $this->load->model('menu');
        $data['post'] = $this->post->get($id);
        $data['menu_list'] = $this->menu->get_list();

        if (empty($data['post'])) {
            show_404();
        }

        $type = $data['post']->type;
        if ($type === 'image' || $type === 'video') {
            $this->load->model('image');
            $data['image_list'] = $this->image->get_post_image_list($id);
        }

        $this->load->helper('form');
        $this->load->view('templates/admin_header');
        $this->load->view('admin/post/edit', $data);
        $this->load->view('templates/admin_footer');
    }



    /**
     * 이미지 관련
     */

    public function post_image_upload()
    {
        $type = $this->input->post('type');
        $title = $this->input->post('imgTitle');

        $this->load->library('upload');
        $this->upload->set_upload_path('./upload/'.$type);

        if ( ! $this->upload->do_upload('mFile')) {
            $rtn['status'] = 'error';
            $rtn['errMsg'] = $this->upload->display_errors();

            $this->output->set_content_type('application/json', 'utf-8')
                ->set_status_header(500)
                ->set_output(json_encode($rtn));
        } else {
            // 이미지 테이블 INSERT
            $path = $type.'/'.$this->upload->data('file_name');
            $image['title'] = $title;
            $image['path'] = $path;
            $this->load->model('image');
            $this->image->insert($image);
            $image_id = $this->db->insert_id();

            // 리턴 데이터
            $rtn['status'] = 'success';
            $rtn['id'] = $image_id;
            $rtn['path'] = $path;
            if ($type === 'image') {
                $rtn['thumbPath'] = $path;
                $rtn['title'] = $title;
            }

            // ================= 썸네일 적용 후 제거
            $rtn['data'] = $this->upload->data();
            // ================= 썸네일 적용 후 제거


            $this->output->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($rtn));
        }
    }

    public function post_image_delete($id)
    {
        $this->load->model('image');
        $this->image->delete($id);

        $this->output->set_content_type('text/plain')
            ->set_output('success');
    }






}