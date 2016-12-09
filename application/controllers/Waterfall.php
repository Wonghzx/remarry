<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waterfall extends CI_Controller
{

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
                    $where = " WHERE us.sex = '女' ";
                } else {
                    $where = " WHERE  us.sex = '男' ";
                }
            }
            $check_friends = $this->db->select('tarname')
                ->get('friends')
                ->result_array();

            $sql = " SELECT us.nickname,u.photo,us.age,us.height,us.monologue,u.member FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname  {$where}";
            $check_info = $this->db->query($sql)->result_array();
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
                if($value['nickname'] == "红娘"){
                   unset($check_info[$item]);
                }
            }
            @array_multisort($row,SORT_DESC,$check_info);
            if (!empty($check_info)) {
                print json_encode($check_info, JSON_UNESCAPED_UNICODE);
            }
            else
            {
                $sql = " SELECT us.nickname,u.photo,us.age,us.height,us.monologue FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname ";
                $check_info = $this->db->query($sql)->result_array();
                foreach ($check_info as $item => $value) {
                    $data = array();
                    foreach ($check_friends as $it => $va) {
                        if ($value['nickname'] == $va['tarname']) {
                            $data[] = $va['tarname'];
                        }
                    }
                    $check_info[$item]['like'] = count($data);
                    if($value['nickname'] == "红娘"){
                        unset($check_info[$item]);
                    }
                }
                print json_encode($check_info, JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
