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

class Activity_Models extends CI_Model
{
    private $checkMember;

    function __construct()
    {
        parent::__construct();

    }


    /**
     * 查询
     * @param AddActivity
     * @param 2016/11/14
     * @param 10:52
     */
    public function queryActivity($latitude)
    {
        if (!empty($latitude)) {
            $sql = "id,sponsor,activitytitle,introduction,activitytype,poster,city,actposition,starttime,endtime,stoptime,lng,lat,state,add_time";
            $Info = $this->db->select($sql)
                ->like('latitude', $latitude, 'after')
                ->order_by('add_time', 'DESC')
                ->get('activity')
                ->result_array();
            if (is_array($Info)) {
                return $Info;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }
}

