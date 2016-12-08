<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersonalData extends CI_Controller
{

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
        session_start();
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
            $check_info = $this->db->select('age,height,education,weight,working,income,kid,child,alcohol,shape,marriage')
                ->where('nickname', $this->nickname)
                ->get('mymates')
                ->row_array();
            if ($check_info) {
                print json_encode($check_info);
            } else {
                $result['status'] = "error";
                print json_encode($result);
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
            $PhotoUlr = $this->db->select("photo")
                ->where('nickname =', $this->nickname)
                ->get('user')
                ->row_array();
            $Photo = substr($PhotoUlr['photo'], 29);
            @$DelPhoto = unlink('./' . $Photo);

            $data = array(
                'photo' => $photo
            );
            $UpdatePhoto = $this->db->where('nickname =', $this->nickname)
                ->update('user', $data);
            if ($UpdatePhoto) {
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
        $photo = $this->input->post('photourl', TRUE);
        $photo_url = explode(',', $photo);
        foreach ($photo_url as $key => $item) {
            if (!empty($item)) {
                $data = array(
                    'nickname' => $this->nickname,
                    'photourl' => $item,
                    'add_time' => time()
                );
                $add_photo = $this->db->insert('useralbum', $data);
            }
        }
        if (@$add_photo > 0) {
            $result['status'] = 'success';
            print json_encode($result);
        } else {
            $result['status'] = 'error';
            print json_encode($result);
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
            $del = $this->db->where('nickname =', $this->nickname)->delete('useralbum', $data);
            if ($del) {
                $Photo = substr($url, 29);
                @unlink('./' . $Photo);
                $result['status'] = 'success';
                print json_encode($result);
            } else {
                $result['status'] = 'error';
                print json_encode($result);
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
            $check_photo = $this->db->select('photo')->where($row, $this->nickname)->get('user')->row_array();
            if (is_array($check_photo)) {
                print json_encode($check_photo);
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
        $userid = $this->input->post('userid', TRUE);
        $targetid = $this->input->post('targetid', TRUE);

      $sql = " SELECT id FROM rem_mychat WHERE nickname IN ('{$userid}','{$targetid}') AND  targetname IN ('{$userid}','{$targetid}') ";
        $check_user = $this->db->query($sql)->row_array();
        if (empty($check_user)) {
            $add = $this->db->insert('mychat', array('nickname' => $userid, 'targetname' => $targetid, 'add_time' => time()));
            if ($add) {
                $check = $this->db->select('targetname')->where('nickname =', $userid)->get('mychat')->result_array();
                $json = json_encode($check);
                session('json', $json);
                @file_put_contents('./log.log', session('json'), FILE_APPEND);
                $result['status'] = "success";
                print json_encode($result);
            }
        }
    }
}
