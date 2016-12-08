<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Version extends CI_Controller
{

    public function VersionC()
    {
        $check = $this->db->select('versionname,versioncode,content,url')
            ->order_by('versioncode', 'DESC')
            ->limit(1)
            ->get('version')
            ->row_array();
        echo json_encode($check, JSON_UNESCAPED_UNICODE);
    }
}
