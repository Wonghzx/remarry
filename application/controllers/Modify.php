<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modify extends CI_Controller
{

    public function index()
    {
        $nickname = $this->input->post('nickname', TRUE);
        $password = $this->input->post('password', TRUE);
        if (!empty($this->input->post())) {
            $data = array(
                'password' => $password
            );
            $update = $this->db->where('nickname =', $nickname)->update('user', $data);
            if ($update) {
                $result['status'] = "success";
                print json_encode($result);
            } else {
                $result['status'] = "error";
                print json_encode($result);
            }
        }
    }
}
