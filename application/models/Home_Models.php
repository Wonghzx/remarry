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

class Home_Models extends CI_Model
{

    private $homeDataInfo;

    private $status = true;

    function __construct()
    {
        parent::__construct();

    }

    /**
     * App首页
     * @param index
     * @param 2016/11/9
     * @param 10:00
     */
    public function queryHomeInfo($res = true, $where, $rand)
    {
        if ($res == true) {

            $sql = "SELECT us.nickname,u.photo,u.userid,us.age,us.sex,us.height,us.constellation,u.memtime,g.membergrade,g.grade FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname LEFT JOIN rem_grade AS g ON us.nickname = g.nickname WHERE us.status = '1' AND {$where}   {$rand}  ";

            $this->homeDataInfo = $this->db->query($sql)->result_array();
            if (empty($this->homeDataInfo)) {
                $sql = "SELECT us.nickname,u.photo,u.userid,us.age,us.sex,us.height,us.constellation,u.memtime,g.membergrade,g.grade FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname LEFT JOIN rem_grade AS g ON us.nickname = g.nickname WHERE  us.status = '1' AND RAND() LIMIT 0 , 20";
                $this->homeDataInfo = $this->db->query($sql)->result_array();
            }
        }

        return $this->homeDataInfo;
    }

    /**
     * App首页 按条件添加friend
     * @param AddFriend
     * @param 2016/11/9
     * @param 10:00
     */
    public function addFriend($nickname, $tarName)
    {
        if ($this->status == true) {
            $data = array($nickname, $tarName);
            $friendInfo = $this->db->select('id,state')
                ->where_in('nickname', $data)
                ->where_in('tarname', $data)
                ->get('friends')
                ->row_array();
            return $friendInfo;
        } else {
            return false;
        }
    }


    /*
     * 查询是否为好友
     */
    public function queryFriendsTrue(array $where)
    {
        if (!empty($where)) {
            $checkUser = $this->db->where($where)
                ->get('friends')
                ->row_array();//查询是否有这条数据
            return $checkUser;
        }
    }

}

