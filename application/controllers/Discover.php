<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discover extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->geohash = new Geohash();
    }

    /**
     * 发现
     * @param LikeEach
     * @param 2016/11/19
     * @param 14:12
     */
    public function index()
    {
        $lng = $this->input->post('lng', TRUE); //经度
        $lat = $this->input->post('lat', TRUE); //维度
        $sex = $this->input->post('sex', TRUE); //性别

        if (!empty($this->input->post())) {

            $where = "";
            if (!empty($sex)) {
                if ($sex == "女") {
                    $where[] = " WHERE us.sex = '男' AND ";
                } else {
                    $where[] = " WHERE us.sex = '女' AND ";
                }
            }
            //$where = join(' AND ',$where);

            $hash = $this->geohash->encode($lat, $lng);
            $prefix = substr($hash, 0, 4);//截取我的经纬度前面六位
            $neighbors = $this->geohash->neighbors($prefix);//取出相邻八个区域
            array_push($neighbors, $prefix);
            $data = array();

            foreach ($neighbors as $key => $val) {
                $sql = " SELECT us.sex,us.nickname,u.photo,us.lng,us.lat FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname {$where[0]}  latitude LIKE '{$val}%' AND us.status = '1' ";
                $check = $this->db->query($sql)->result_array();

                foreach ($check as &$value) {
                    $data[] = $value;
                }
            }
            if (!empty($data)) {
                print json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                $sql = " SELECT us.sex,us.nickname,u.photo,us.lng,us.lat FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname WHERE  us.status = '1'";
                $check = $this->db->query($sql)->result_array();
                print json_encode($check, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * 查询好友
     * @param QueryFriends
     * @param 2016/11/25
     * @param 17:41
     */
    public function QueryFriends()
    {
        $nickname = $this->input->post('nickname', TRUE);
        if (!empty($this->input->post())) {
            $check_info = $this->db->select('nickname,photo')->like('nickname', $nickname, 'both')->get('user')->result_array();
            if ($check_info) {
                print json_encode($check_info, JSON_UNESCAPED_UNICODE);
            }
        }

    }
}
