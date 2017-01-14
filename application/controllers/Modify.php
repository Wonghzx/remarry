<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modify extends CI_Controller
{

    /*
     * 修改密码
     */
    public function index()
    {
        $nickname = $this->input->post('nickname', TRUE);
        $password = md5($this->input->post('password', TRUE));
        if (!empty($this->input->post())) {
            $data = array(
                'password' => $password
            );

            $update = $this->Common_Models->updateData(array('nickname' => $nickname), 'user', $data);
            if ($update) {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }
}
