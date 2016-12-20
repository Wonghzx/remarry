<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AutoStart extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
//        $this->row = new ServerAPI('bmdehs6pbqj2s', 'qmJhoSdpc95J');
    }

    public function Start()
    {
        $sql = " SELECT u.member,u.nickname,us.age,us.sex,u.userid FROM rem_user AS u LEFT JOIN rem_userdata AS us ON u.nickname = us.nickname WHERE member = '1' AND age >= 20 AND age <= 20+10";
        $check = $this->db->query($sql)->result_array();
        p($check);
    }
}
