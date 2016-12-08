<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GrabData extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
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
                print json_encode($result);
            } else {
                $result['status'] = "error";
                print json_encode($result);
            }
        }
    }
}
