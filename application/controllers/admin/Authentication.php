<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller
{
    private $res = [0, 1, 2, 3];

    private $table;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Authentication_Models');
        $this->table = $this->input->post('table', true);
    }

    /*
     * 身份审核
     */
    public function identity()
    {

        $dataInfo = $this->Authentication_Models->identityData();

        foreach ($dataInfo as $item => $value) {
            $photoUrl = explode(',', $value['photo']);
            $dataInfo[$item]['photo'] = $photoUrl;
        }

        $data = [
            'dataInfo' => $dataInfo,
            'countInfo' => count($dataInfo),
        ];
        $this->load->view('admin/auditing/authentication', $data);
    }


    /*
     * 设置审核状态
     */
    public function setState()
    {
        $nickname = $this->input->post('nickname', true);
        if (!empty($nickname) AND !empty($this->table)) {
            $name = substr($nickname, 0, -3);//昵称
            $state = substr($nickname, -3);//状态码  200

            if ($this->table == "user") {
                $res = "autstate";
            } else {
                $res = "state";
            }
            if ($state == '200') {
                $suc = 1;
            } else {
                $suc = $this->res['3'];
            }
            $result = $this->Common_Models->updateData(array('nickname' => $name), $this->table, array($res => "$suc"));
            if ($result == "success") {
                echo json_encode('success');
                die;
            }
        }
    }


    /*
     * 删除资料
     */
    public function delAuthentication()
    {
        $id = $this->input->post('id', true);
        if (!empty($this->table)) {
            $where = array(
                'id' => $id
            );

            $res = $this->Common_Models->getDataOne($this->table, 0, $where, false);
            if (!empty($res)) {
                if (!empty($res['photo'])) {
                    $url = explode(',', $res['photo']);
                    foreach ($url as $item => $value) {
                        $urlP = substr($value, 21);
                        @unlink('./' . $urlP);
                    }
                    echo json_encode($url);
                }
                $this->Common_Models->deleteData($this->table, array('id' => $id));
            }
        }
    }

}