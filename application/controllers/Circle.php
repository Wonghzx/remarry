<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Circle extends CI_Controller
{

    /**
     * Circle constructor.
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
    }

    /**
     * 添加我的生活圈
     * @param AddMyCircle
     * @param 2016/11/15
     * @param 16:44
     */
    public function AddMyCircle()
    {
        if (!empty($this->input->post())) {

            $add_info = $this->conn->insert('rem_circle', [
                'nickname' => $this->nickname,
                'content' => $this->input->post('content', TRUE),
                'photourl' => $this->input->post('photo', TRUE),
                'location' => $this->input->post('location', TRUE),
                'add_time' => time()
            ]);
            if ($add_info > 0) {
                $result['status'] = "success";
                print json_encode($result);
            } else {
                $result['status'] = "error";
                print json_encode($result);
            }
        }
    }

    /**
     * 写评论
     * @param WriteComment
     * @param 2016/11/15
     * @param 17:00
     */
    public function WriteComment()
    {
        if (!empty($this->input->post())) {
            $data = array(
                'nickname' => $this->nickname,//我的昵称
                'content' => $this->input->post('content', TRUE),//评论内容
                'circleid' => $this->input->post('circleid', TRUE),//朋友圈ID
                'add_time' => time()
            );
            if (!in_array('', $data)) {
                $add_info = $this->db->insert('circle_comment', $data);
                $id = $this->db->insert_id();
                if ($add_info > 0 AND $id > 0) {
                    $result['status'] = "success";
                    $result['id'] = (string)$id;
                    print json_encode($result);
                } else {
                    $result['status'] = "error";
                    print json_encode($result);
                }
            }
        }
    }

    /**
     * 回复评论
     * @param ReplyComment
     * @param 2016/11/15
     * @param 17:00
     */
    public function ReplyComment()
    {
        if (!empty($this->input->post())) {
            $data = array(
                'nickname' => $this->nickname,//我的昵称
                'targetname' => $this->input->post('targetname', TRUE),//目标昵称
                'content' => $this->input->post('content', TRUE),//评论内容
                'commentid' => $this->input->post('commentid', TRUE),//评论内容ID
                'circleid' => $this->input->post('circleid', TRUE),//朋友圈ID
                'add_time' => time()
            );
            $add_info = $this->db->insert('circle_reply', $data);
            if ($add_info > 0) {
                $result['status'] = "success";
                print json_encode($result);
            } else {
                $result['status'] = "error";
                print json_encode($result);
            }
        }
    }

    /**
     * 查询所有生活圈信息
     * @param CheckCircleAll
     * @param 2016/11/15
     * @param 16:44
     */
    public function CheckCircleAll()
    {
        $page = $this->input->post('page', TRUE);
        $limit = 10;
        $num = ($page - 1) * $limit;
        try {
            $this->db->cache_on();
            $sql = " SELECT c.id,c.nickname,u.userid,c.location,u.photo,c.content,c.photourl,c.add_time FROM rem_circle AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname  ORDER BY c.add_time DESC LIMIT {$num},{$limit} ";//
            $check_info = $this->db->query($sql)->result_array();//生活圈信息


            $sql = " SELECT c.id,c.nickname,u.userid,u.photo,c.content,c.circleid,c.add_time FROM rem_circle_comment AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname ";
            $check_comment = $this->db->query($sql)->result_array();//评论信息


            $sql = " SELECT r.id,r.nickname,u.userid,r.targetname,r.content,r.commentid FROM rem_circle_reply AS r LEFT JOIN rem_user AS u ON r.nickname = u.nickname";
            $check_reply = $this->db->query($sql)->result_array();//评论信息

            $check_like = $this->db->select('nickname,circleid,add_time')
                ->order_by('add_time', 'ASC')
                ->get('like')
                ->result_array();//点赞


            //----------------↓循环嵌套评论回复↓--------------//
            foreach ($check_comment as $item => $value) {
                $row = array();
                foreach ($check_reply as $it => $va) {
                    if ($value['id'] == $va['commentid']) {
                        $row[] = $va;
                    }
                }
                $check_comment[$item]['reply'] = $row;
            }
            //----------↓循环嵌套所有生活圈信息↓------------//
            foreach ($check_info as $item => $value) {
                $data = array();
                foreach ($check_like as $ite => $val) {
                    if ($value['id'] == $val['circleid']) {
                        $data[] = $val['nickname'];
                    }
                }
                $check_info[$item]['like'] = $data; //循环添加点赞
                $row = array();
                foreach ($check_comment as $i => $v) {
                    if ($value['id'] == $v['circleid']) {
                        $row[] = $v;
                    }
                }
                $check_info[$item]['comment'] = $row;
            }
            //------------------------------------------//
            if (!empty($check_info)) {
//                p($check_info);
                echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
            }
            $this->db->cache_off();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 单条生活圈信息
     * @param CheckCircleOne
     * @param 2016/11/15
     * @param 16:44
     */
    public function CheckCircleOne()
    {
        $id = $this->input->post('id', TRUE);
        if (!empty($this->input->post())) {
            try {

                $sql = " SELECT c.id,c.nickname,u.userid,u.member,c.location,u.photo,us.age,us.height,c.content,c.photourl,c.add_time FROM rem_circle AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname LEFT JOIN rem_userdata AS us ON c.nickname = us.nickname WHERE c.id = {$id}";
                $check_info = $this->db->query($sql)->row_array();//生活圈信息


                $sql = " SELECT c.id,c.nickname,us.nowlocal,u.userid,u.photo,c.content,c.circleid,c.add_time FROM rem_circle_comment AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname LEFT JOIN rem_userdata AS us ON c.nickname = us.nickname WHERE c.circleid = {$id} ";
                $check_comment = $this->db->query($sql)->result_array();//评论信息

                $sql = " SELECT r.id,r.nickname,u.userid,r.targetname,r.content,r.commentid FROM rem_circle_reply AS r LEFT JOIN rem_user AS u ON r.targetname = u.nickname";
                $check_reply = $this->db->query($sql)->result_array();//回复评论信息

                $check_like = $this->db->select('nickname,add_time')
                    ->where('circleid', $id)
                    ->order_by('add_time', 'ASC')
                    ->get('like')
                    ->result_array();//点赞
                $check_likes = array_column($check_like, 'nickname');
                foreach ($check_comment as $item => $value) {
                    $row = array();
                    foreach ($check_reply as $it => $va) {
                        if ($value['id'] == $va['commentid']) {
                            $row[] = $va;
                        }
                    }
                    $check_comment[$item]['reply'] = $row;
                }
                $check_info['like'] = $check_likes;
                $check_info['comment'] = $check_comment;

                if (!empty($check_info)) {
                    echo json_encode($check_info, JSON_UNESCAPED_UNICODE);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

    }

    /**
     * 删除生活圈信息
     * @param DelCircle
     * @param 2016/11/15
     * @param 16:44
     */
    public function DelCircle()
    {
        $id = (int)$this->input->post('id', TRUE);
        $photo_url = $this->db->select('photourl')->where('id', $id)->get('circle')->row_array();
        $url = explode(',', $photo_url['photourl']);
        foreach ($url as $item => $value) {
            $url_p = substr($value, 29);
            @unlink('./' . $url_p);
        }
        if (!empty($id)) {
            $DelCir = $this->db->delete('circle', array('id' => $id));
            if ($DelCir) {
                $DelComm = $this->db->delete('circle_comment', array('circleid' => $id));
                if ($DelComm) {
                    $DelReply = $this->db->delete('circle_reply', array('circleid' => $id));
                    if ($DelReply) {
                        $result['status'] = "success";
                        echo json_encode($result);
                    }
                }
            }
        } else {
            return false;
        }


    }

    /**
     * 点赞
     * @param CircleLike
     * @param 2016/11/15
     * @param 17:10
     */
    public function CircleLike()
    {
        $id = $this->input->post('id', TRUE);
        if (!empty($this->input->post())) {
            $data = array(
                'nickname' => $this->nickname,
                'circleid' => $id,
                'add_time' => time()
            );
            $this->db->insert('like', $data);
        }
    }


    /**
     * 取消点赞
     * @param CancelLike
     * @param 2016/11/16
     * @param 11:49
     */

    public function CancelLike()
    {
        $id = $this->input->post('id', TRUE);
        if (!empty($this->input->post())) {
            $win = $this->db->where('nickname ', $this->nickname)->delete('like', array('circleid' => $id));
            if ($win) {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
            }
        }
    }
}
