<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    static $data = array();

    /**
     * Home constructor.
     * 构造方法
     */
    function __construct()
    {
        parent::__construct();
        $this->province = $this->input->post('province', TRUE);
        $this->age = $this->input->post('age', TRUE);
        $this->sex = $this->input->post('sex', TRUE);

    }

    /**
     * App首页
     * @param index
     * @param 2016/11/9
     * @param 10:00
     */
    public function index()
    {
        if (!empty($this->input->post())) {
            $where = "";

            if (!empty($this->age)) {
                $where[] = " us.age >= {$this->age}" . " AND us.age <= " . ($this->age + 18);
            }
            if (!empty($this->province)) {
                $where[] = " us.province = '{$this->province}' ";
            }
            if (!empty($this->sex)) {
                if ($this->sex == "男") {
                    $where[] = "us.sex = '女' ";
                } else {
                    $where[] = " us.sex = '男' ";
                }
            }
        }
        $rand = "";
        if (!empty($where)) {
            $where = join(' AND ', $where);
            $rand = " ORDER BY RAND() LIMIT  20 ";
        } else {
            $where = " RAND() LIMIT  20 ";
        }

        $sql = "SELECT us.nickname,u.photo,u.userid,us.age,us.sex,us.height,u.memtime FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname WHERE us.status = '1' AND {$where}   {$rand}  ";
        $check_info = $this->db->query($sql)->result_array();

        $check_photo = $this->db->select('nickname,photourl')
            ->get('useralbum')
            ->result_array();

        $check_friends = $this->db->select('tarname')
            ->get('friends')
            ->result_array();

        $unm = "";
        foreach ($check_info as $key => $item) {

            $arr = array();
            foreach ($check_photo as $ke => $value) {
                if ($item['nickname'] == $value['nickname']) {
                    $arr[] = $value['photourl'];
                }
            }
            $check_info[$key]['countphoto'] = count($arr);

            $row = array();
            foreach ($check_friends as $k => $v) {
                if ($item['nickname'] == $v['tarname']) {
                    $row[] = $v['tarname'];
                }

            }
            $check_info[$key]['like'] = count($row);
//            if ($item['nickname'] == "红娘") {
//                $unm = $key;
//            }
        }
//        unset($check_info[$unm]);
        if (!empty($check_info)) {
            echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            $check = $this->db->select('nickname,member,memtime')->where('member =', '1')->get('user')->result_array();
            foreach ($check as $item => $value) {
                if (time() > $value['memtime']) {
                    $this->db->where(array('memtime' => $value['memtime'], 'member' => '1'))->update('user', array('member' => "0"));
                }
            }
        } else {
//            $sql = "SELECT us.nickname,u.photo,u.userid,us.age,us.sex,us.height,u.memtime FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname WHERE RAND() LIMIT 0 , 20";
//            $check_info = $this->db->query($sql)->result_array();
//
//            $check_photo = $this->db->select('nickname,photourl')
//                ->get('useralbum')
//                ->result_array();
//
//            foreach ($check_info as $key => $item) {
//                if ($item['nickname'] == "红娘") {
//                    unset($check_info[$key]);
//                }
//                $arr = array();
//                foreach ($check_photo as $ke => $value) {
//                    if ($item['nickname'] == $value['nickname']) {
//                        $arr[] = $value['photourl'];
//                    }
//                }
//                $check_info[$key]['countphoto'] = count($arr);
//                $row = array();
//                foreach ($check_friends as $k => $v) {
//                    if ($item['nickname'] == $v['tarname']) {
//                        $row[] = $v['tarname'];
//                    }
//                }
//                $check_info[$key]['like'] = count($row);
//
//            }
//            print json_encode($check_info, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * App首页 按条件添加friend
     * @param AddFriend
     * @param 2016/11/9
     * @param 10:00
     */
    public function AddFriend()
    {
        $this->nickname = trim($this->input->post('nickname', TRUE));
        $this->tarname = trim($this->input->post('tarname', TRUE));

        $sql = " SELECT id,state FROM rem_friends WHERE nickname IN ('{$this->nickname}','{$this->tarname}') AND  tarname IN ('{$this->nickname}','{$this->tarname}') ";
        $check_f = $this->db->query($sql)->row_array();

        switch ($this->nickname) {
            case (empty($check_f));
                try {
                    $data = array(
                        'nickname' => $this->nickname,
                        'tarname' => $this->tarname,
                        'state' => "0",
                        'add_time' => time()
                    );
                    if (!in_array('', $data)) {
                        $AddData = $this->db->insert('friends', $data);
                        if ($AddData > 0) {
                            $result['status'] = "success";
                            echo json_encode($result);
                        } else {
                            $result['status'] = "error";
                            echo json_encode($result);
                        }
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                break;
            case ($check_f['state'] == "0");

                $arr = array(
                    'nickname' => $this->nickname,
                    'tarname' => $this->tarname
                );
                $check_user = $this->db->where($arr)
                    ->get('friends')
                    ->row_array();//查询是否有这条数据
                if ($check_user == "") {
                    $data = array(
                        'state' => "1"


                    );
                    $up_win = $this->db->where('id =', $check_f['id'])
                        ->update('friends', $data);
                    if ($up_win) {
                        $result['status'] = 'like';
                        echo json_encode($result);
                    }
                }
                break;
            case ($check_f['state'] == "1");
                $result['status'] = "您们已经是好友了！";
                break;
            default;
                echo "No number between 1 and 3";
                break;
        }
    }
}

