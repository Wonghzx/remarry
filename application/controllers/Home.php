<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    static $data = array();

    private $province;

    private $age;

    private $sex;

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
        $this->load->model('Home_Models');

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

        //查出首页数据 第一个参数为false 为有筛选条件
        $check_info = $this->Home_Models->queryHomeInfo(true, $where, $rand);

        $check_photo = $this->Common_Models->getDataAll('useralbum', 'nickname,photourl');

        $check_friends = $this->Common_Models->getDataAll('friends', 'tarname');

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
        }
        if (!empty($check_info)) {
            echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            $check = $this->Common_Models->getDataAll('user', 'nickname,member,memtime', array('member' => '1'));

            foreach ($check as $item => $value) {
                if (time() > $value['memtime']) {
                    $this->Common_Models->updateData(array('memtime' => $value['memtime']), 'user', array('member' => "0"));
                }
            }
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
        $nickname = trim($this->input->post('nickname', TRUE));
        $tarName = trim($this->input->post('tarname', TRUE));

        $check_f = $this->Home_Models->addFriend($nickname, $tarName);
        switch ($nickname) {
            case (empty($check_f));
                try {
                    $data = array(
                        'nickname' => $nickname,
                        'tarname' => $tarName,
                        'state' => "0",
                        'add_time' => time()
                    );
                    if (!in_array('', $data)) {
                        $AddData = $this->Common_Models->insertData('friends', $data);
                        if ($AddData == "success") {
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
            case ($check_f['state'] == "0");//如果有数据state为0的

                //一下查询为判断用户多次点击喜欢  防止单方面的点击喜欢
                $arr = array(
                    'nickname' => $nickname,//点击的喜欢用户
                    'tarname' => $tarName   //被点击喜欢的用户
                );
                $check_user = $this->Home_Models->queryFriendsTrue($arr);

                //如果为空 双方用户达成互相喜欢成为好友
                if ($check_user == "") {
                    $data = array(
                        'state' => "1"
                    );
                    $up_win = $this->Common_Models->updateData(array('id' => $check_f['id']), 'friends', $data);
                    if ($up_win == "success") {
                        $result['status'] = 'like';
                        echo json_encode($result);

                        $this->Common_Models->updateGrade();//成为有添加等级检验
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

