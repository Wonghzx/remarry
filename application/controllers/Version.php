<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Version extends CI_Controller
{

    public function VersionC()
    {
        $check = $this->Common_Models->queryVersion();
        echo json_encode($check, JSON_UNESCAPED_UNICODE);
    }
}
