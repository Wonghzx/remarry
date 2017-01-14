<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller
{

    private $result = [];

    function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        if ($this->session->userdata('username') == NULL) {
            jump('亲，您需要登录哦！', base_url('Admin/Login/signIn'));
        }
        $data = array();
        $this->load->view('admin/index', $data);
    }


    public function welcome()
    {



        $data = array(
            'username' => $this->session->userdata('username'),
        );

        $sign = $this->Common_Models->getDataOne('admin', 'id,username,login_time,login_ip,power,number', $data, false);

        $data = array(
            'login_time' => $sign['login_time'],
            'login_ip' => $sign['login_ip'],
            'number' => $sign['number'],
        );
        $this->load->view('admin/welcome', $data);
    }

    /*
     * 信息统计
     */
    public function messageStatistics()
    {

        $checkCount = $_REQUEST;

        if (!empty($checkCount)) {
            $this->result['countUser'] = $this->Common_Models->getCount(array('status' => $checkCount['status']), 'userdata');
            $this->result['countMember'] = $this->Common_Models->getCount(array('member' => $checkCount['member']), 'user') - 19;
            $this->result['countActivity'] = $this->Common_Models->getCount(0, 'activity', true);
            $this->result['countFeedback'] = $this->Common_Models->getCount(0, 'feedback', true);
            $this->result['countAdmin'] = $this->Common_Models->getCount(0, 'admin', true);
            echo json_encode($this->result);
            exit;
        }

    }


}
