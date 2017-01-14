<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersonalData extends CI_Controller
{

    private $nickname;

    /*
     * PersonalData constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->nickname = $this->input->post('nickname', TRUE);
        $this->conn = new medoo([
            'database_type' => 'mysql',
            'database_name' => 'remarry',
            'server' => '10.66.169.101',
            'username' => 'wong',
            'password' => 'huangzhixue123',
            'charset' => 'utf8',
        ]);
        $this->load->model('PersonalData_Models');
    }

    /**
     * 添加个人资料
     * @param AddPersonaData
     * @param 2016/11/11
     * @param 14:17
     */
    public function AddPersonaData()
    {
        if (!empty($this->input->post())) {
            if (!empty($this->input->post('monologue', TRUE))) {
                $data = array(
                    'monologue' => $this->input->post('monologue', TRUE)//独白
                );
                $this->db->where('nickname', $this->nickname)->update('userdata', $data);
            }
            if (empty($this->input->post('monologue', TRUE))) {
                if (empty($this->input->post('birthday', TRUE))) {
                    $age = "0";
                } else {
                    $age = date('Y.m.d') - $this->input->post('birthday', TRUE);
                }
                $update = $this->conn->update('rem_userdata', [
                    'age' => $age,//年龄
                    'sex' => $this->input->post('sex', TRUE),//性别
                    'height' => $this->input->post('height', TRUE),//身高
                    'weight' => $this->input->post('weight', TRUE),//重量
                    'education' => $this->input->post('education', TRUE),//学历
                    'constellation' => $this->input->post('constellation', TRUE),//星座
                    'birthday' => $this->input->post('birthday', TRUE),//生日
                    'occupation' => $this->input->post('occupation', TRUE),//职业
                    'working' => $this->input->post('working', TRUE),//工作地区
                    'income' => $this->input->post('income', TRUE),//月收入
                    'housing' => $this->input->post('housing', TRUE),//住宅情况
                    'kid' => $this->input->post('kid', TRUE),//是否想要小孩
                    'child' => $this->input->post('child', TRUE),//有没有小孩
                    'place' => $this->input->post('place', TRUE),//籍贯
                    'car' => $this->input->post('car', TRUE),//买车情况
                    'alcohol' => $this->input->post('alcohol', TRUE),//是否喝酒
                    'smoke' => $this->input->post('smoke', TRUE),//是否吸烟
                    'shape' => $this->input->post('shape', TRUE),//体型
                    'marry' => $this->input->post('marry', TRUE),//何时结婚
                    'marriage' => $this->input->post('marriage', TRUE),//婚姻状况
                    'wechat' => $this->input->post('wechat', TRUE),//微信
                    'qq' => $this->input->post('qq', TRUE)//qq
                ], ['nickname' => $this->nickname]);
                if ($update) {
                    $result['status'] = "success";
                    echo json_encode($result);
                } else {
                    $result['status'] = "error";
                    echo json_encode($result);
                }
            }
        }
    }

    /**
     * 添加个人择偶条件
     * @param AddUserMate
     * @param 2016/11/11
     * @param 14:17
     */
    public function AddUserMate()
    {
        if (!empty($this->input->post())) {
            $UpdateMate = $this->conn->update('rem_mymates', [
                'age' => $this->input->post('age', TRUE),//年龄
                'height' => $this->input->post('height', TRUE),//身高
                'education' => $this->input->post('education', TRUE),//学历
                'weight' => $this->input->post('weight', TRUE),//体重
                'working' => $this->input->post('working', TRUE),//工作地区
                'income' => $this->input->post('income', TRUE),//月收入
                'kid' => $this->input->post('kid', TRUE),//是否想要小孩
                'child' => $this->input->post('child', TRUE),//有没有小孩
                'alcohol' => $this->input->post('alcohol', TRUE),//是否喝酒
                'shape' => $this->input->post('shape', TRUE),//体型
                'marriage' => $this->input->post('marriage', TRUE),//婚姻状况
            ], ['nickname' => $this->nickname]);
            if ($UpdateMate) {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }

    /**
     * 获取择偶信息
     * @param CheckUserMate
     * @param 2016/11/14
     * @param 15:48
     */
    public function CheckUserMate()
    {
        if (!empty($this->input->post())) {

            $sql = "age,height,education,weight,working,income,kid,child,alcohol,shape,marriage";
            $check_info = $this->Common_Models->getDataOne('mymates', $sql, array('nickname' => $this->nickname));
            if ($check_info) {
                echo json_encode($check_info);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }

    /**
     * 更新个人头像
     * @param UpdatePhoto
     * @param 2016/11/11
     * @param 15:10
     */
    public function UpdatePhoto()
    {
        $photo = $this->input->post('url', TRUE);

        if (!empty($photo)) {

            $PhotoUlr = $this->Common_Models->getDataOne('user', 'photo', array('nickname' => $this->nickname));
            $Photo = substr($PhotoUlr['photo'], 21);
            @$DelPhoto = unlink('./' . $Photo);

            $data = array(
                'photo' => $photo
            );

            $UpdatePhoto = $this->Common_Models->updateData(array('nickname' => $this->nickname), 'user', $data);
            if ($UpdatePhoto == "success") {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }

        }
    }

    /**
     * 上传个人图片
     * @param AddPersonaData
     * @param 2016/11/12
     * @param 14:31
     */
    public function AddUserPhoto()
    {
        $add_photo = null;
        $photo = $this->input->post('photourl', TRUE);
        $photo_url = explode(',', $photo);
        foreach ($photo_url as $key => $item) {
            if (!empty($item)) {
                $data = array(
                    'nickname' => $this->nickname,
                    'photourl' => $item,
                    'add_time' => time()
                );
                $add_photo = $this->Common_Models->insertData('useralbum', $data);
            }
        }
        if ($add_photo == "success") {
            $result['status'] = 'success';
            echo json_encode($result);
        } else {
            $result['status'] = 'error';
            echo json_encode($result);
        }
    }

    /**
     * 删除个人相册
     * @param DelUserPhoto
     * @param 2016/11/12
     * @param 14:31
     */
    public function DelUserPhoto()
    {
        $url = $this->input->post('url', TRUE);
        if (!empty($this->input->post())) {
            $data = array(
                'photourl' => $url
            );
            $del = $this->Common_Models->deleteData('useralbum', $data, array('nickname' => $this->nickname));
            $Photo = substr($url, 21);
            @unlink('./' . $Photo);
            if ($del == "success") {
                $result['status'] = 'success';
                echo json_encode($result);
            } else {
                $result['status'] = 'error';
                echo json_encode($result);
            }
        }
    }

    /**
     * 我的头像
     * @param MyPhoto
     * @param 2016/12/6
     * @param 9:23
     */
    public function MyPhoto()
    {
        try {
            if (empty($this->nickname))
                throw new Exception('用户名不能为空!');

            $row = "";
            if (is_numeric($this->nickname)) {
                $row = "username";
            } else {
                $row = "nickname";
            }
            $check_photo = $this->Common_Models->getDataOne('user', 'photo', array($row => $this->nickname));
            if (is_array($check_photo)) {
                echo json_encode($check_photo);
            } else {
                $result['status'] = NULL;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 聊天好友
     * @param MyChat
     * @param 2016/12/6
     * @param 16:57
     */
    public function MyChat()
    {
        $userId = $this->input->post('userid', TRUE);
        $targetId = $this->input->post('targetid', TRUE);
        $check_user = $this->PersonalData_Models->MyChat($userId, $targetId);
        if (empty($check_user)) {
            $add = $this->Common_Models->insertData('mychat',array('nickname' => $userId, 'targetname' => $targetId, 'add_time' => time()));
            if ($add) {
                $result['status'] = "success";
                echo json_encode($result);
            }
        }
    }
}
