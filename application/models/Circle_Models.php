<?php
/**
 * www.qinyejun.com
 *
 * Description:
 *
 * @package         www.qinyejun.com
 * @file            node_model.php
 * @author          QinYejun <qinyejun@me.com>
 * @copyright       Copyright (c) 2016, Wonghzx, Inc.
 * @since           Version 1.0
 * @time
 */
// ------------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class Circle_Models extends CI_Model
{

    private $circleInfo;

    private $circleReplyInfo;

    private $circleCommentInfo;

    private $circleLikeInfo;

    function __construct()
    {
        parent::__construct();

    }

    //------------------------------------//
    /**
     * 查询所有生活圈信息
     * @param CheckCircleAll
     * @param 2016/11/15
     * @param 16:44
     */
    public function queryCircle($num = null, $limit = null,$where = null)
    {
        if ($where == null) {

            $sql = " SELECT c.id,c.nickname,u.userid,c.location,u.photo,c.content,c.photourl,c.add_time FROM rem_circle AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname  ORDER BY c.add_time DESC LIMIT {$num},{$limit} ";//
            $this->circleInfo = $this->db->query($sql)->result_array();//生活圈信息

        } else {
            $sql = " SELECT c.id,c.nickname,u.userid,u.member,c.location,u.photo,us.age,us.height,c.content,c.photourl,c.add_time FROM rem_circle AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname LEFT JOIN rem_userdata AS us ON c.nickname = us.nickname WHERE c.id = {$where}";
            $this->circleInfo = $this->db->query($sql)->row_array();//生活圈信息
        }
        return $this->circleInfo;
    }

    /*
     * 评论信息
     */
    public function queryCircleComment($where = null)
    {
        if ($where == null) {

            $sql = " SELECT c.id,c.nickname,u.userid,u.photo,c.content,c.circleid,c.add_time FROM rem_circle_comment AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname ";
            $this->circleCommentInfo = $this->db->query($sql)->result_array();//评论信息
        } else {
            $sql = " SELECT c.id,c.nickname,us.nowlocal,u.userid,u.photo,c.content,c.circleid,c.add_time FROM rem_circle_comment AS c LEFT JOIN rem_user AS u ON c.nickname = u.nickname LEFT JOIN rem_userdata AS us ON c.nickname = us.nickname WHERE c.circleid = {$where} ";
            $this->circleCommentInfo = $this->db->query($sql)->result_array();//评论信息
        }
        return $this->circleCommentInfo;
    }

    /*
     * 评论回复信息
     */
    public function queryCircleReply($where = null)
    {
        if ($where == null) {

            $sql = " SELECT r.id,r.nickname,u.userid,r.targetname,r.content,r.commentid FROM rem_circle_reply AS r LEFT JOIN rem_user AS u ON r.nickname = u.nickname";
            $this->circleReplyInfo = $this->db->query($sql)->result_array();//评论回复信息

        } else {
            $sql = " SELECT r.id,r.nickname,u.userid,r.targetname,r.content,r.commentid FROM rem_circle_reply AS r LEFT JOIN rem_user AS u ON r.targetname = u.nickname";
            $this->circleReplyInfo = $this->db->query($sql)->result_array();//回复评论信息
        }
        return $this->circleReplyInfo;

    }

    /*
     * 点赞
     */
    public function queryCircleLike($where = null)
    {
        if ($where == null) {

            $this->circleLikeInfo = $this->db->select('nickname,circleid,add_time')
                ->order_by('add_time', 'ASC')
                ->get('like')
                ->result_array();
        } else {
            $this->circleLikeInfo = $this->db->select('nickname,add_time')
                ->where('circleid', $where)
                ->order_by('add_time', 'ASC')
                ->get('like')
                ->result_array();//点赞
        }

        return $this->circleLikeInfo;
    }

    //----------------------------------//


}

