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

class aliPay_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 开通会员时间
     * @param vip
     * @param 2016/12/8
     * @param 10:52
     */
    private function vip($time)
    {
        try {
            if (empty($time))
                throw new Exception('金额不能为空');

            switch ($time) {
                case($time == 1);//一个月会员
                    $vip = 3600 * 24 * 30 + time();
                    return $vip;
                    break;
                case ($time == 2);//半年会员
                    $vip = 3600 * 24 * 180 + time();
                    return $vip;
                    break;
                case ($time == 3);//一年会员
                    $vip = 3600 * 24 * 365 + time();
                    return $vip;
                    break;
                default;
                    return false;
                    break;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 执行开通会员
     * @param OpenVip
     * @param 2016/12/8
     * @param 14:58
     */
    public function OpenVip($nickname, $time)
    {
        try {
            if (empty($nickname))
                throw new Exception('您称不能为空');

            $check = $this->db->select('memtime')->where('nickname =', $nickname)->get('user')->row_array();
            if (empty($check))
                throw new Exception('请求失败');

            if (!empty($this->vip($time))) {
                $num = floor(($check['memtime'] - time()) / 86400);//算出我我会员剩多少天再叠加
                $vipTime = $this->vip($time);
                if ($num < 0) {//小于0是负数
                    $day = floor(0 + $vipTime);
                } else {
                    $day = floor(3600 * 24 * $num + $vipTime);
                }
                $update = $this->db->where('nickname =', $nickname)->update('user', array('memtime' => $day, 'member' => "1"));
                if ($update) {
                    $result['memtime'] = $day;
                    $result['status'] = "success";
                    return json_encode($result);
                } else {
                    $result['status'] = "error";
                    return json_encode($result);
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function queryOrder($data)
    {
        if (!empty($data)) {
            $result = $this->db->select('uptime,nickname')
                ->where($data)
                ->get('user')
                ->row_array();
            return $result;
        }

    }
}