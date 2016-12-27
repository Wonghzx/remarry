<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Member.php";

class Test extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->tarname = '148075130997598';
        $this->nickname = "148109329079888";
//        $this->sex = $this->input->post('sex', TRUE);
        $this->load->model('Common_Models');
    }

    public function index()
    {
//        $sql = " SELECT id,sponsor,activitytitle,introduction,activitytype,poster,city,actposition,starttime,endtime,stoptime,lng,lat,state,add_time FROM rem_activity WHERE latitude LIKE '{$this->nickname}%' ORDER BY add_time DESC";
//        $check_info = $this->db->query($sql)->result_array();

        $check = $this->Common_Models->getDataAll('participant', 'nickname,actid',array('nickname'=> 1));
        p($check);
    }

}
