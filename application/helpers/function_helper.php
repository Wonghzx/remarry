<?php

function p($gg)
{
    echo '<pre>';
    print_r($gg);
    echo '</pre>';
}

function jump($msg, $url = '')
{   //验证跳转

    if ($url != '') {
        exit('<script>alert("' . $msg . '");location.href="' . $url . '"</script>');
    } else {
        exit('<script>alert("' . $msg . '");history.go(-1);</script>');
    }
}


function get_one($sql)
{   //查一条
    $query = mysql_query($sql);
    if ($query) {
        $info = mysql_fetch_assoc($query);
    } else {
        return 'SQL语句，错误信息为：' . mysql_error(); //mysql错误信息
    }
    return $info;
}

/*
 *  获取多条记录
 *
 * @param  string $sql   SQL查询语句
 * @return  
 */
function get_all($sql)
{
    $query = mysql_query($sql);
    $info = array();
    while ($data = @mysql_fetch_assoc($query)) {
        $info[] = $data;
    }
    return $info;
}

/**
 * 删除记录函数
 * @param string $table 数据表名（如：'admin'）
 * @param string $where 条件（格式：'主键id=2'）
 * @param author lin teacher
 * @return boolean
 */
function del($table, $where)
{
    $query = mysql_query("delete from $table where $where");
    if ($query) {
        if (mysql_affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return '请检查SQL语句，错误信息为：' . mysql_error();  //mysql错误信息
    }
}


/**
 * 添加记录函数
 * @param string $table 数据表名（如：'admin'）
 * @param array $data 一维关联数组
 *（格式：array('字段名'=>值,'字段名'=>值)）
 * @param author lin teacher
 * @return boolean
 */
function insert($table, $data)
{
    $key_arr = array_keys($data);  // 提取数组的下标
    $val_arr = array_values($data); // 提取数组的值
    $key_str = implode(',', $key_arr);
    $val_str = "'" . implode("','", $val_arr) . "'";
    $query = mysql_query("insert into $table ($key_str) values ($val_str)");
    if ($query) {
        if (mysql_insert_id() > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return '请检查SQL语句，错误信息为：' . mysql_error();  //mysql错误信息
    }
}

/**
 * 更新记录函数
 * @param string $table 数据表名（如：'admin'）
 * @param array $data 一维关联数组
 *(格式：array('字段名'=>值,'字段名'=>值)）
 * @param string $where 条件（格式：'主键id=2'）
 * @param author lin teacher
 * @return boolean
 */
function update($table, $data, $where)
{
    $str = '';
    foreach ($data as $key => $value) {
        $str .= $key . "='" . $value . "',";
    }
    $str = rtrim($str, ',');
    $query = mysql_query("UPDATE $table SET $str WHERE $where");
    if ($query) {
        if (mysql_affected_rows() > 0) {
            return true;
        } else {
            return false; //操作的记录不成功，不代表sql语句错误
        }
    } else {
        return '请检查SQL语句，错误信息为：' . mysql_error();  //mysql错误信息
    }
}

/**
 * 获得用户的真实IP地址（服务器）
 *
 * @return  string
 */

function real_ip()
{
    static $realip = NULL;

    if ($realip !== NULL) {
        return $realip;
    }

    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip) {
                $ip = trim($ip);

                if ($ip != 'unknown') {
                    $realip = $ip;

                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $realip;
}

/**
 *  验证管理员权限
 */
function power()
{
    if (isset($_COOKIE['power']) && $_COOKIE['power'] == 0) {
        jump('亲，你没有权限哦！');
    }
}


/**
 * 字符截取（对中文、英文都可以进行截取）
 * @param string $string 字符串
 * @param int $start 字符串截取开始位置
 * @param int $length 字符串截取长度(多少个中文、英文)
 * @param string $charset 字符串编码
 * @param string $dot 截取操作发生时，在被截取字符串最后边增加的字符串
 * @param author lin teacher
 * @return string
 */
function str_cut(&$string, $start, $length, $charset = "utf-8", $dot = '...')
{
    if (function_exists('mb_substr')) {  // mb_  扩展（php.ini 开启扩展）  mb_substr 截取字符
        if (mb_strlen($string, $charset) > $length) {//mb_strlen 按字符获取长度
            return mb_substr($string, $start, $length, $charset) . $dot;
        }
        return mb_substr($string, $start, $length, $charset);//按字符截取字符串

    } else if (function_exists('iconv_substr')) {
        if (iconv_strlen($string, $charset) > $length) {
            return iconv_substr($string, $start, $length, $charset) . $dot;
        }
        return iconv_substr($string, $start, $length, $charset);
    }

    $charset = strtolower($charset);  //转小写
    switch ($charset) {
        case "utf-8" :  //  ASCII码
            preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $ar);
            if (func_num_args() >= 3) { //func_num_args()  返回函数的参数个数
                if (count($ar[0]) > $length) {
                    return join("", array_slice($ar[0], $start, $length)) . $dot;
                }
                return join("", array_slice($ar[0], $start, $length));
            } else {
                return join("", array_slice($ar[0], $start));//join()=>implode()
            }
            break;
        default:
            $start = $start * 2;
            $length = $length * 2;
            $strlen = strlen($string);
            for ($i = 0; $i < $strlen; $i++) {
                if ($i >= $start && $i < ($start + $length)) {
                    if (ord(substr($string, $i, 1)) > 129) $tmpstr .= substr($string, $i, 2);
                    else $tmpstr .= substr($string, $i, 1);
                }
                if (ord(substr($string, $i, 1)) > 129) $i++; //返回字符的 ASCII 码值
            }
            if (strlen($tmpstr) < $strlen) $tmpstr .= $dot;

            return $tmpstr;
    }
}

/**
 * 分页函数
 * @param int $pn 页码
 * @param int $total 总记录数
 * @param int $limit 每页显示的记录数
 * @param int $size 规定的奇数页码
 * @param string $class div的class名
 * @param author lin teacher
 * @return string
 */
function page($pn, $total, $limit, $size = 5, $class = 'yellow')
{

    $url = $_SERVER['PHP_SELF'] . '?';
    foreach ($_GET as $key => $value) {
        if ($key == 'pn') {
            continue;
        }
        $url .= $key . '=' . $value . '&';
    }

    $total_page = ceil($total / $limit);  //ceil(3.5)=>4  ceil(3.2) =>4
    //分页：每页显示2条，总页数：总条数/2
    //当前页码：$_GET['pn']

    $str = '<div class="' . $class . '"> <span>总共有 ' . $total . ' 条记录</span>';
    if ($pn > 1 && $pn <= $total_page) {
        $str .= '<a href="' . $url . 'pn=1">第一页</a>';
        $str .= '<a href="' . $url . 'pn=' . ($pn - 1) . '">上一页</a>';
    } else {
        $str .= '<span class="disabled">第一页</span>';
        $str .= '<span class="disabled">上一页</span>';
    }

    if ($pn < ceil($size / 2)) {       //第1种情况 [1] 2 3 4 5
        $begin = 1;
        $end = $size > $total_page ? $total_page : $size;  //谁小就取谁
    } else if ($pn > $total_page - floor($size / 2)) {//第3种情况  6 7 8 [9] 10
        $begin = $total_page - $size + 1 <= 0 ? 1 : $total_page - $size + 1;
        $end = $total_page;
    } else {           //第2种情况    2 3 [4] 5 6     3 4 [5] 6 7
        $begin = $pn - floor($size / 2);    //floor(2.5)=2   floor(1.5)=1
        $end = $pn + floor($size / 2);
    }

    //循环显示页码
    for ($i = $begin; $i <= $end; $i++) {
        if ($i == $pn) {
            $str .= '<span class="current">' . $i . '</span>';
        } else {
            $str .= '<a href="' . $url . 'pn=' . $i . '">' . $i . '</a>';
        }
    }

    if ($pn < $total_page && $pn >= 1) {
        $str .= '<a href="' . $url . 'pn=' . ($pn + 1) . '">下一页</a>';
        $str .= '<a href="' . $url . 'pn=' . $total_page . '">最后一页</a>';
    } else {
        $str .= '<span class="disabled">下一页</span>';
        $str .= '<span class="disabled">最后一页</span>';
    }
    $str .= '</div>';

    return $str;
}


/**
 * 文件上传
 *
 * @param  string $path 保存的目录
 * @param  string $size 文件限制大小
 * @param  string $oldimg 旧图片地址
 * @return
 */
function upload_file($path = './uploads/', $size = 2097152, $oldimg = '')
{

//考虑photo(file的名字)
//文件大小判断
//考虑文件保存的目录
//记得重新命名文件的名字（年月日时分秒+5位随机数+后缀名）

    //-- 得到数组下标
    $key_arr = array_keys($_FILES);
    $key = $key_arr[0];  //photo

    // --文件上传
    if ($_FILES[$key]['error'] != 4) {  // 如果有选择文件

        //考虑内容：网络是否有问题，是否有选择文件，文件大小（提示）、类型限制、文件名（不能重复）
        if ($_FILES[$key]['error'] == 3) { //错误：网络中断（线上会出现）
            echo '<script>alert("网络出错！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }

        if ($_FILES[$key]['size'] > $size) { //大小超过隐藏域MAX_FILE_SIZE限制的大小
            echo '<script>alert("文件大小不能超过' . fsize($size) . '！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }

        //定义新的名字
        $fname = $_FILES[$key]['name'];  //php03.rar  要改名
        $extend = pathinfo($fname, PATHINFO_EXTENSION);  //jpg   //pathinfo_extension

        //图片：gif/png/jpg/jpeg
        if (!in_array($extend, array('gif', 'png', 'jpg', 'jpeg'))) {
            echo '<script>alert("请上传图片类型的文件！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }

        //文件名$fname，要改名：时间格式+后缀名   mt_rand()  生成随机数（保证图片名不一样）
        $new_name = date('YmdHis') . mt_rand(10000, 99999) . '.' . $extend;

        if (!file_exists($path)) {  //如果保存的目录不存在，要帮他创建
            mkdir($path, 0777);
        }

        //研究：模拟表单提交
        if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
            // 成功上传头像
            //copy($_FILES[$key]['tmp_name'],'./uploads/'.$fname);
            move_uploaded_file($_FILES[$key]['tmp_name'], $path . $new_name);

            // 删除旧图片
            if ($oldimg != '') {
                @unlink($oldimg);   //$oldimg 旧图片的路径
            }

        } else {
            echo '<script>alert("非法上传！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }
        //  $path =>  ./uploads/   ../uploads/
        // $path = str_replace('../', '', $path);
        // $path = str_replace('./', '', $path);

        $path = str_replace(array('../', './'), '', $path);
        return $path . $new_name;//图片路径
    } else {
        // $oldimg = str_replace('../', '', $oldimg);
        // $oldimg = str_replace('./', '', $oldimg);

        $oldimg = str_replace(array('../', './'), '', $oldimg);
        return $oldimg;  //返回原图的地址
    }

}

function getDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6378138; //近似地球半径米
    // 转换为弧度
    $lat1 = ($lat1 * pi()) / 180;
    $lng1 = ($lng1 * pi()) / 180;
    $lat2 = ($lat2 * pi()) / 180;
    $lng2 = ($lng2 * pi()) / 180;
    // 使用半正矢公式  用尺规来计算
    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;
    return round($calculatedDistance);
}


/**
 * [uploads 文件上传函数]
 * @param [string] $name [表单域的name名]
 * @param [string] $catalog [文件保存的路径]
 * @param array $type [允许上传的文件类型]
 * @param integer $size [允许上传的文件大小]
 * @return [array] [error 1 上传失败 2 上传成功]
 */
function uploads($name, $catalog, $type = array('jpg', 'jpeg', 'gif', 'png'), $size = 2097152)//2097152
{
    $status = $_FILES[$name]['error'];
    if ($status > 0) {
        switch ($status) {
            case 1:
                $res['msg'] = "文件上传超过最大值2M";
                $res['err'] = 1;
                return $res;
                break;
            case 2:
                $res['msg'] = "文件上传超过MAX_FILE_SIZE大小";
                $res['err'] = 1;
                return $res;
                break;
            case 3:
                $res['msg'] = "文件上传失败";
                $res['err'] = 1;
                return $res;
                break;
            case 4:
                $res['msg'] = '请选择文件';
                $res['err'] = 1;
                return $res;
                break;
            default:
                break;
        }
    }
    if ($_FILES[$name]['size'] > $size) {
        $res['msg'] = '上传文件超出指定大小';
        $res['err'] = 1;
        return $res;
    }
    $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, $type)) {
        $res['msg'] = '请上传指定的文件类型';
        $res['err'] = 1;
        return $res;
    }
//第一种做法
    $catalog = rtrim($catalog, '/');
    $dir = $catalog;
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    do {
        $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 5;
        $new_str = substr(str_shuffle($listAlpha), 0, $length); //打乱字符串
        $file = date('Ymdhis') . '_' . $new_str;//mt_rand(1000, 9999);
        $filename = $file . '.' . $ext;
        $newname = $dir . '/' . $filename;
    } while (is_file($dir . '/' . $filename));
    move_uploaded_file($_FILES[$name]['tmp_name'], $dir . '/' . $filename);
    $res['msg'] = '文件上传成功';
    $res['err'] = 2;
    $res['filename'] = "upload/".$filename;
    $res['name'] = $filename;
    return $res;
}

/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function C($name=null, $value=null,$default=null) {
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtoupper($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : $default;
            $_config[$name] = $value;
            return null;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  strtoupper($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
        return null;
    }
    return null; // 避免非法参数
}

/**
 * session管理函数
 * @param string|array $name session名称 如果为数组则表示进行session设置
 * @param mixed $value session值
 * @return mixed
 */
function session($name='',$value='') {
    $prefix   =  C('SESSION_PREFIX');
    if(is_array($name)) { // session初始化 在session_start 之前调用
        if(isset($name['prefix'])) C('SESSION_PREFIX',$name['prefix']);
        if(C('VAR_SESSION_ID') && isset($_REQUEST[C('VAR_SESSION_ID')])){
            session_id($_REQUEST[C('VAR_SESSION_ID')]);
        }elseif(isset($name['id'])) {
            session_id($name['id']);
        }
        if('common' == APP_MODE){ // 其它模式可能不支持
            ini_set('session.auto_start', 0);
        }
        if(isset($name['name']))            session_name($name['name']);
        if(isset($name['path']))            session_save_path($name['path']);
        if(isset($name['domain']))          ini_set('session.cookie_domain', $name['domain']);
        if(isset($name['expire']))          {
            ini_set('session.gc_maxlifetime',   $name['expire']);
            ini_set('session.cookie_lifetime',  $name['expire']);
        }
        if(isset($name['use_trans_sid']))   ini_set('session.use_trans_sid', $name['use_trans_sid']?1:0);
        if(isset($name['use_cookies']))     ini_set('session.use_cookies', $name['use_cookies']?1:0);
        if(isset($name['cache_limiter']))   session_cache_limiter($name['cache_limiter']);
        if(isset($name['cache_expire']))    session_cache_expire($name['cache_expire']);
        if(isset($name['type']))            C('SESSION_TYPE',$name['type']);
        if(C('SESSION_TYPE')) { // 读取session驱动
            $type   =   C('SESSION_TYPE');
            $class  =   strpos($type,'\\')? $type : 'Think\\Session\\Driver\\'. ucwords(strtolower($type));
            $hander =   new $class();
            session_set_save_handler(
                array(&$hander,"open"),
                array(&$hander,"close"),
                array(&$hander,"read"),
                array(&$hander,"write"),
                array(&$hander,"destroy"),
                array(&$hander,"gc"));
        }
        // 启动session
        if(C('SESSION_AUTO_START'))  session_start();
    }elseif('' === $value){
        if(''===$name){
            // 获取全部的session
            return $prefix ? $_SESSION[$prefix] : $_SESSION;
        }elseif(0===strpos($name,'[')) { // session 操作
            if('[pause]'==$name){ // 暂停session
                session_write_close();
            }elseif('[start]'==$name){ // 启动session
                session_start();
            }elseif('[destroy]'==$name){ // 销毁session
                $_SESSION =  array();
                session_unset();
                session_destroy();
            }elseif('[regenerate]'==$name){ // 重新生成id
                session_regenerate_id();
            }
        }elseif(0===strpos($name,'?')){ // 检查session
            $name   =  substr($name,1);
            if(strpos($name,'.')){ // 支持数组
                list($name1,$name2) =   explode('.',$name);
                return $prefix?isset($_SESSION[$prefix][$name1][$name2]):isset($_SESSION[$name1][$name2]);
            }else{
                return $prefix?isset($_SESSION[$prefix][$name]):isset($_SESSION[$name]);
            }
        }elseif(is_null($name)){ // 清空session
            if($prefix) {
                unset($_SESSION[$prefix]);
            }else{
                $_SESSION = array();
            }
        }elseif($prefix){ // 获取session
            if(strpos($name,'.')){
                list($name1,$name2) =   explode('.',$name);
                return isset($_SESSION[$prefix][$name1][$name2])?$_SESSION[$prefix][$name1][$name2]:null;
            }else{
                return isset($_SESSION[$prefix][$name])?$_SESSION[$prefix][$name]:null;
            }
        }else{
            if(strpos($name,'.')){
                list($name1,$name2) =   explode('.',$name);
                return isset($_SESSION[$name1][$name2])?$_SESSION[$name1][$name2]:null;
            }else{
                return isset($_SESSION[$name])?$_SESSION[$name]:null;
            }
        }
    }elseif(is_null($value)){ // 删除session
        if(strpos($name,'.')){
            list($name1,$name2) =   explode('.',$name);
            if($prefix){
                unset($_SESSION[$prefix][$name1][$name2]);
            }else{
                unset($_SESSION[$name1][$name2]);
            }
        }else{
            if($prefix){
                unset($_SESSION[$prefix][$name]);
            }else{
                unset($_SESSION[$name]);
            }
        }
    }else{ // 设置session
        if(strpos($name,'.')){
            list($name1,$name2) =   explode('.',$name);
            if($prefix){
                $_SESSION[$prefix][$name1][$name2]   =  $value;
            }else{
                $_SESSION[$name1][$name2]  =  $value;
            }
        }else{
            if($prefix){
                $_SESSION[$prefix][$name]   =  $value;
            }else{
                $_SESSION[$name]  =  $value;
            }
        }
    }
    return null;
}
?>