<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Member.php";

class Test extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->tarname = '148075130997598';
        $this->nickname = "148109329079888";
//        $this->sex = $this->input->post('sex', TRUE);
    }

    public function index()
    {

//        $sql = " SELECT endtime,state FROM rem_activity  ORDER BY add_time DESC";
//        $check_info = $this->db->query($sql)->result_array();
//        foreach ($check_info as $i => $v) {
//            $t = 3600 * 24 * 7 + $v['endtime'];
//            echo $t . "<br />";
//
//            if (time() == $t || time() > $t) {
//                $this->db->delete('activity',array('state'=>"0"));
//                echo "123" . "<br />";
//            } else {
//                echo "12" . "<br />";
//            }
//        }
//        $where = "u.id,u.username,u.nickname,u.autstate,u.photo,us.sex,us.age,us.height,us.weight,us.education,us.constellation,us.birthday,us.occupation,us.working,us.income,us.housing,us.kid,us.child,us.province,us.place,us.car,us.alcohol,us.smoke,us.shape,us.nation,us.marry,us.marriage,us.monologue";
//        $check_info = $this->db->select($where)
//            ->from('user as u')
//            ->join('userdata as us', 'u.nickname = us.nickname', 'left')
//            ->where('u.nickname', 'fox')
//            ->get('user')
//            ->row_array();
//        $sql = "SELECT nickname,photourl FROM rem_useralbum  WHERE nickname = 'fox' ";
//        $PhotoUrl = $this->db->query($sql)->result_array();
//
//       $mymates = $this->db->select('age,height,income,education,weight,marriage,shape,working,child,kid,alcohol')->where('nickname','fox')->get('mymates')->row_array();
//
//
//
//        $check_info['photourl'] = $PhotoUrl;
//        $check_info['mymates'] = $mymates;
//        if ($check_info) {
//            p($check_info);
//            print json_encode($check_info, JSON_UNESCAPED_UNICODE);
//        } else {
//            $result['status'] = "error";
//            print json_encode($result);
//        }
//        $data = array(
//            'nickname' => $this->nickname,
//            'tarname' => $this->tarname
//        );
//        $asd = $this->db->select('state')->where($data)->get('friends')->row_array();

//        $sql = "SELECT f.nickname,f.tarname,u.photo,u.userid,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.nickname = u.nickname WHERE f.state = '1' AND f.tarname = '{$this->nickname}' OR f.nickname = '{$this->nickname}' ";
//        $sad = $this->db->query($sql)->result_array();
//       p($sad);
//    $this->load->view('ceshi');

//        $sql = "SELECT f.nickname,f.tarname,u.photo,u.userid,f.id FROM rem_friends AS f LEFT JOIN rem_user AS u ON f.nickname = u.nickname  WHERE (f.tarname = 'fox' OR f.nickname = 'fox') AND f.state = '1'";
//        $show_like = $this->db->query($sql)->result_array();
//        echo $this->db->last_query();
//        p($show_like);
        $row = new ServerAPI('bmdehs6pbqj2s', 'qmJhoSdpc95J');
        $a = $row->userCheckOnline('148124723519687');
        p($a);
    }

}
