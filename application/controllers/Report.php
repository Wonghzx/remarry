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
            $add = $this->Common_Models->insertData('report',$data);
            if ($add) {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }
}
