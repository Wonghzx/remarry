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

class PersonalData_Models extends CI_Model
{
    private $checkMember;

    function __construct()
    {
        parent::__construct();

    }

    /**
     * 聊天好友
     * @param MyChat
     * @param 2016/12/6
     * @param 16:57
     */
    public function MyChat($userId, $targetId)
    {
        if (empty($userId) && empty($targetId))
            throw new Exception('error');
        $sql = " SELECT nickname FROM rem_mychat WHERE nickname IN ('{$userId}','{$targetId}') AND  targetname IN ('{$userId}','{$targetId}') ";
        $check_user = $this->db->query($sql)->row_array();
        return $check_user;
    }
}

