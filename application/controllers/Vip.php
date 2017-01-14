<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vip extends CI_Controller
{
    protected $nickname;
    protected $time;

    /**
     * Vip constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->nickname = $this->input->post('nickname', TRUE);
        $this->time = $this->input->post('time', TRUE);
        $this->d = date("t", time());//给定月份所应有的天数
    }

    /**
     * 开通会员时间
     * @param vip
     * @param 2016/12/8
     * @param 10:52
     */
    private function vip()
    {
        try {
            if (empty($this->time))
                throw new Exception('金额不能为空');

            switch ($this->time) {
                case($this->time == 1);//一个月会员
                    $vip = 3600 * 24 * 30 + time();
                    return $vip;
                    break;
                case ($this->time == 2);//半年会员
                    $vip = 3600 * 24 * 180 + time();
                    return $vip;
                    break;
                case ($this->time == 3);//一年会员
                    $vip = 3600 * 24 * 365 + time();
                    return $vip;
                    break;
                default;
                    return false;
                    break;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 执行开通会员
     * @param OpenVip
     * @param 2016/12/8
     * @param 14:58
     */
    public function OpenVip()
    {
        try {
            if (empty($this->nickname))
                throw new Exception('您称不能为空');

            $check = $this->Common_Models->getDataOne('user', 'memtime', array('nickname' => $this->nickname));
            if (empty($check))
                throw new Exception('请求失败');

            if (!empty($this->vip())) {
                $num = floor(($check['memtime'] - time()) / 86400);//算出我我会员剩多少天再叠加
                $vipTime = $this->vip();
                if ($num < 0) {//小于0是负数
                    $day = floor(0 + $vipTime);
                } else {
                    $day = floor(3600 * 24 * $num + $vipTime);
                }
                $data = array('memtime' => $day, 'member' => "1", 'uptime' => time());

                $update = $this->Common_Models->updateData(array('nickname' => $this->nickname), 'user', $data);
                if ($update == "success") {
                    $result['memtime'] = $day;
                    $result['status'] = "success";
                    echo json_encode($result);
                } else {
                    $result['status'] = "error";
                    echo json_encode($result);
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 会员开通失败
     * @param FailedVip
     * @param 2016/12/8
     * @param 14:58
     */
    public function FailedVip()
    {
        $ordernumber = $this->input->post('ordernumber', TRUE);
        if (!empty($this->nickname)) {

            $check = $this->Common_Models->getDataOne('failedvip', false, array('nickname' => $this->nickname), false);
            if ($check == "") {
                $data = array(
                    'nickname' => $this->nickname,
                    'ordernumber' => $ordernumber,
                    'add_time' => time()
                );

                $add = $this->Common_Models->insertData('failedvip',$data);
                if ($add == "success") {
                    $result['status'] = 'success';
                    echo json_encode($result);
                } else {
                    $result['status'] = 'error';
                    echo json_encode($result);
                }
            } else {
                $result['status'] = 'error';
                echo json_encode($result);
            }
        }
    }
}
