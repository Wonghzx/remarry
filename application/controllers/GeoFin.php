<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GeoFin extends CI_Controller
{
   private $geohash;
    function __construct()
    {
        parent::__construct();
        $this->geohash = new Geohash();
        $this->load->model('GeoFin_Models');
    }

    /**
     * 附近人 筛选功能
     * @param GeoHah
     * @param 2016/11/11
     * @param 12:00
     */
    public function GeoHah()
    {
        $sex = $this->input->post('sex', TRUE);
        $age = $this->input->post('age', TRUE);
        $place = $this->input->post('place', TRUE);
        $lat = $this->input->post('lat', TRUE);
        $lng = $this->input->post('lng', TRUE);
        if (!empty($this->input->post())) {
            $age_list = explode('-', $age);
            $list_age = array_map('intval', $age_list);

            $where = "";
            if (!empty($sex)) {
                $where[] = " sex = '{$sex}' ";
            }
            if (!empty($age)) {
                $where[] = " age >= {$list_age[0]} AND age <= {$list_age[1]} ";
            }
            if (!empty($place)) {
                $where[] = " province = '{$place}省' ";
            }

            $and = "";
            if (!empty($where)) {
                $where = join(" AND ", $where);
                $and = " AND ";
            }

            $hash = $this->geohash->encode($lat, $lng);
            $prefix = substr($hash, 0, 1);//截取我的经纬度前面六位
            $neighbors = $this->geohash->neighbors($prefix);//取出相邻八个区域
            array_push($neighbors, $prefix);
            $data = array();

            foreach ($neighbors as $key => $value) {

                $check = $this->GeoFin_Models->queryNearbyPeople($where, $and, $value);
                foreach ($check as $ke => $va) {
                    $data[] = $va;
                    $lat_it_ud = strval(getDistance($lat, $lng, $va['lat'], $va['lng'])); // 纬度   经度
                    $data[$ke]['geohash'] = isset($lat_it_ud) ? $lat_it_ud : strval(100);
                }

            }

            if (!empty($data)) {
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }
//            else {
//                $check = $this->GeoFin_Models->queryNearbyPeople();
//                foreach ($check as $ke => $va) {
//                    $lat_it_ud = strval(getDistance($lat, $lng, $va['lat'], $va['lng'])); // 纬度   经度
//                    $check[$ke]['geohash'] = isset($lat_it_ud) ? $lat_it_ud : strval(100);
//                }
//                echo json_encode($check, JSON_UNESCAPED_UNICODE);
//            }
        }
    }
}
