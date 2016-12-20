<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends CI_Controller
{

    private $number;

    private $nickname;

    private $checkMember;

    private $second;

    public $time;

    private $day = 3600 * 24;

    function __construct()
    {
        parent::__construct();
        $this->nickname = $this->input->post('nickname', TRUE);//$this->input->post('nickname', TRUE)
        $this->time = time();
        $this->load->model('Grade_Models');

    }


    /**
     * 更新用户等级
     * @param gradeQuery
     * @param 2016/12/15
     * @param 12:00
     * return update
     */
    public function gradeQuery()
    {

        $this->checkMember = $this->Grade_Models->gradeQuery($this->nickname);

        if ( date('j') >= date('j', $this->checkMember['add_time'])) {
            if (!empty($this->checkMember)) {

                //status时间戳小于现在时间戳执行以下代码
                if (date('j',$this->checkMember['status']) <= date('j')) {
                    if ((int)$this->checkMember['member'] == 1) {
                        $data = array(
                            'memberintegral' => $this->checkMember['memberintegral'] + 8,
                            'integral' => $this->checkMember['integral'] + 1
                        );
                    } else {
                        $data = array(
                            'integral' => $this->checkMember['integral'] + 1
                        );
                    }
                    $updateNow = $this->db->where('nickname =', $this->nickname)
                        ->update('grade', $data);
                    if ($updateNow) {
                        $this->db->where('nickname =', $this->nickname)
                            ->update('grade', array('status' => $this->day + $this->time));
                    }
                }

                if (!empty($this->checkMember['online_time']) != 0 AND !empty($this->checkMember['signout_time']) != 0) {

                    //以秒来计算小时
                    $this->second = $this->checkMember['signout_time'] - $this->checkMember['online_time'];

                } else {
                    //如果onlineTime为空的就更新现在时间
                    $this->db->where('nickname =', $this->nickname)->update('grade', array('online_time' => $this->time));
                }

                //累加更新临时积分
                if ($this->second > 0) {
                    $data = array(
                        'temporary' => $this->checkMember['temporary'] + $this->second,//临时积分
                        'online_time' => $this->time
                    );
                    $this->db->where('nickname =', $this->nickname)
                        ->update('grade', $data);
                }

                sleep(3);
                $this->updateGrade($this->checkMember['integral']);
                $this->updateVipGrade($this->checkMember['memberintegral']);
                //计算用户用时有2个小时执行加经验
                if ($this->checkMember['temporary'] == floor((int)7200) || $this->checkMember['temporary'] >= floor((int)7200)) {
                    switch ($this->checkMember['temporary']) {
                        case $this->checkMember['member'] == 1;//会员经验

                            $this->number = $this->VipGrade($this->checkMember['membergrade']);
                            $integral = isset($this->number['integral']) ? $this->number['integral'] : 1;

                            $row = array(
                                'memberintegral' => $this->checkMember['memberintegral'] + 2,
                                'integral' => $this->checkMember['integral'] + 2 + $integral,
                            );
                            $updateMember = $this->db->where('nickname =', $this->nickname)->update('grade', $row);
                            if ($updateMember) {
                                $this->db->where('nickname =', $this->nickname)
                                    ->update('grade',
                                        array('add_time' => 3600 * 24 + $this->time, 'online_time' => 0, 'signout_time' => 0, 'temporary' => 0));
                            }
                            break;
                        case $this->checkMember['member'] == 0;//普通经验

                            $data = array(
                                'integral' => $this->checkMember['integral'] + 2,
                            );
                            $updateGrade = $this->db->where('nickname =', $this->nickname)->update('grade', $data);
                            if ($updateGrade) {
                                $this->db->where('nickname =', $this->nickname)
                                    ->update('grade',
                                        array('add_time' => 3600 * 24 + $this->time, 'online_time' => 0, 'signout_time' => 0, 'temporary' => 0));
                            }
                            break;
                        default;
                            break;
                    }
                }
            }
        }
    }


    /**
     * 超级会员用户等级   添加倍数
     * @param accumulation
     * @param 2016/12/15
     * @param 12:00
     * return json
     */
    private function VipGrade($number)
    {

        $row = array();
        switch ($this->nickname) {
            case $number == 1;
                $double['integral'] = 1;
                $row = $double;
                break;
            case $number == 2;
                $double['integral'] = 1.5;
                $row = $double;
                break;
            case $number == 3;
                $double['integral'] = 2;
                $row = $double;
                break;
            case $number == 4;
                $double['integral'] = 2.5;
                $row = $double;
                break;
            case $number == 5;
                $double['integral'] = 3;
                $row = $double;
                break;
            case $number == 6;
                $double['integral'] = 3.5;
                $row = $double;
                break;
            case $number == 7;
                $double['integral'] = 4;
                $row = $double;
                break;
            case $number == 8;
                $double['integral'] = 4.5;
                $row = $double;
                break;
            default;
                break;
        }
        return $row;
    }


    /**
     * 更新普通用户等级
     * @param accumulation
     * @param 2016/12/15
     * @param 12:00
     * return array
     */
    private function updateGrade($integral)
    {
        $unm = 240;
        $arr = null;
        for ($i = 2; $i <= 39; $i++) {
            $unm *= 2;
            $arr[$i] = $unm . "<br />";
            krsort($arr);
        }
        $data = array();//key对应的是用户级别  value对应用户经验条
        foreach ($arr as $item => $value) {
            if ((int)$integral >= $value) {
                $data[$item] = $value;
            }
        }
        $grade = each($data);
        $int = isset($grade['key']) ? $grade['key'] : 1;
        if (!empty(is_numeric($int))) {
            $setUpdate = $this->db->where('nickname =', $this->nickname)->update('grade', array('grade' => $int));
            if ($setUpdate) {
                return "success";
            }
        }
    }

    /**
     * 更新vip用户等级
     * @param accumulation
     * @param 2016/12/15
     * @param 12:00
     * return array
     */
    private function updateVipGrade($integral)
    {
        $unm = 180;
        $arr = null;
        for ($i = 2; $i <= 8; $i++) {
            $unm *= 2;
            $arr[$i] = $unm;
            krsort($arr);
        }
        $data = array();//key对应的是用户级别  value对应用户经验条
        foreach ($arr as $item => $value) {
            if ((int)$integral >= $value) {
                $data[$item] = $value;
            }
        }
        $grade = each($data);
        $int = isset($grade['key']) ? $grade['key'] : 1;
        if (!empty(is_numeric($int))) {
            $Update = $this->db->where('nickname =', $this->nickname)->update('grade', array('membergrade' => $int));
            if ($Update) {
                return "success";
            }
        }
    }
}