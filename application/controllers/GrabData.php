<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GrabData extends CI_Controller
{
    public $time;

    function __construct()
    {
        parent::__construct();
        $this->time = time();
    }

    /**
     * 抓取用户习惯
     * @param GrabData
     * @param 2016/12/5
     * @param 17:40
     */
    public function index()
    {

        $data_json = $this->input->post('data_json', TRUE);
        $json = json_decode($data_json, TRUE);
        if (!empty($this->input->post())) {
            $add = $this->Common_Models->updateData(array('nickname' => $json['nickname']), 'grabdata', array('data_json' => $data_json));
            if ($add) {
                $result['status'] = "success";
                echo json_encode($result);
                //-----------------------//
                $this->load->model('Grade_Models');
                $grade = $this->Grade_Models->gradeQuery($json['nickname']);

                if ($this->time >= $grade['add_time']) {

                    $data = array('signout_time' => $this->time);
                    $this->Common_Models->updateData(array('nickname' => $json['nickname']), 'grade', $data);
                    if ($grade['temporary'] >= 0) {
                        $url = $url = base_url('Grade/gradeQuery') . "?nickname=" . $json['nickname'];
                        $this->curlFileGetContents($url);

                        $this->timingUp($json['nickname']);
                    }
                }
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }

    /*
     * 定时访问
     */
    public function timingUp($nickname)
    {
        sleep(3);
        $temporary = $this->Common_Models->getDataOne('grade', 'temporary', array('nickname' => $nickname));
        if ($temporary['temporary'] >= 7200) {
            $url = base_url('Grade/gradeQuery') . "?nickname=$nickname";
            $this->curlFileGetContents($url);
        }

    }


    /*
     * 访问链接
     */
    public function curlFileGetContents($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
}
