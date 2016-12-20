<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GrabData extends CI_Controller
{
    public $time;

    function __construct()
    {
        parent::__construct();
        $this->time = time();
    }

    /**
     * 抓取用户习惯
     * @param GrabData
     * @param 2016/12/5
     * @param 17:40
     */
    public function index()
    {

        $data_json = $this->input->post('data_json', TRUE);
        $json = json_decode($data_json, TRUE);
        if (!empty($this->input->post())) {
            $add = $this->db->where('nickname =', $json['nickname'])->update('grabdata', array('data_json' => $data_json));
            if ($add) {
                $result['status'] = "success";
                echo json_encode($result);
                $time = $this->db->select('add_time')->where('nickname =', $json['nickname'])->get('grade')->row_array();
                if (date('j') == date('j', $time['add_time']) || date('j') > date('j', $time['add_time'])) {
                    $this->db->where('nickname =', $json['nickname'])->update('grade', array('signout_time' => $this->time));
                }
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }
}
