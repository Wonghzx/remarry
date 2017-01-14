<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication_Models extends CI_Model
{
    /*
     * 身份验证信息
     */
    public function identityData( )
    {
        $query = "a.id,a.nickname,u.photo,a.photo,a.add_time,a.status,u.autstate";
        $sql = " SELECT {$query} FROM rem_authentication AS a LEFT JOIN rem_user AS u ON  a.nickname = u.nickname ORDER BY a.add_time DESC";
        $dataInfo = $this->db->query($sql)->result_array();
        return $dataInfo;

    }
}