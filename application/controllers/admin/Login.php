<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

    }

    /*
     * 后台登陆
     */
    public function signIn()
    {
        if (!empty($this->input->post())) {
            $username = $this->input->post('username');
            $password = md5($this->input->post('password'));
            $data = array(
                'username' => $username,
                'password' => $password
            );
            $sign = $this->Common_Models->getDataOne('admin', 'id,username,login_time,login_ip,power,number', $data, false);

            if (!empty($sign)) {
                $data = array(
                    'login_ip' => real_ip(),
                    'login_time' => date('Y-m-d H:i:s'),
                    'number' => $sign['number'] + 1,
                );
                $this->Common_Models->updateData(array('username' => $username), 'admin', $data);
                $setData = array(
                    'username' => $sign['username'],
                    'power' => $sign['power'],
                );
                @$this->session->set_userdata($setData);
                jump('登录成功', base_url('Admin/index'));
            } else {
                jump('您的账号或密码错误！');
            }
        }

        $this->load->view('Admin/member/login');
    }

    /*
     * 登出后台
     */
    public function signOut()
    {
        $array_items = array('username', 'power');

        $unset = $this->session->unset_userdata($array_items);
        jump('亲，已退出，你需要重新登录哦！', base_url('Admin/Login/signIn'));
    }
}
