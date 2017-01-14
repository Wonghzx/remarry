<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Circle extends CI_Controller
{

    private $nickname;

    /**
     * Circle constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->nickname = $this->input->post('nickname', TRUE);
        $this->load->model('Circle_Models');
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
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
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
                $add_info = $this->Common_Models->insertData('circle_comment', $data);
                $id = $this->db->insert_id();
                if ($add_info == "success" AND $id > 0) {

                    $result['status'] = "success";
                    $result['id'] = (string)$id;
                    echo json_encode($result);

                    $this->load->model('Grade_Models');
                    $grade = $this->Grade_Models->gradeQuery($this->nickname);

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
                    $this->Common_Models->updateData(array('nickname' => $this->nickname), 'grade', $data);

                } else {
                    $result['status'] = "error";
                    echo json_encode($result);
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
            $add_info = $this->Common_Models->insertData('circle_reply', $data);
            if ($add_info == "success") {
                $result['status'] = "success";
                echo json_encode($result);
            } else {
                $result['status'] = "error";
                echo json_encode($result);
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

            $check_info = $this->Circle_Models->queryCircle($num, $limit);//生活圈信息

            $check_comment = $this->Circle_Models->queryCircleComment();//评论信息

            $check_reply = $this->Circle_Models->queryCircleReply();//评论回复信息

            $check_like = $this->Circle_Models->queryCircleLike();//评论回复信息

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


                $check_info = $this->Circle_Models->queryCircle(null, null, $id);//生活圈信息

                $check_comment = $this->Circle_Models->queryCircleComment($id);//评论信息

                $check_reply = $this->Circle_Models->queryCircleReply($id);//评论回复信息

                $check_like = $this->Circle_Models->queryCircleLike($id);//评论回复信息

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
        $photo_url = $this->Common_Models->getDataOne('circle', 'photourl', array('id' => $id));
        $url = explode(',', $photo_url['photourl']);
        foreach ($url as $item => $value) {
            $url_p = substr($value, 21);
            @unlink('./' . $url_p);
        }
        if (!empty($id)) {
            $delCir = $this->Common_Models->deleteData('circle', array('id' => $id));
            if ($delCir) {
                $delComm = $this->Common_Models->deleteData('circle_comment', array('circleid' => $id));
                if ($delComm) {
                    $delReply = $this->Common_Models->deleteData('circle_reply', array('circleid' => $id));
                    if ($delReply) {
                        $delLike = $this->Common_Models->deleteData('like', array('circleid' => $id));
                        if ($delLike) {
                            $result['status'] = "success";
                            echo json_encode($result);
                        }
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
            $this->Common_Models->insertData('like', $data);
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
            $win = $this->Common_Models->deleteData('like', array('circleid' => $id), array('nickname' => $this->nickname));
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
