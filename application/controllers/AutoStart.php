<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AutoStart extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->row = new ServerAPI('bmdehs6pbqj2s', 'qmJhoSdpc95J');
    }

    public function Start()
    {
        define("TIME",time());
        $sql = " SELECT p.actid,a.starttime,p.nickname,u.userid FROM rem_participant AS p LEFT JOIN rem_activity AS a ON p.actid = a.id LEFT JOIN rem_user AS u ON p.nickname = u.nickname WHERE p.start = '1' ";
        $check = $this->db->query($sql)->result_array();

        $day = 3600 * 24 * 1;
        foreach ($check as $key => $value) {
            $t = strtotime($value['starttime']);

            $a = $t - 2400;

            if (TIME >= $a AND TIME <= $t) {
                $data = array(
                    'actid' => $value['actid'],
                    'userid' => json_encode($value['userid'])
                );
                if (is_array($data)) {
                    echo json_encode($data);
                    $this->db->where('nickname =', $value['nickname'])->update('participant', array('start' => "0"));
                }
            }
        }


    }
}
