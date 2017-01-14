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

class Common_Models extends CI_Model
{

    private $alias = array();

    function __construct()
    {
        parent::__construct();

    }

    /**
     * 添加数据 insertData 方法
     * @param $table  string  数据库表名
     * @param $data   array   所添加的数据内容
     * @return string
     */
    public function insertData($table, array $data)
    {
        if (!empty($table) AND is_array($data)) {
            $insert = $this->db->insert($table, $data);
            if ($insert) {
                return "success";
            } else {
                return "error";
            }
        } else {
            return false;
        }
    }

    /**
     * 更新数据 updateData 方法
     * @param $where array   更新数据条件
     * @param $table string  所更新的数据表名
     * @param $data  array   所更新的数据内容
     * @return string
     */
    public function updateData(array $where, $table, array $data)
    {
        if (!empty($table)) {

            $key = array_keys($where);
            $rew = implode($key);
            $update = $this->db->where($where)->update($table, $data);//"$rew =", $where[$rew]
            if ($update) {
                return "success";
            } else {
                return "error";
            }
        } else {
            return false;
        }
    }


    /**
     * 删除数据 deleteData 方法
     * @param $table     string   删除数据表名
     * @param $condition array    删除的数据
     * @param $where     array    删除数据指定一条（条件）
     * @return string
     */
    public function deleteData($table, array $condition, array $where = null)
    {
        if (empty($where)) {
            if (!empty($table)) {
                $del = $this->db->delete($table, $condition);
                if ($del) {
                    return "success";
                } else {
                    return "error";
                }
            } else {
                return false;
            }
        } else {
            $key = array_keys($where);
            $rew = implode($key);
            $del = $this->db->where("$rew =", $where[$rew])->delete($table, $condition);
        }
    }

    /**
     * 查询单条数据 getDataOne 方法
     * @param $table  string   查询表名
     * @param $query  string   查询的数据
     * @param $where  array   查询的数据
     * @return string
     */
    public function getDataOne($table, $query, $where, $status = true)
    {
        if ($status == true) {
            $key = array_keys($where);
            $rew = implode($key);
            $getDataOne = $this->db->select($query)
                ->where("$rew =", $where[$rew])
                ->get($table)
                ->row_array();
            return $getDataOne;
        } else {
            $getDataOne = $this->db->where($where)->get($table)->row_array();
            return $getDataOne;
        }
    }


    /**
     * 查询多条数据 getDataAll 方法
     * @param $where  string   更新数据条件
     * @param $table  string   查询表名
     * @return string
     */
    public function getDataAll($table, $query, array $where = null)
    {
        if (empty($where)) {

            if (!empty($table)) {
                $getAll = $this->db->select($query)
                    ->get($table)
                    ->result_array();
                return $getAll;
            } else {
                return false;
            }

        } else {
            $key = array_keys($where);
            $rew = implode($key);
            $getAll = $this->db->select($query)
                ->where("$rew =", $where[$rew])
                ->get($table)
                ->result_array();
            return $getAll;
        }
    }

    /*
     * 记录总数
     */
    public function getCount($where, $table, $status = FALSE)
    {
        if ($status == FALSE) {
            $count = $this->db->where($where)->count_all_results($table);
        } else {
            $count = $this->db->count_all($table);
        }
        return $count;
    }

    /**
     * 更新等级经验
     * @param $nickname  string   更新的数据昵称
     * @return string
     */
    public function updateGrade($nickname)
    {
        if (!empty($nickname)) {
            $sql = "UPDATE rem_grade SET memberintegral = memberintegral+1,integral = integral+1 WHERE nickname = '$nickname'";
            $row = $this->db->query($sql);
            if ($row) {
                return "success";
            } else {
                return "error";
            }
        } else {
            return false;
        }
    }

    /*
     * 小号信息
     */
    public function reserveUser($where)
    {
        if (!empty($where)) {
            $query = "username,nickname,photo,userid,member,memtime,uptime,token,autstate,age,sex,wechat,qq,height,weight,education,constellation,birthday,occupation,working,income,housing,kid,child,province,place,car,alcohol,smoke,shape,nation,marry,marriage,lng,lat,nowlocal,monologue,status,photourl,my_age,my_height,my_income,my_education,my_weight,my_marriage,my_shape,my_working,my_child,my_kid,my_alcohol,like,state";
            $info = $this->db->select($query)->where('sex =', $where)->get('local')->result_array();
            return $info;
        }
    }

    /*
     * 判断是否为好友状态 为1 用户资料可以点击聊天
     */
    public function checkedFriends($nickname, $tarName)
    {
        $Info = $this->db->select('state')
            ->where_in('nickname', array($nickname, $tarName))
            ->where_in('tarname', array($tarName, $nickname))
            ->get('friends')
            ->row_array();
        return $Info;
    }

    /*
     * 判断是否为点击过喜欢的用户 为0 用户资料可以点击喜欢为好友
     */
    public function clickLike($nickname, $tarName)
    {

        $data = array(
            'nickname' => $nickname,
            'tarname' => $tarName
        );
        $like = $this->db->select('state')->where($data)->get('friends')->row_array();
        return $like;
    }


    /*
     * app 版本号 :v1.0.1
     */
    public function queryVersion()
    {
        $check = $this->db->select('versionname,versioncode,content,url')
            ->order_by('versioncode', 'DESC')
            ->limit(1)
            ->get('version')
            ->row_array();
        return $check;
    }
}
