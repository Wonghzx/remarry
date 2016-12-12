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
    }

    public function index()
    {


        $sql = " SELECT u.member,u.nickname,us.age,us.sex,u.userid FROM rem_user AS u LEFT JOIN rem_userdata AS us ON u.nickname = us.nickname WHERE member = '1' AND age >= 20 AND age <= 20+10";
        $check = $this->db->query($sql)->result_array();

        $unm = "";
        foreach ($check as $item => $value) {
            if ($value['nickname'] == "红娘") {
                $unm =  $item;
            }
        }
        unset($check[$unm]);
        p($check);
//        echo json_encode($check, JSON_UNESCAPED_UNICODE);
    }

}
