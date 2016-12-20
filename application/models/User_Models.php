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

class User_Models extends CI_Model
{
    private $checkMember;

    function __construct()
    {
        parent::__construct();

    }


    public function getUserOne($nickname)
    {
        $checkInfo = $this->db->select('username,nickname,photo,userid,member,memtime,uptime,token,token,login_time,add_time')
            ->where('nickname =', $nickname)
            ->get('user')
            ->row_array();
        return $checkInfo;
    }
}

