<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Controller
{

    private $sponsor;

    /**
     * Activity constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->sponsor = $this->input->post('sponsor', TRUE);//nickname 主办方
        $this->geohash = new Geohash();
        $this->load->model('Activity_Models');
    }

    /**
     * 创建活动
     * @param AddActivity
     * @param 2016/11/14
     * @param 10:52
     */
    public function AddActivity()
    {
        try {
            if (empty($this->sponsor))
                throw new Exception('主办方不能为空哦！亲....');

            if (!empty($this->input->post())) {
                $data = array(
                    'sponsor' => $this->sponsor,
                    'activitytitle' => $this->input->post('activitytitle', TRUE),//活动名称
                    'introduction' => $this->input->post('introduction', TRUE),//活动介绍
                    'activitytype' => $this->input->post('activitytype', TRUE),//活动类型
                    'poster' => $this->input->post('poster', TRUE),//海报
                    'city' => $this->input->post('city', TRUE),//城市
                    'actposition' => $this->input->post('actposition', TRUE),//活动位置名称
                    'starttime' => $this->input->post('starttime', TRUE),//开始时间
                    'endtime' => strtotime($this->input->post('starttime', TRUE)),//活动截取报名时间
                    'stoptime' => $this->input->post('stoptime', TRUE),//结束时间
                    'lng' => $this->input->post('lng', TRUE),//经度
                    'lat' => $this->input->post('lat', TRUE),//维度
                    'latitude' => $this->geohash->encode($this->input->post('lat', TRUE), $this->input->post('lng', TRUE)),//维度
                    'add_time' => time()
                );

                $add_info = $this->Common_Models->insertData('activity', $data);
                if ($add_info == "success") {
                    $result['status'] = "success";
                    echo json_encode($result);
                    $this->load->model('Grade_Models');
                    $grade = $this->Grade_Models->gradeQuery($this->sponsor);
                    if ((int)$grade['member'] == 1) {
                        $data = array(
                            'memberintegral' => $grade['memberintegral'] + 1,
                            'integral' => $grade['integral'] + 1
                        );
                    } else {
                        $data = array(
                            'integral' => $grade['integral'] + 1
                        );
                    }

                    $this->Common_Models->updateData(array('nickname' => $this->sponsor), 'grade', $data);
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
     * 查询
     * @param AddActivity
     * @param 2016/11/14
     * @param 10:52
     */
    public function CheckActivity()
    {
        $this->db->cache_on();
        $lng = $this->input->post('lng', TRUE);//经度
        $lat = $this->input->post('lat', TRUE);//维度
        if (!empty($this->input->post())) {
            @$hash = $this->geohash->encode($lat, $lng);
            $prefix = substr($hash, 0, 4);
            @$neighbors = $this->geohash->neighbors($prefix);//取出相邻八个区域
            array_push($neighbors, $prefix);

            $data = array();

            $check = $this->Common_Models->getDataAll('participant', 'nickname,actid');//查询出参与活动人

            foreach ($neighbors as $key => $value) {

                $check_info = $this->Activity_Models->queryActivity($value);//查询出附近的活动信息
                foreach ($check_info as $item => $va) {

                    $data[] = $va;
                    foreach ($data as $ite => $val) {
                        $dat = array();
                        foreach ($check as $i => $v) {
                            if ($val['id'] == $v['actid']) {
                                $dat[] = $v['nickname'];
                            }
                        }
                        $data[$ite]['countpeople'] = strval(count($dat));
                    }
                    //↓时间活动时间到期
                    if (time() > $va['endtime']) {
                        $this->Common_Models->updateData(array('endtime' => $va['endtime']), 'activity', array('state' => "0"));
                    }
                    $t = 3600 * 24 * 7 + $va['endtime'];//活动时间到期7天自动删除
                    if (time() == $t || time() > $t) {
                        $id = $this->Common_Models->getDataAll('activity', 'id', array('state' => "0"));
                        //查出活动到期7天后删除
                        foreach ($id as &$val) {
                            $this->Common_Models->deleteData('participant', array('actid' => $val['id']));
                            $Photo = substr($val['poster'], 21);
                            @$DelPhoto = unlink('./' . $Photo);
                        }
                        $this->Common_Models->deleteData('activity', array('state' => "0"), array('endtime' => $va['endtime']));

                    }

                }
            }
            if (!empty($data)) {
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        }

    }

    /**
     * 单个活动信息
     * @param CheckActivityOne
     * @param 2016/11/15
     * @param 15:12
     */
    public function CheckActivityOne()
    {
        $id = $this->input->post('id', TRUE);
        if (!empty($this->input->post())) {

            $sql = "id,sponsor,activitytitle,introduction,activitytype,poster,city,actposition,starttime,endtime,stoptime,lng,lat,state,add_time";
            $check_info = $this->Common_Models->getDataOne('activity', $sql, array('id' => $id));

            $check_user = $this->Activity_Models->queryActivityOne($id);
            $check_info['enterpeople'] = $check_user;

            if (!empty($check_info)) {
                echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            } else {
                echo "网络错误！";
            }
        }

    }

    /**
     * 添加活动报名
     * @param AddActivity
     * @param 2016/11/15
     * @param 11:15
     */
    public function AddEnterUser()
    {
        $id = $this->input->post('id', TRUE);
        if (!empty($this->input->post())) {
            $data = array(
                'nickname' => $this->sponsor,
                'actid' => $id,
                'add_time' => time()
            );
            $AddUser = $this->Common_Models->insertData('participant', $data);
            if ($AddUser == "success") {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }

    /**
     * 取消活动报名
     * @param CancelEnter
     * http://119.29.143.48/remarry/Activity/CancelEnter
     * return post(昵称,“sponsor”)  post(id,“id”)'这个id是活动报名唯一的id'
     * @param 2016/11/15
     * @param 15:54
     */
    public function CancelEnter()
    {
        $id = $this->input->post('id', TRUE);
        if (!empty($this->input->post())) {
            try {
                if (empty($id))
                    throw new Exception('亲！ID不能为空哦！');
                if (empty($this->sponsor))
                    throw new Exception('亲！昵称不能为空哦！');
                $del = array(
                    'nickname' => $this->sponsor,
                    'actid' => $id
                );
                $delete = $this->Common_Models->deleteData('participant', $del);
                if ($delete) {
                    $result['status'] = "success";
                    echo json_encode($result);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     * 删除我活动
     * @param DelMyActivity
     * http://119.29.143.48/remarry/Activity/DelMyActivity
     * return post(活动ID,“id”)
     * @param 2016/11/16
     * @param 16:26
     */
    public function DelMyActivity()
    {
        $id = $this->input->post('id', TRUE);
        if (!empty($this->input->post())) {
            $delAct = $this->Common_Models->deleteData('activity', array('id' => $id));
            if ($delAct) {
                $delPar = $this->Common_Models->deleteData('participant', array('actid' => $id));
                if ($delPar) {
                    $result['status'] = "success";
                    echo json_encode($result);
                } else {
                    $result['status'] = "error";
                    echo json_encode($result);
                }
            }
        } else {
            $result['status'] = "亲！您提交失败o！";
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }
}
