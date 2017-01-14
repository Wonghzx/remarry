<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waterfall extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Waterfall_Models');
    }

    /**
     * 瀑布流
     * @param index
     * @param 2016/11/18
     * @param 9:46
     */
    public function index()
    {
        $sex = $this->input->post('sex', TRUE); //性别
        if (!empty($this->input->post())) {
            $where = "";
            if (!empty($sex)) {
                if ($sex == "男") {
                    $where = " AND us.sex = '女' ";
                } else {
                    $where = " AND us.sex = '男' ";
                }
            }

            $check_friends = $this->Common_Models->getDataAll('friends', 'tarname');//


            $check_info = $this->Waterfall_Models->getUserALl($where);
            $row = array();
            foreach ($check_info as $item => $value) {

                $data = array();
                foreach ($check_friends as $it => $va) {
                    if ($value['nickname'] == $va['tarname']) {
                        $data[] = $va['tarname'];
                    }
                }
                $check_info[$item]['like'] = count($data);
                $row[] = $value['member'];

            }
            @array_multisort($row, SORT_DESC, $check_info);
            if (!empty($check_info)) {
                echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
