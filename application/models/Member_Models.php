<?php
/**
 * www.qinyejun.com
 *
 * Description:
 *
 * @package         www.qinyejun.com
 * @file            node_model.php
 * @author          QinYejun <qinyejun@me.com>
 * @copyright       Copyright (c) 2016, Wonghzx, Inc.
 * @since           Version 1.0
 * @time
 */
// ------------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_Models extends CI_Model
{

    private $showLike;

    function __construct()
    {
        parent::__construct();

    }


    /*
     * 用户登录验证
     */
    public function signToLove($nickname, $password)
    {
        if (!empty($nickname) AND !empty($password)) {
            $sql = " SELECT id,username,nickname,photo,userid,member,memtime,token,autstate,add_time,login_time FROM rem_user WHERE  (nickname = '{$nickname}' OR username = '{$nickname}') AND password = '{$password}' ";
            $check_user = $this->db->query($sql)->row_array();
            return $check_user;
        }
    }

    /*
     * 登录返回数据
     */
    public function returnData($where, $nickname)
    {
        if (!empty($where)) {
            $add_check = $this->db->select('u.age,u.sex,u.province,g.data_json,gr.membergrade,gr.grade')
                ->from('userdata AS u')
                ->join('grabdata AS g', 'u.nickname = g.nickname', 'left')
                ->join('grade AS gr', 'u.nickname = gr.nickname', 'left')
                ->where("u.$where =", $nickname)
                ->get('userdata')
                ->row_array();
            return $add_check;
        }
    }

    /*
     * 推送查询用户
     */
    public function queryPushUser($age)
    {
        if (is_numeric((int)$age)) {
            $sql = " SELECT u.member,u.nickname,us.age,us.sex,u.userid FROM rem_user AS u LEFT JOIN rem_userdata AS us ON u.nickname = us.nickname WHERE member = '1' AND age >= {$age} AND age <= {$age}+10 AND status = '1' ";
            $check = $this->db->query($sql)->result_array();
            return $check;
        }
    }

    /*
     * 查出单个用户信息
     */
    public function getUserInfo($member)//用户
    {
        if (!empty($member)) {

            $where = "u.id,u.username,u.userid,u.nickname,u.autstate,u.photo,u.member,us.wechat,us.qq,us.sex,us.height,us.weight,us.education,us.constellation,us.birthday,us.occupation,us.working,us.income,us.housing,us.kid,us.child,us.province,us.place,us.car,us.alcohol,us.smoke,us.shape,us.nation,us.marry,us.marriage,us.monologue,g.membergrade,g.grade,us.age,g.integral,g.memberintegral";
            $check_info = $this->db->select($where)
                ->from('user as u')
                ->join('userdata as us', 'u.nickname = us.nickname', 'left')
                ->join('grade AS g', 'u.nickname = g.nickname', 'left')
                ->where('u.nickname', $member)
                ->get('user')
                ->row_array();
            return $check_info;
        }
    }

    /*
     * 查出全部用户信息
     */
    public function getUserAll($start, $limit)
    {
        $where = "u.id,u.username,u.userid,u.nickname,u.autstate,u.photo,u.member,us.wechat,us.qq,us.sex,us.height,us.weight,us.education,us.constellation,us.birthday,us.occupation,us.working,us.income,us.housing,us.kid,us.child,us.province,us.place,us.car,us.alcohol,us.smoke,us.shape,us.nation,us.marry,us.marriage,us.monologue,g.membergrade,g.grade,us.nowlocal,u.add_time,u.userid,us.latitude";
        $sql = " SELECT {$where} FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname LEFT JOIN rem_grade AS g ON us.nickname = g.nickname WHERE us.status = '1' ORDER BY u.add_time DESC LIMIT {$start},$limit";
        $userInfo = $this->db->query($sql)->result_array();

        return $userInfo;
    }

    /*
     * 查询我喜欢的人
     */
    public function queryLike($nickname)
    {
        if (!empty($nickname)) {
            $sql = "SELECT f.tarname,u.photo,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.tarname = u.nickname WHERE f.nickname = '{$nickname}' AND state = '0' ORDER BY f.add_time DESC";
            $check_like = $this->db->query($sql)->result_array();
            return $check_like;
        }
    }

    /*
     * 查询出喜欢我的人
     */
    public function likeMy($nickname)
    {
        if (!empty($nickname)) {
            $sql = "SELECT f.nickname,u.photo FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.nickname = u.nickname WHERE f.tarname = '{$nickname}' AND state = '0' ORDER BY f.add_time DESC";
            $check_like = $this->db->query($sql)->result_array();
            return $check_like;
        }
    }

    /*
     * 查询我的好友
     */
    public function likeEach($status = 'tarName', $nickname)
    {
        if ($status == "tarName") {
            $sql = "SELECT f.nickname,u.photo,u.userid,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.nickname = u.nickname WHERE f.state = '1' AND f.tarname = '{$nickname}'";
            $this->showLike = $this->db->query($sql)->result_array();
        } else {
            $sql = "SELECT f.tarname,u.photo,u.userid,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.tarname = u.nickname WHERE  f.state = '1' AND f.nickname = '{$nickname}'";
            $this->showLike = $this->db->query($sql)->result_array();
        }
        return $this->showLike;

    }


    //---------------------------------------------//
    //我的动态
    public function queryMyCircle($num, $limit, $nickname)
    {
        if (!empty($nickname)) {
            $sql = " SELECT c.id,c.nickname,u.userid,u.member,u.photo,us.height,us.age,c.content,c.location,c.photourl,c.add_time FROM rem_circle AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname LEFT JOIN rem_userdata AS us ON c.nickname = us.nickname WHERE c.nickname = '{$nickname}' ORDER BY add_time DESC LIMIT {$num},{$limit}";
            $check_info = $this->db->query($sql)->result_array();
            return $check_info;
        }
    }

    //---------------------------------------------//

    /*
     * 我的活动
     */
    public function myActivity($nickname)
    {
        if (empty($nickname))
            throw new Exception('请检查sql语句!');
        $sql = "id,sponsor,activitytitle,introduction,activitytype,poster,city,actposition,starttime,endtime,stoptime,add_time";
        $check_info = $this->db->select($sql)
            ->where('sponsor', $nickname)
            ->order_by('add_time', 'DESCd')
            ->get('activity')
            ->result_array();
        return $check_info;
    }

    /*
     * 我参与的活动
     */
    public function participateActivity($id)
    {
        if (empty($id))
            throw new Exception('Id不能为空!');

        $sql = "id,sponsor,activitytitle,introduction,activitytype,poster,city,actposition,starttime,endtime,stoptime,add_time";
        $check_info = $this->db->select($sql)
            ->where('id', $id)
            ->order_by('add_time', 'DESC')
            ->get('activity')
            ->result_array();
        return $check_info;
    }


    /*
     * 查询聊天信息
     */
    public function queryMyChat($unm)
    {
        if (empty($unm))
            throw new Exception('error');
        $sql = " SELECT nickname,targetname FROM rem_mychat WHERE (nickname = '$unm' OR  targetname = '$unm') ";
        $check_f = $this->db->query($sql)->row_array();
        return $check_f;
    }


}

