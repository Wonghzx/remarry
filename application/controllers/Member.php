<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->username = trim($this->input->post('username', TRUE));
        $this->password = trim($this->input->post('password', TRUE));
        $this->nickname = trim($this->input->post('nickname', TRUE));
        $this->tarname = $this->input->post('tarname', TRUE);
//        $this->token = trim($this->input->post('token', TRUE));
        $this->userid = time();
        $this->geohash = new Geohash;
        $this->row = new ServerAPI('bmdehs6pbqj2s', 'qmJhoSdpc95J');
        session_start();

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

//        @file_put_contents('./log.log', $this->userid . $new_str, FILE_APPEND);
        if (!empty($this->input->post())) {

            $check_username = $this->db->select('username')->where(array('username' => $this->username))->get('user')->row_array(); //查询用户是否存在
            $check_nickname = $this->db->select('nickname')->where(array('nickname' => $this->nickname))->get('user')->row_array(); //查询用户是否存在
            switch ($this->nickname) {
                case(!empty($check_username['username']));
                    $result['status'] = "phone";
                    print json_encode($result);
                    break;
                case (!empty($check_nickname['nickname']));
                    $result['status'] = "nickname";
                    print json_encode($result);
                    break;
                case (empty($check_username['username']) AND empty($check_nickname['nickname']));
                    //  if (empty($check_user)) {  //不存在等于空执行以下代码
                    $to = $this->row->getToken($this->userid . $new_str, $this->nickname, "http://119.29.143.48/moren/moren.png");
                    $obj = json_decode($to);
                    if ($obj->code != 200) {
                        $result['status'] = "error3";
                        print json_encode($result);
                    } else {
                        $token = $obj->token;
                        $Seven = 3600 * 24 * 3;  //5天会员
                        $data = array(
                            'username' => $this->username,
                            'password' => $this->password,
                            'nickname' => $this->nickname,
                            'userid' => $this->userid . $new_str,
                            'photo' => "http://119.29.143.48/moren/moren.png",
                            'member' => "1", //默认1   送七天会员
                            'memtime' => $Seven + time(),
                            'token' => $token,
                            'autstate' => "0",
                            'add_time' => time()
                        );
                        $add_user = $this->db->insert('user', $data);
                        if ($add_user > 0) {
                            $result['status'] = "success";
                            print json_encode($result);
                            //用户注册成功之后再添加个人信息资料
                            $check_user_data = $this->db->select('username,nickname')
                                ->where('nickname', $this->nickname)
                                ->get('user')
                                ->row_array();
                            $dat = array(
                                'nickname' => $check_user_data['nickname'],
                                'username' => $check_user_data['username'],
                                'sex' => $this->input->post('sex', TRUE),
                                'birthday' => $this->input->post('birthday', TRUE),
                                'age' => floor(date('Y.m.d') - $this->input->post('birthday', TRUE))
                            );
                            $this->db->insert('userdata', $dat);
                            $this->db->insert('mymates', array('nickname' => $check_user_data['nickname']));
                            $this->db->insert('grabdata', array('nickname' => $check_user_data['nickname']));
                        } else {
                            $result['status'] = "error1";
                            print json_encode($result);
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

        $sql = " SELECT id,username,nickname,photo,userid,member,memtime,token,autstate,add_time FROM rem_user WHERE  (nickname = '{$this->nickname}' OR username = '{$this->nickname}') AND password = '{$this->password}' ";
        $check_user = $this->db->query($sql)->row_array();

        if (!empty($check_user)) {
            if (is_numeric($this->nickname)) {
                $where = "username";
            } else {
                $where = "nickname";
            }
            $data = array(
                'province' => trim($this->input->post('province', TRUE)),
                'lng' => $this->input->post('lng', TRUE),//更新经度
                'lat' => $this->input->post('lat', TRUE),//更新维度
                'latitude' => $this->geohash->encode($this->input->post('lat', TRUE), $this->input->post('lng', TRUE)), //把经纬度更新转码
                'nowlocal' => $this->input->post('nowlocal', TRUE)
            );
            $this->db->where("$where =", $this->nickname)
                ->update('userdata', $data);

            $add_check = $this->db->select('u.age,u.sex,u.province,g.data_json')
                ->from('userdata AS u')
                ->join('grabdata AS g', 'u.nickname = g.nickname', 'left')
                ->where("u.$where =", $this->nickname)
                ->get('userdata')
                ->row_array();
            //↓判断当前时间是否大于用户会员时间
            if (time() >= $check_user['memtime']) {
                $this->db->where("$where =", $this->nickname)->update('user', array('member' => "0"));//大于更新会员过期
            }

            //----------------↓好友主动推送↓--------------//
            $row = $this->MyChat($check_user['userid']);
            $sql = " SELECT u.member,u.nickname,us.age,us.sex,u.userid FROM rem_user AS u LEFT JOIN rem_userdata AS us ON u.nickname = us.nickname WHERE member = '1' AND age >= {$add_check['age']} AND age <= {$add_check['age']}+10";
            $check = $this->db->query($sql)->result_array();

            $sex = "";
            if ($add_check['sex'] == "女") {
                $sex = "男";
            } else if ($add_check['sex'] = "男") {
                $sex = "女";
            }
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
                if ($data[$x]['userid'] == $row['nickname'] || $data[$x]['userid'] == $row['targetname']) {
                    $num = "";
                } else {
                    @$num = $data[$x]['userid'];
                }
            }
//            else {
//                $sql = " SELECT u.nickname,us.sex,u.userid FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname WHERE us.sex = '{$sex}' ";
//                $c = $this->db->query($sql)->result_array();
//                $x = @array_rand($c, 1);
//                @$num .= $c[$x]['userid'];
//            }
            //------------------------------------------//
            $result = array(
                'status' => 'success',
                'id' => $check_user['id'],
                'username' => $check_user['username'],
                'userid' => $check_user['userid'],
                'autstate' => $check_user['autstate'],
                'age' => $add_check['age'],
                'sex' => $add_check['sex'],
                'province' => $add_check['province'],
                'nickname' => $check_user['nickname'],
                'photo' => $check_user['photo'],
                'member' => $check_user['member'],
                'memtime' => isset($check_user['memtime']) ? $check_user['memtime'] : 0,
                'token' => $check_user['token'],
                'add_time' => $check_user['add_time'],
                'pushid' => isset($num) ? $num : NULL,
                'data_json' => isset($add_check['data_json']) ? $add_check['data_json'] : NULL
            );

            print json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            $result['status'] = "error";
            print json_encode($result);
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
            $member = isset($this->tarname) ? $this->tarname : $this->nickname;

            //查询我们是不是好友，是好友就可以聊天!
            $sql = " SELECT state FROM rem_friends WHERE nickname IN ('{$this->nickname}','{$this->tarname}') AND  tarname IN ('{$this->nickname}','{$this->tarname}') ";
            $check_f = $this->db->query($sql)->row_array();
            $state = isset($check_f['state']) ? $check_f['state'] : "0";


            //查询我喜欢的好友
            $data = array(
                'nickname' => $this->nickname,
                'tarname' => $this->tarname
            );
            $like = $this->db->select('state')->where($data)->get('friends')->row_array();
            if (!empty($like)) {
                $luck = "1";
            } else {
                $luck = "0";
            }

            $where = "u.id,u.username,u.userid,u.nickname,u.autstate,u.photo,us.wechat,us.qq,us.sex,us.height,us.weight,us.education,us.constellation,us.birthday,us.occupation,us.working,us.income,us.housing,us.kid,us.child,us.province,us.place,us.car,us.alcohol,us.smoke,us.shape,us.nation,us.marry,us.marriage,us.monologue";
            $check_info = $this->db->select($where)
                ->from('user as u')
                ->join('userdata as us', 'u.nickname = us.nickname', 'left')
                ->where('u.nickname', $member)
                ->get('user')
                ->row_array();
            $age = date('Y.m.d') - $check_info['birthday'];
            $sql = "SELECT photourl FROM rem_useralbum  WHERE nickname = '{$member}' ";
            $PhotoUrl = $this->db->query($sql)->result_array();
            $photourl = array_column($PhotoUrl, 'photourl');

            $mymates = $this->db->select('age,height,income,education,weight,marriage,shape,working,child,kid,alcohol')->where('nickname', $this->nickname)->get('mymates')->row_array();

            $check_info['age'] = floor($age);
            $check_info['state'] = (string)$state;
            $check_info['like'] = (string)$luck;
            $check_info['photourl'] = $photourl;
            $check_info['mymates'] = $mymates;
            if ($check_info) {
                print json_encode($check_info, JSON_UNESCAPED_UNICODE);
            } else {
                $result['status'] = "error";
                print json_encode($result);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 查出全部用户信息
     * @param register
     * @param 2016/11/6
     * @param 18:00
     */
    public function GetUserAll()
    {

        $where = "u.id,u.username,u.nickname,u.photo,us.sex,us.age,us.height,us.weight,us.education,us.constellation,us.birthday,us.occupation,us.working,us.income,us.housing,us.kid,us.child,us.province,us.place,us.car,us.alcohol,us.smoke,us.shape,us.nation,us.marry,us.marriage,us.lat,us.lng,us.monologue";
        $check_info = $this->db->select($where)
            ->from('user as u')
            ->join('userdata as us', 'u.nickname = us.nickname', 'left')
            ->get('user')
            ->result_array();
        echo $this->db->last_query();
        if ($check_info) {
            print json_encode($check_info, JSON_UNESCAPED_UNICODE);
        } else {
            $result['status'] = "error";
            print json_encode($result);
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
                $sql = "SELECT f.tarname,u.photo,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.tarname = u.nickname WHERE f.nickname = '{$this->nickname}' AND state = '0' ORDER BY f.add_time DESC";
                $check_like = $this->db->query($sql)->result_array();
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
                $sql = "SELECT f.nickname,u.photo FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.nickname = u.nickname WHERE f.tarname = '{$this->nickname}' AND state = '0' ORDER BY f.add_time DESC";
                $check_like = $this->db->query($sql)->result_array();
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

                $sql = "SELECT f.nickname,u.photo,u.userid,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.nickname = u.nickname WHERE f.state = '1' AND f.tarname = '{$this->nickname}'";
                $show_like = $this->db->query($sql)->result_array();

                $sql = "SELECT f.tarname,u.photo,u.userid,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.tarname = u.nickname WHERE  state = '1' AND f.nickname = '{$this->nickname}'";
                $show_likes = $this->db->query($sql)->result_array();

                foreach ($show_likes as $key => $value) {
                    $show_likes[$key]['nickname'] = $value['tarname'];
                }
                $like = array_merge_recursive($show_like, $show_likes);
                if (is_array($like)) {
                    print json_encode($like, JSON_UNESCAPED_UNICODE);
                    return $like;
                } else {
                    return false;
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
            $update = $this->db->delete('friends', $data);
            if ($update) {
                $result['status'] = "success";
                print  json_encode($result);
            } else {
                $result['status'] = "error";
                print  json_encode($result);
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
            $update = $this->db->delete('friends', $data);
            if ($update) {
                $result['status'] = "success";
                print  json_encode($result);
            } else {
                $result['status'] = "error";
                print  json_encode($result);
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

            $sql = " SELECT c.id,c.nickname,u.userid,u.member,u.photo,us.height,us.age,c.content,c.location,c.photourl,c.add_time FROM rem_circle AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname LEFT JOIN rem_userdata AS us ON c.nickname = us.nickname WHERE c.nickname = '{$this->nickname}' ORDER BY add_time DESC LIMIT {$num},{$limit}";
            $check_info = $this->db->query($sql)->result_array();

            $sql = " SELECT c.id,c.nickname,us.nowlocal,u.userid,u.photo,c.content,c.circleid,c.add_time FROM rem_circle_comment AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname LEFT JOIN rem_userdata AS us ON c.nickname = us.nickname ";
            $check_comment = $this->db->query($sql)->result_array();//评论信息


            $sql = " SELECT r.id,r.nickname,u.userid,r.targetname,r.content,r.commentid FROM rem_circle_reply AS r LEFT JOIN rem_user AS u ON r.targetname = u.nickname ";
            $check_reply = $this->db->query($sql)->result_array();//评论信息


            $check_like = $this->db->select('nickname,circleid,add_time')
                ->order_by('add_time', 'ASC')
                ->get('like')
                ->result_array();


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
                print json_encode($check_info, JSON_UNESCAPED_UNICODE);
            } else {
                print json_encode(array('status' => 'You have no data'), JSON_UNESCAPED_UNICODE);
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

//            $sql = " SELECT nickname,actid FROM rem_participant ";
            $check_m = $this->db->select('nickname,actid')->get('participant')->result_array();

            $check_info = $this->db->select("id,sponsor,activitytitle,introduction,activitytype,poster,city,actposition,starttime,endtime,stoptime,add_time")
                ->where('sponsor', $this->nickname)
                ->order_by('add_time', 'DESC')
                ->get('activity')
                ->result_array();
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
                print json_encode($check_info, JSON_UNESCAPED_UNICODE);
            } else {
                print json_encode(array('status' => 'You have no data'), JSON_UNESCAPED_UNICODE);
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
            $check = $this->db->select('nickname,actid')->where('nickname =', $this->nickname)->get('participant')->result_array();

            foreach ($check as &$item) {
                $check_info = $this->db->select("id,sponsor,activitytitle,introduction,activitytype,poster,city,actposition,starttime,endtime,stoptime,add_time")
                    ->where('id', $item['actid'])
                    ->order_by('add_time', 'DESC')
                    ->get('activity')
                    ->result_array();
                foreach ($check_info as $value => $va) {
                    $arr = array();
                    if ($va['id'] == $item['actid']) {
                        $arr[] = $item['nickname'];
                    }
                    $check_info[$value]['asd'] = (string)count($arr);
                }
                if (!empty($check_info)) {
                    print json_encode($check_info, JSON_UNESCAPED_UNICODE);
                } else {
                    print json_encode(array('status' => 'You have no data'), JSON_UNESCAPED_UNICODE);
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
            $check = $this->db->select('username,nickname,photo')->where($where, isset($id) ? $id : $username)->get('user')->row_array();
            if ($check) {
                print json_encode($check, JSON_UNESCAPED_UNICODE);
            } else {
                $result['status'] = "error";
                print json_encode($result);
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
            $check_info = $this->db->where($check)->get('readmy')->row_array();
            if (empty($check_info)) {
                $data = array(
                    'nickname' => $this->nickname,
                    'targetname' => $this->tarname,
                    'targetphoto' => $targetphoto,
                    'add_time' => time(),
                );
                $add = $this->db->insert('readmy', $data);
                if ($add) {
                    $result['status'] = "success";
                    print json_encode($result);
                } else {
                    $result['status'] = "error";
                    print json_encode($result);
                }
            }
        } catch (Exception $e) {
            print $e->getMessage();
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
            $check = $this->db->select('nickname,targetphoto,add_time')->where('targetname =', $this->nickname)->get('readmy')->result_array();
            if ($check) {
                print json_encode($check, JSON_UNESCAPED_UNICODE);
            } else {
                $result['status'] = "error";
                print json_encode($result);
            }
        }
    }

    /**
     * 聊天信息
     * @param MyChat
     * @param 2016/12/7
     * @param 16:34
     */
    private function MyChat($num)
    {
        $sql = " SELECT nickname,targetname FROM rem_mychat WHERE nickname = ('$num') OR  targetname = ('$num') ";
        $check_f = $this->db->query($sql)->row_array();
        if (!empty($check_f)) {
            return $check_f;
        } else {
            return false;
        }
    }
}
