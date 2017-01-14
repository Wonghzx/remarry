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

class Waterfall_Models extends CI_Model
{
    private $checkMember;

    function __construct()
    {
        parent::__construct();

    }

    /**
     * 瀑布流
     * @param index
     * @param 2016/11/18
     * @param 9:46
     */
    public function getUserALl($where)
    {
        $sql = " SELECT us.nickname,u.photo,us.age,us.height,us.monologue,u.member FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname WHERE us.status = '1' {$where}   ";
        $check_info = $this->db->query($sql)->result_array();
        return $check_info;
    }
}

