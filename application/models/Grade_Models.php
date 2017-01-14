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

class Grade_Models extends CI_Model
{
    private $checkMember;

    function __construct()
    {
        parent::__construct();

    }


    public function gradeQuery($nickname)
    {
        $where = "g.membergrade,g.memberintegral,g.grade,g.integral,g.temporary,g.online_time,g.signout_time,g.add_time,g.status,u.member,g.state";
        $this->checkMember = $this->db->select($where)
            ->from('grade AS g')
            ->join('user AS u', 'g.nickname=u.nickname', 'left')
            ->where('g.nickname =', $nickname)
            ->get('grade')->row_array();
        return $this->checkMember;
    }
}

