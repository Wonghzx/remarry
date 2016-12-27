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
     * @param $table string  所添加的数据表名
     * @param $data  array   所更新的数据内容
     * @return string
     */
    public function updateData(array $where, $table, array $data)
    {
        if (!empty($table)) {

            $key = array_keys($where);
            $rew = implode($key);
            $update = $this->db->where("$rew =", $where[$rew])->update($table, $data);
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

    /*
     * 查询单条数据
     */
    public function getDataOne()
    {

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
}
