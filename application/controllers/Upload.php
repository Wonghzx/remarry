<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    /**
     *上传图片
     */
    public function file()
    {

        $target_path = "upload/";//接收文件目录

        $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 5;
        $new_str = substr(str_shuffle($listAlpha), 0, $length); //打乱字符串

        $date = date('Ymdhis') . '_' . $new_str;//得到当前时间,如;20070705163148
        @$fileName = $_FILES['uploadedfile']['name'];//得到上传文件的名字
        $name = explode('.', $fileName);//将文件名以'.'分割得到后缀名,得到一个数组
        @$newPath = $date . '.' . $name[1];//得到一个新的文件为'20070705163148.jpg',即新的路径
        $target_path = $target_path . $newPath;


        if (@move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {

            $result = array(
                "status" => "success",
                "path" => $target_path
            );
            echo json_encode($result);

        } else {
            echo "There was an error uploading the file, please try again!" . @$_FILES['uploadedfile']['error'];
        }
    }

    /**
     * 上传app包
     */
    public function Apk()
    {
        $target_path = "Apk/";//接收文件目录

        @$fileName = $_FILES['ApkFile']['name'];//得到上传文件的名字
        $target_path = $target_path . $fileName;


        if (@move_uploaded_file($_FILES['ApkFile']['tmp_name'], $target_path)) {
            $url = $this->Common_Models->getDataOne('version', 'url', array('id' => 1));
            $url_p = substr($url, 15);
            @unlink('./' . $url_p);
            $data = array(
                'versionname' => $this->input->post('versionname', TRUE),
                'content' => $this->input->post('content', TRUE),
                'url' => "http://da.cntywl.com/Apk/" . $target_path
            );
            $add = $this->Common_Models->insertData('version', $data);
            if ($add > 0) {
                echo "上传成功";
            } else {
                echo "上传失败";
            }

        } else {
            echo "There was an error uploading the file, please try again!" . @$_FILES['uploadedfile']['error'];
        }

        $this->load->view('index');
    }

    public function uploadApk()
    {

        $this->load->view('uploadApk');
    }
}
