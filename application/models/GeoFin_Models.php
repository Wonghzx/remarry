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

class GeoFin_Models extends CI_Model
{

    private $nearbyPeopleInfo;

    function __construct()
    {
        parent::__construct();

    }

    /**
     * 附近人 筛选功能
     * @param GeoHah
     * @param 2016/11/11
     * @param 12:00
     */
    public function queryNearbyPeople($where = null, $and = null, $value = null)
    {
        if (!empty($value)) {

            $row = "us.nickname,u.photo,us.nowlocal,us.age,us.sex,us.lng,us.lat,us.constellation,us.education,us.car,us.housing,us.height,us.shape";
            $sql = "SELECT $row FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname WHERE {$where} {$and} latitude LIKE '{$value}%' AND us.status = '1' ";
            $this->nearbyPeopleInfo = $this->db->query($sql)->result_array();
        }
//        else {
//            $sql = "SELECT us.nickname,u.photo,us.nowlocal,us.age,us.sex,us.lng,us.lat,us.constellation,us.education,us.car,us.housing,us.height,us.shape FROM rem_userdata AS us LEFT JOIN rem_user AS u ON us.nickname = u.nickname WHERE  us.status = '1'";
//            $this->nearbyPeopleInfo = $this->db->query($sql)->result_array();
//        }
        return $this->nearbyPeopleInfo;
    }
}

