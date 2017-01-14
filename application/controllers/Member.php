<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{

    public $time;

    public $username;

    public $nickname;

    protected $password;

    function __construct()
    {
        parent::__construct();
        $this->username = trim($this->input->post('username', TRUE));
        $this->password = md5(trim($this->input->post('password', TRUE)));
        $this->nickname = trim($this->input->post('nickname', TRUE));
        $this->tarname = $this->input->post('tarname', TRUE);
        $this->time = time();
        $this->geohash = new Geohash;
        $this->row = new ServerAPI('bmdehs6pbqj2s', 'qmJhoSdpc95J');
        $this->load->model('Member_Models');

    }

    /**
     * 用户注册
     * @param register
     * @param 2016/11/6
     * @param 16:42
     */
    public function Register()
    {
        $listAlpha = '1234567891098765432187987979879875456498789798';
        $length = 5;
        $new_str = substr(str_shuffle($listAlpha), 0, $length); //打乱字符串

        if (!empty($this->input->post())) {

            //查询用户是否存在
            $check_username = $this->Common_Models->getDataOne('user', 'username', array('username' => $this->username));

            //查询用户是否存在
            $check_nickname = $this->Common_Models->getDataOne('user', 'nickname', array('nickname' => $this->nickname));

            switch ($this->nickname) {
                case(!empty($check_username['username']));
                    $result['status'] = "phone";
                    echo json_encode($result);
                    break;
                case (!empty($check_nickname['nickname']));
                    $result['status'] = "nickname";
                    echo json_encode($result);
                    break;
                case (empty($check_username['username']) AND empty($check_nickname['nickname']));
                    $to = $this->row->getToken($this->time . $new_str, $this->nickname, "http://119.29.143.48/moren/moren.jpg");
                    $obj = json_decode($to);
                    if ($obj->code != 200) {
                        $result['status'] = "error3";
                        echo json_encode($result);
                    } else {
                        $token = $obj->token;
                        $Seven = 3600 * 24 * 3;  //5天会员
                        $data = array(
                            'username' => $this->username,
                            'password' => $this->password,
                            'nickname' => $this->nickname,
                            'userid' => $this->time . $new_str,
                            'photo' => "http://119.29.143.48/moren/moren.jpg",
                            'member' => "1", //默认1   送3天会员
                            'memtime' => $Seven + $this->time,
                            'token' => $token,
                            'autstate' => "0",
                            'login_time' => $this->time,
                            'add_time' => $this->time
                        );

                        $add_user = $this->Common_Models->insertData('user', $data);
                        if ($add_user == "success") {
                            $result['status'] = "success";
                            echo json_encode($result);


                            //用户注册成功之后再添加个人信息资料
                            $check_user_data = $this->Common_Models->getDataOne('user', 'username,nickname', array('nickname' => $this->nickname));
                            $dat = array(
                                'nickname' => $check_user_data['nickname'],
                                'username' => $check_user_data['username'],
                                'sex' => $this->input->post('sex', TRUE),
                                'birthday' => $this->input->post('birthday', TRUE),
                                'age' => floor(date('Y.m.d') - $this->input->post('birthday', TRUE)),
                                'status' => '1'
                            );
                            $this->Common_Models->insertData('userdata', $dat);

                            $this->Common_Models->insertData('mymates', array('nickname' => $check_user_data['nickname']));

                            $this->Common_Models->insertData('grabdata', array('nickname' => $check_user_data['nickname']));

                            $this->Common_Models->insertData('grade', array('nickname' => $check_user_data['nickname'], 'add_time' => $this->time, 'status' => $this->time));
                        } else {
                            $result['status'] = "error1";
                            echo json_encode($result);
                        }
                    }
                    break;
                default;
                    break;
            }
        }

    }

    /**
     * 用户登录
     * @param Login
     * @param 2016/11/6
     * @param 17:00
     */
    public function Login()
    {

        $check_user = $this->Member_Models->signToLove($this->nickname, $this->password);

        if (!empty($check_user)) {
            if (is_numeric($this->nickname)) {
                $where = "username";
            } else {
                $where = "nickname";
            }
            $this->Common_Models->updateData(array('nickname' => $check_user['nickname']), 'grade', array('online_time' => $this->time));
            $data = array(
                'province' => trim($this->input->post('province', TRUE)),
                'lng' => $this->input->post('lng', TRUE),//更新经度
                'lat' => $this->input->post('lat', TRUE),//更新维度
                'latitude' => $this->geohash->encode($this->input->post('lat', TRUE), $this->input->post('lng', TRUE)), //把经纬度更新转码
                'nowlocal' => $this->input->post('nowlocal', TRUE)
            );

            $this->Common_Models->updateData(array($where => $this->nickname), 'userdata', $data);//登录时更新用户经纬度

            $add_check = $this->Member_Models->returnData($where, $this->nickname);//查询用户资料返回给客户
            //↓判断当前时间是否大于用户会员时间
            if (time() >= $check_user['memtime']) {
                $this->Common_Models->updateData(array($where => $this->nickname), 'user', array('member' => "0"));
            }

            //查询小号
            $sex = "";
            if ($add_check['sex'] == "女") {
                $sex = "男";
            } else {
                $sex = "女";
            }
            $local = $this->Common_Models->reserveUser($sex);
            if ($check_user['login_time'] <= time()) {
                //----------------↓好友主动推送↓--------------//
                $row = $this->MyChat($check_user['userid']);

                $check = $this->Member_Models->queryPushUser($add_check['age']);
                $data = array();
                foreach ($check as $item => $value) {
                    $l = in_array($sex, $value, TRUE);
                    if ($l) {
                        $data[] = $value;
                    }
                }
                $num = "";
                if (!empty($data)) {
                    $x = @array_rand($data, 1);
                    if ($data[$x]['userid'] != $row['nickname'] || $data[$x]['userid'] != $row['targetname']) {
                        $num = $data[$x]['userid'];
                    }
                }
                //------------------------------------------//
                $this->Common_Models->updateData(array($where => $this->nickname), 'user', array('login_time' => 3600 * 24 * 1 + time()));  //更新推送信息时间
            }
            $result = [
                'status'      => 'success',
                'membergrade' => $add_check['membergrade'],
                'grade'       => $add_check['grade'],
                'id'          => $check_user['id'],
                'username'    => $check_user['username'],
                'userid'      => $check_user['userid'],
                'autstate'    => $check_user['autstate'],
                'age'         => $add_check['age'],
                'sex'         => $add_check['sex'],
                'province'    => $add_check['province'],
                'nickname'    => $check_user['nickname'],
                'photo'       => $check_user['photo'],
                'member'      => $check_user['member'],
                'memtime'     => isset($check_user['memtime']) ? $check_user['memtime'] : 0,
                'token'       => $check_user['token'],
                'add_time'    => $check_user['add_time'],
                'pushid'      => isset($num) ? $num : NULL,
                'data_json'   => isset($add_check['data_json']) ? $add_check['data_json'] : NULL,
                'trumpet'     => json_encode($local, JSON_UNESCAPED_UNICODE),

            ];

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            $result['status'] = "error";
            echo json_encode($result);
        }
    }

    /**
     * 查出单个用户信息
     * @param GetUserOne
     * @param 2016/11/6
     * @param 17:00
     */
    public function GetUserOne()
    {
        try {
            $member = isset($this->tarname) ? $this->tarname : $this->nickname;//用户名

            //查询我们是不是好友，是好友就可以聊天!
            $check_f = $this->Common_Models->checkedFriends($this->nickname, $this->tarname);
            $state = isset($check_f['state']) ? $check_f['state'] : "0";


            //查询是否我点击过的喜欢的好友
            $like = $this->Common_Models->clickLike($this->nickname, $this->tarname);
            if (!empty($like)) {
                $luck = "1";
            } else {
                $luck = "0";
            }


            $check_info = $this->Member_Models->getUserInfo($member);

            $age = date('Y.m.d') - $check_info['birthday'];

            $PhotoUrl = $this->Common_Models->getDataAll('useralbum', 'photourl', array('nickname' => $member));

            $photourl = array_column($PhotoUrl, 'photourl');


            $sql = "age,height,income,education,weight,marriage,shape,working,child,kid,alcohol";
            $myMates = $this->Common_Models->getDataOne('mymates', $sql, array('nickname' => $this->nickname));

            $check_info['age'] = floor($age);
            $check_info['state'] = (string)$state;
            $check_info['like'] = (string)$luck;
            $check_info['photourl'] = $photourl;
            $check_info['mymates'] = $myMates;
            if ($check_info) {
                echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * 查出我喜欢的人
     * @param MyLike
     * @param 2016/11/9
     * @param 15:17
     */
    public function MyLike()
    {
        try {
            if (empty($this->nickname))
                throw new Exception('亲！昵称不能为空哦..');

            if (!empty($this->input->post())) {
                $check_like = $this->Member_Models->queryLike($this->nickname);
                if (isset($check_like)) {
                    echo json_encode($check_like, JSON_UNESCAPED_UNICODE);
                } else {
                    $result = "error";
                    echo $result;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * 查出喜欢我的人
     * @param LikeMy
     * @param 2016/11/9
     * @param 16:03
     */
    public function LikeMy()
    {
        try {
            if (empty($this->nickname))
                throw new Exception('亲！昵称不能为空哦..');
            if (!empty($this->input->post())) {
                $check_like = $this->Member_Models->likeMy($this->nickname);
                if ($check_like) {
                    echo json_encode($check_like, JSON_UNESCAPED_UNICODE);
                } else {
                    $result = "error";
                    echo $result;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }


    /**
     * 查出我的好友
     * @param LikeEach
     * @param 2016/11/9
     * @param 16:03
     */
    public function LikeEach()
    {

        try {
            if (empty($this->nickname))
                throw new Exception('亲！昵称不能为空哦..');

            if (!empty($this->input->post())) {

                $show_like = $this->Member_Models->likeEach('tarName', $this->nickname);//tarName 为数据的tarName的好友名称

                $show_likes = $this->Member_Models->likeEach(false, $this->nickname);  // 为数据的nickname的好友名称

                foreach ($show_likes as $key => $value) {
                    $show_likes[$key]['nickname'] = $value['tarname'];
                }
                $like = array_merge_recursive($show_like, $show_likes);
                if (is_array($like)) {
                    echo json_encode($like, JSON_UNESCAPED_UNICODE);

                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }

    /**
     * 删除我喜欢的人
     * @param DelMyLike
     * @param 2016/11/10
     * @param 11:10
     */

    public function DelMyLike()
    {
        $id = $this->input->get('id', TRUE);
        if ($this->input->get()) {
            $data = array(
                'id' => intval($id)
            );
            $update = $this->Common_Modesl->deleteData('friends', $data);
            if ($update == "success") {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }


    /**
     * 删除好友
     * @param DelLikeEach
     * @param 2016/11/10
     * @param 11:10
     */
    public function DelLikeEach()
    {
        $id = $this->input->get('id', TRUE);
        if ($this->input->get()) {
            $data = array(
                'id' => intval($id)
            );
            $update = $this->Common_Modesl->deleteData('friends', $data);
            if ($update == "success") {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }

    /**
     * 我的生活圈
     * @param MyCircle
     * @param 2016/11/18
     * @param 14:26
     */
    public function MyCircle()
    {
        $page = $this->input->post('page', TRUE);
        $limit = 10;
        $num = ($page - 1) * $limit;
        if (!empty($this->input->post())) {

            $check_info = $this->Member_Models->queryMyCircle($num, $limit, $this->nickname);

            //调用生活圈的models加载我的信息
            $this->load->model('Circle_Models');
            $check_comment = $this->Circle_Models->queryCircleComment();//评论信息

            $check_reply = $this->Circle_Models->queryCircleReply();//评论信息

            $check_like = $this->Circle_Models->queryCircleLike();//点赞


            foreach ($check_comment as $item => $value) {
                $row = array();
                foreach ($check_reply as $it => $va) {
                    if ($value['id'] == $va['commentid']) {
                        $row[] = $va;
                    }
                }
                $check_comment[$item]['reply'] = $row;
            }

            foreach ($check_info as $item => $value) {
                $data = array();
                foreach ($check_like as $it => $va) {
                    if ($value['id'] == $va['circleid']) {
                        $data[] = $va['nickname'];
                    }
                }
                $check_info[$item]['like'] = $data;
                $row = array();
                foreach ($check_comment as $i => $v) {
                    if ($value['id'] == $v['circleid']) {
                        $row[] = $v;
                    }
                }
                $check_info[$item]['comment'] = $row;
            }
            if (!empty($check_info)) {
                echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array('status' => 'You have no data'), JSON_UNESCAPED_UNICODE);
            }

        }

    }

    /**
     * 我创建的活动
     * @param MyActivity
     * @param 2016/11/18
     * @param 14:42
     */
    public function MyActivity()
    {
        if (!empty($this->input->post())) {

            $check_m = $this->Common_Models->getDataAll('participant', 'nickname,actid');//活动参与人

            $check_info = $this->Member_Models->myActivity($this->nickname);//活动信息
            foreach ($check_info as $items => $val) {
                $arr = array();
                foreach ($check_m as $value => $item) {
                    if ($val['id'] == $item['actid']) {
                        $arr[] = $item['actid'];
                    }
                }
                $check_info[$items]['countpeople'] = (string)count($arr);
            }

            if (!empty($check_info)) {
                echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array('status' => 'You have no data'), JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * 我参与的活动
     * @param ParticipateActivity
     * @param 2016/11/18
     * @param 15:31
     */
    public function ParticipateActivity()
    {
        if (!empty($this->input->post())) {

            $check = $this->Common_Models->getDataAll('participant', 'nickname,actid', array('nickname' => $this->nickname));//活动参与人
            foreach ($check as &$item) {

                $check_info = $this->Member_Models->participateActivity($item['actid']);
                foreach ($check_info as $value => $va) {
                    $arr = array();
                    if ($va['id'] == $item['actid']) {
                        $arr[] = $item['nickname'];
                    }
                    $check_info[$value]['asd'] = (string)count($arr);
                }
                if (!empty($check_info)) {
                    echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array('status' => 'You have no data'), JSON_UNESCAPED_UNICODE);
                }
            }

        }
    }

    /**
     * 通过id查出信息
     * @param MyUser
     * @param 2016/11/22
     * @param 14:50
     */
    public function MyId()
    {
        $id = $this->input->post('id', TRUE);
        $username = $this->input->post('username', TRUE);

        if (!empty($this->input->post())) {

            $where = "";
            switch (!empty($this->input->post())) {
                case (!empty($id));
                    $where = "userid";
                    break;
                case (!empty($username));
                    $where = "username";
                    break;
                default;
                    echo 'code to be executed';
            }

            $check = $this->Common_Models->getDataOne('user', 'username,nickname,photo', array($where => isset($id) ? $id : $username));
            if ($check) {
                echo json_encode($check, JSON_UNESCAPED_UNICODE);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }

    /**
     * 添加谁看过我
     * @param AddReadMy
     * @param 2016/12/1
     * @param 9:20
     */
    public function AddReadMy()
    {
        $targetphoto = $this->input->post('targetphoto', TRUE);
        try {
            if (empty($this->nickname))
                throw new Exception;//昵称不能为空
            if (empty($this->tarname))
                throw new Exception;//target不能为空

            $check = array(
                'nickname' => $this->nickname,
                'targetname' => $this->tarname
            );

            $check_info = $this->Common_Models->getDataOne('readmy', '0', $check, false);
            if (empty($check_info)) {
                $data = array(
                    'nickname' => $this->nickname,
                    'targetname' => $this->tarname,
                    'targetphoto' => $targetphoto,
                    'add_time' => time(),
                );
                $add = $this->Common_Models->insertData('readmy', $data);
                if ($add == "success") {
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
    }

    /**
     * 获取谁看过我
     * @param ReadMy
     * @param 2016/12/1
     * @param 10:09
     */
    public function ReadMy()
    {
        if (!empty($this->input->post())) {

            $check = $this->Common_Models->getDataAll('readmy', 'nickname,targetphoto,add_time', array('targetname' => $this->nickname));
            if ($check) {
                echo json_encode($check, JSON_UNESCAPED_UNICODE);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }

    /**
     * 聊天信息
     * @param MyChat
     * @param 2016/12/7
     * @param 16:34
     */
    protected function MyChat($unm)
    {

        $check_f = $this->Member_Models->queryMyChat($unm);  //return nickname,targetName
        if (!empty($check_f)) {
            return $check_f;
        } else {
            return false;
        }
    }

}
