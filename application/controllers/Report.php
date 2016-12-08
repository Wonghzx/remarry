<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller
{


    public function index()
    {
        $nickname = $this->input->post('nickname', TRUE);
        $targetname = $this->input->post('targetnickname', TRUE);
        $content = $this->input->post('content', TRUE);
        if (!empty($this->input->post())) {
            $data = array(
                'nickname' => $nickname,
                'targetname' => $targetname,
                'content' => $content,
                'add_time' => time()
            );
            $add = $this->db->insert('report', $data);
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
