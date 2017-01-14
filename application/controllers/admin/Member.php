<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{
    private $i = 1;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Member_Models');
    }

    /*
     *加载用户列表
     */
    public function MemberList()
    {
        $this->output->cache(3);
        $urlPn = $this->uri->segment(4, 0);
        $pn = $this->uri->segment(5, 1);

        $total = $this->Common_Models->getCount(array('status' => '1'), 'userdata');
        //分页类

        $config['base_url'] = site_url('admin/Member/MemberList/' . $urlPn);
        $config['total_rows'] = $total;  //总记录数
        $config['per_page'] = 10;  //每页显示多少条记录
        $config['uri_segment'] = 5;  //分页页码在url的第几段
        $config['use_page_numbers'] = TRUE;    //url显示的是页码
        $config['cur_tag_open'] = '<li class="active"><a href="#" id="cla">'; //当前页码
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';    //链接页码
        $config['num_tag_close'] = '</li>';
        $config['first_link'] = '第一页';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '上一页';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '下一页';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = '最后一页';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['full_tag_open'] = '<div class="page"><ul>';    //整个分页最外层的标签
        $config['full_tag_close'] = '</ul></div>';
        $this->load->library('pagination');
        $limit = $config['per_page'];
        $this->pagination->initialize($config); //加载配置
        $pageInfo = $this->pagination->create_links();//分页效果
        $start = ($pn - 1) * $config['per_page'];

        $userInfo = $this->Member_Models->getUserAll($start, $limit);
        foreach ($userInfo as $item => $value) {
            $status = $this->queryStatus($value['userid']);
            $ste = json_decode($status, TRUE);
            $userInfo[$item]['status'] = $ste['status'];
        }
        $data = [
            'i' => $this->i,
            'userInfo' => $userInfo,
            'pageInfo' => $pageInfo,
            'countUserInfo' => $total,
        ];

        $this->load->view('Admin/member/member-list', $data);
    }

    /*
     * 用户名片
     */
    public function visitingCardUser()
    {
        $nickname = $this->input->get('nickname');

        $userInfo = $this->Member_Models->getUserInfo($nickname);
        $data = [
            'userInfo' => $userInfo
        ];
        $this->load->view('Admin/member/member-show', $data);
    }

    /*
     * 刷新会员是否在线
     */
    public function queryStatus($userId = null)
    {
        $server = new ServerAPI('bmdehs6pbqj2s', 'qmJhoSdpc95J');
        if ($userId == null) {
            if (!empty($this->input->post())) {

                $obj = $this->input->post('obj');

                $checkOnLine = $server->userCheckOnline($obj);
                exit($checkOnLine);
            }
        } else {
            $checkOnLine = $server->userCheckOnline($userId);
            return $checkOnLine;
        }

    }

}