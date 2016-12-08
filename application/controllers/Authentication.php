<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 身份认证
     * Certifying
     * @param 2016/11/25
     * @param 9:35
     */
    public function Certifying()
    {
        $nickname = $this->input->post('nickname', TRUE);
        $photo = $this->input->post('photo', TRUE);
        if (!empty($this->input->post())) {
            $data = array(
                'nickname' => $nickname,
                'photo' => $photo,
                'add_time' => time()
            );
            $add = $this->db->insert('authentication', $data);
            if ($add) {
                $result['status'] = "success";
                print json_encode($result);
                $this->db->where('nickname',$nickname)->update('user',array('autstate'=>"2"));
            } else {
                $result['status'] = "error";
                print json_encode($result);
            }
        }

    }
}
