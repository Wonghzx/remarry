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
        $this->load->model('Grade_Models');
        $a = $this->Grade_Models->gradeQuery('George.W');
        p($a);

    }

}
