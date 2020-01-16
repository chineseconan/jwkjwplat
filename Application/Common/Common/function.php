<?php

/**
 *  系统函数
 */

//生成GUID
function makeGuid()
{
    //距离Oracle列最大宽度还差5位
    return sprintf('T%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535));
}

/**
 * 更改数据编码
 * @param $data
 * @param $beforeCoding
 * @param $afterCoding
 * @author baijingqi
 * @date 2017-12-05
 * @return string
 */
function changeCoding($data, $beforeCoding, $afterCoding)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = changeCoding($value, $beforeCoding, $afterCoding);
            } else {
                $data[$key] = iconv($beforeCoding, $afterCoding, $value);
            }
        }
    } else {
        $data = iconv($beforeCoding, $afterCoding, $data);
    }
    return $data;
}

/**
 * 得到webservice操作对象
 * @param string $address
 * @param string $charset
 * @param bool|false $decode
 * @author baijingqi
 * @return \SoapClient
 */
function getWebServiceObj($address = '', $charset = 'UTF-8', $decode = false)
{
    $client = new \SoapClient($address);
    $client->soap_defencoding = $charset;
    $client->decode_utf8 = $decode;
    $client->xml_encoding = $charset;
    return $client;
}

/**
 * 二维数组排序
 * @param array $arr
 * @param string $keys
 * @param string $type
 * @param string $sortType (默认按照数字处理，可指定按照字符串处理)
 * @param bool $resetKey 是否需要重组键值
 * @return array
 * @date 2017-10-14
 * @author baijingqi
 */
function arraySort($arr = array(), $keys = '', $type = 'asc', $sortType = 'number', $resetKey = FALSE)
{
    $keysValue = $newArray = array();
    foreach ($arr as $key => $value) {
        $keysValue[$key] = $value[$keys];
    }
    if ($sortType == 'number') {
        $type == 'asc' ? asort($keysValue) : arsort($keysValue);
    } else {
        $type == 'asc' ? asort($keysValue, SORT_STRING) : arsort($keysValue, SORT_STRING);
    }
    reset($keysValue);
    if ($resetKey == TRUE) {
        foreach ($keysValue as $k => $v) {
            $newArray [] = $arr[$k];
        }
    } else {
        foreach ($keysValue as $k => $v) {
            $newArray [$k] = $arr[$k];
        }
    }
    return $newArray;
}

/**
 * 递归处理层级 数组需要id，pid两个字段
 * @param array $data 需要处理的数组
 * @param $pid 最上级父级id
 * @param $level
 * @author baijingqi
 * @return array
 */
function getLevelData($data = [], $pid, $level = 0)
{
    if (empty($data)) return $data;
    $tree = [];
    foreach ($data as $key => $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $tree[] = $v;
            $tree = array_merge($tree, getLevelData($data, $v['id'], $level + 1));
        }
    }
    return $tree;
}

/**
 * 柱状图(所需数据：name-名称，value-值,baifenbi-百分比：可选)
 * @param array $data
 * @date 2017-9-8
 * @author baijingqi
 * @return array
 */
function barChart($data = array())
{
    if (empty($data)) return false;
    $baifenbi = array();
    $names = array();
    $values = array();
    foreach ($data as $k => $v) {
        array_push($names, $v['name']);
        array_push($values, $v['value']);
        if (isset($values['baifenbi'])) {
            array_push($baifenbi, $v['baifenbi']);
        }
    }
    $result = array();
    $result['names_str'] = "'" . implode("','", $names) . "'";
    $result['values_str'] = "'" . implode("','", $values) . "'";
    if (!empty($baifenbi)) $result['baifenbi_str'] = "'" . implode("','", $baifenbi) . "'";
    return $result;
}

/**
 * 饼图(所需数据：name-名称，value-值)
 * @param array $data
 * @date 2017-9-8
 * @author baijingqi
 * @return array
 */
function pieChart($data = array())
{
    if (empty($data)) return false;
    $names = array();
    $values = array();
    foreach ($data as $k => $v) {
        array_push($names, $v['name']);
        array_push($values, "{value:" . $v['value'] . ",name:'" . $v['name'] . "'}");
    }
    $result['names_str'] = "'" . implode("','", $names) . "'";
    $result['values_str'] = implode(",", $values);
    return $result;
}

/**
 * excel导出，在导出数据较少时可以选用该方法，大量数据导出请使用csv导出。该方法不对数据做任何处理，请处理好数据再传入
 * @param array $tableHeader excel 需要生成的excel表头，默认第一列为序号列
 * @param array $result 需要导出的数据
 * @param bool $isAjaxDown 需要直接下载还是需要ajax下载
 * @param array $width 列宽度
 * @param bool $is_landscape 纸张是否是横向
 * @param bool $is_center 文字是否居中
 * @param bool $footer 是否需要页脚
 * @param string $title 表头文本
 * @throws PHPExcel_Writer_Exception
 * @author baijingqi
 * @return array file
 */
function excelExport($tableHeader = array(), $result = array(), $isAjaxDown = true, $width = Array(),$is_landscape=false,$is_center=true,$footer=false,$title='',$title2="",$repeat=array(),$name="")
{

    if (empty($tableHeader)) return json_encode(array('code' => -1, 'message' => '缺少表头'));
    if (empty($result)) return json_encode(array('code' => -2, 'message' => '数据为空'));
    if ($tableHeader[0] != '序号' && $tableHeader[0] != '项目序号') array_unshift($tableHeader, '序号');
    $headerLength = count($tableHeader);
    if ($headerLength > 52) return json_encode(array('code' => -3, 'message' => '最多支持导出52列'));
    $letter = getEnglishLetter(); //获取excel列名
    if ($headerLength > 26) { //根据传入的表头的长度获取需要写入的excel列头
        $moreThan = $headerLength - 26; //压入超过26列后的列头
        $moreColumn = [];
        for ($i = 0; $i < $moreThan; $i++) {
            $moreColumn[] = 'A' . $letter[$i];
        }
        $letter = array_merge($letter, $moreColumn);
    }

    vendor("PHPExcel.PHPExcel");
    $excel = new \PHPExcel();

    $styleArray = array(
        'borders'=>array(
            'allborders'=>array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
    if($is_center){
        $styleArray['alignment'] = array(
            'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
        );
    }
    if($is_landscape){
        //纸张方向
        $excel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    }
    $rowIndex = 1;
    if($title!=""){
        $titleStyle = Array(
            'alignment'=>array(
                'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $excel->getActiveSheet()->setCellValue('A1',$title)->mergeCells('A1:'.$letter[count($tableHeader)-1].$rowIndex);

        $excel->getActiveSheet()->getStyle('A1:'.$letter[count($tableHeader)-1].$rowIndex)->applyFromArray($titleStyle);
        $excel->getActiveSheet()->getStyle('A1:'.$letter[count($tableHeader)-1].$rowIndex)->getFont()->setName("黑体")->setSize(16)->setBold(true);
        $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
        $rowIndex++;
    }
    if($title2!=""){
        $title2Style = Array(
            'alignment'=>array(
                'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
                'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $excel->getActiveSheet()->setCellValue("A".$rowIndex,$title2)->mergeCells("A".$rowIndex.":".$letter[count($tableHeader)-1].$rowIndex);
        $excel->getActiveSheet()->getStyle("A".$rowIndex.":".$letter[count($tableHeader)-1].$rowIndex)->applyFromArray($title2Style);
        $excel->getActiveSheet()->getStyle("A".$rowIndex.":".$letter[count($tableHeader)-1].$rowIndex)->getFont()->setName("楷体")->setSize(12)->setBold(true);
        $excel->getActiveSheet()->getRowDimension($rowIndex)->setRowHeight(20);
        $rowIndex++;
    }
    for ($i = 0; $i < count($tableHeader); $i++) {
        $excel->getActiveSheet()->setCellValue($letter[$i] . $rowIndex, "$tableHeader[$i]"); //写入表头
        $excel->getActiveSheet()->getStyle($letter[$i].$rowIndex)->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->getStyle($letter[$i].$rowIndex)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle($letter[$i].$rowIndex)->applyFromArray($styleArray);
    }
    if(empty($width)){
        for ($j = 0; $j < count($tableHeader); $j++) {
            $excel->getActiveSheet()->getColumnDimension(strtoupper(chr($j+65)))->setWidth(10);
        }
    }else{
        for ($j = 0; $j < count($width); $j++) {
            $excel->getActiveSheet()->getColumnDimension(strtoupper(chr($j+65)))->setWidth($width[$j]);
        }
    }
    $data = array();
    //将二维关联数组处理成索引数组
    foreach ($result as $key => $value) {
        $arr = $value;
        if (isset($arr['numrow'])) unset($arr['numrow']);
        $arr = array_values($arr);
        array_unshift($arr, $key + 1);
        $data[$key] = $arr;
    }
    $start = 2;
    $dataLen =  count($data) + 1;
    if($title!="") {
        $start++;
        $dataLen++;
    }
    if($title2!=""){
        $start++;
        $dataLen++;
    }
    for ($i = 1+$rowIndex; $i <= $dataLen; $i++) {
        $j = 0;
        foreach ($data[$i - $start] as $key => $value) {
            $excel->getSheet()->getStyle($letter[$j].$i)->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value");
            $excel->getActiveSheet()->getStyle("$letter[$j]$i", "$value")->applyFromArray($styleArray);
            if($tableHeader[$key]=="项目编号"||$tableHeader[$key]=="项目名称"||$tableHeader[$key]=="单位"||$tableHeader[$key]=="评审意见"||$tableHeader[$key]=="其他意见"){
                $excel->getActiveSheet()->getStyle($letter[$j].$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
            }
            $j++;
        }
    }
    if($footer){
        //设置页脚
        $excel->getActiveSheet()->getHeaderFooter()->setOddFooter("专家签字：                                                        日期：&R &P")->setAlignWithMargins(true);
        $excel->getActiveSheet()->getPageMargins()->setFooter("0.5");
    }
    if(!empty($repeat)) {//跨页 重复显示  固定头用
        $excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($repeat[0], $repeat[1]);
    }
    if($name!=""){
        $filename = $name;
    }else{
        $filename = date('Ymd') . time() . rand(0, 1000);
    }
    $write = new \PHPExcel_Writer_Excel2007($excel);
    if ($isAjaxDown === true) {
        $filename = $filename . '.xlsx';
        $savePath = 'Public/upload/excel/' . date('Y-m-d');
        if (!is_dir($savePath)) mkdir($savePath, 0777, true);
        $filePath = $savePath . '/' . $filename;
        if(!@rename('测试编码.txt',  '测试编码修改.txt') && !@rename('测试编码修改.txt',  '测试编码.txt')){
            $filePath = iconv('UTF-8','gbk',$filePath);
        }
        $write->save($filePath);
        $fileRootPath = getWebsiteRootPath();
        $filePath = $savePath . '/' . $filename;
        exit(json_encode(array('code' => 1, 'message' => $fileRootPath . $filePath)));
    } else {
        header("Pragma: public");
        header('Content-Disposition:attachment;filename=' .$filename . '.xlsx');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }
}

/**
 * 获取英文字母
 * @param int $length 默认从A开始返回
 * @param bool $upperCase 默认返回大写字母
 * @return array
 */
function getEnglishLetter($length = 26, $upperCase = true)
{
    $data = array();
    $end = $length + 65;
    $function = $upperCase ? 'strtoupper' : 'strtolower';
    for ($i = 65; $i < $end; $i++) {
        $data[] = $function(chr($i));
    }
    return $data;
}

/**
 * csv导出
 * @param array $header 表头
 * @param array $result 导出结果  结果不做任何处理，请处理好传入
 * @param bool|false $isAjaxDown 直接下载还是ajax下载
 * @author baijingqi
 * @date 2018-01-16
 * @return array
 */
function csvExport($header = array(), $result = array(), $isAjaxDown = false)
{
    if (empty($header)) return json_encode(array('code' => -1, 'message' => '缺少表头'));
    if ($header[0] != '序号') array_unshift($header, '序号');
    if (empty($result)) return json_encode(array('code' => -2, 'message' => '数据为空'));
    foreach ($header as &$val) {
        $val = iconv('utf-8', 'gbk', $val);
    }
    $filename = date('Ymd') . time() . rand(0, 1000) . '.csv';
    $filePath = 'Public/upload/csv/' . date('Y-m-d');
    if (!is_dir($filePath)) mkdir($filePath, 0777, true);

    $fp = fopen($filePath . '/' . $filename, 'w');
    fputcsv($fp, $header);
    foreach ($result as $key => $value) {
        $data = $value;
        if (isset($data['numrow'])) unset($data['numrow']);
        $data = array_values($data);
        array_unshift($data, $key + 1);

        $data = changeCoding($data, 'utf-8', 'gbk');
        fputcsv($fp, $data);
    }

    $fileFullPath = $filePath . '/' . $filename;
    $fileRootPath = getWebsiteRootPath();
    if ($isAjaxDown === true) {
        exit(json_encode(array('code' => 1, 'message' => $fileRootPath . $fileFullPath)));
    } else {
        header("location:" . $fileRootPath . $fileFullPath);
    }
}

/**
 * 根据传入年份按照星期划分时间段
 * @param $year
 * @return array
 */
function getWeekendsByYear($year)
{
    $year = intval($year);
    $data = [];
    if (empty($year)) return $data;
    $beginDate = $year . '-01-01';
    $endDate = $year . '-12-31';
    $today = date('Y-m-d');
    for ($i = $beginDate; $i < $endDate; $i = date('Y-m-d', strtotime($i) + 86400)) {
        //从一年的第一个星期一开始计算
        if (date('w', strtotime($i)) == 1) {
            $sunday = date('Y-m-d', strtotime($i) + 518400); //周日=周一+518400s

            //如果此次循环中周日大于本年的最后一天,需要终止循环
            if ($sunday > $endDate) {
                if ($today >= $i && $today <= $sunday) {
                    $data[] = ['date' => $i . '~' . $endDate, 'check' => 1];
                } else {
                    $data[] = ['date' => $i . '~' . $endDate];
                }
                break;
            }

            //判断今天是否在本次循环中的周一至周日区间之内
            if ($today >= $i && $today <= $sunday) {
                $data[] = ['date' => $i . '~' . $sunday, 'check' => 1];
            } else {
                $data[] = ['date' => $i . '~' . $sunday];
            }
            $i = $sunday;
        }
    }
    return $data;
}

/**
 * @return string
 * 获取网站根目录路径
 */
function getWebsiteRootPath()
{
    $relativePath = $_SERVER['SCRIPT_NAME'];
    $pathData = explode('index.php', $relativePath);
    $fileRootPath = $pathData[0];
    return $fileRootPath;
}

/**
 * 生成可解密字符串
 * @param string $str 需要加密的字符串
 * @param string $key 相当于为加密字符串生成一把钥匙，必须拿着把钥匙才能解开加密字符串
 * @param int $expire 有效期（秒） 0 为永久有效，默认一天过期
 * @return string
 */
function encryption($str = '', $key = '', $expire = 86400)
{
    $obj = new \Think\Crypt();
    return $obj->encrypt($str, $key, $expire);
}

/**
 * 解密字符串
 * @param string $str 需要解密的字符串
 * @param string $key 解密字符串用的钥匙
 * @return string
 */
function deciphering($str = '', $key = '')
{
    $obj = new \Think\Crypt();
    return $obj->decrypt($str, $key);
}

/**
 * 获取json标准结果
 * @param int $code
 * @param string $message
 * @return string
 */
function makeStandResult($code = 0, $message = '')
{
    $result = array(
        'code' => $code,
        'message' => $message,
    );
    return json_encode($result);
}

/**
 * 验证码检测
 * @param $code
 * @param string $id
 * @return bool
 */
function check_verify($code, $id = '')
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 文件上传
 * @param string $type
 * @param int $size
 * @param string $savePath
 * @return array|bool
 */
function uploadFile($type = 'image', $size = 3145728, $savePath = 'img/')
{
    $upload = new \Think\Upload();
    $upload->maxSize = $size;
    switch ($type) {
        case 'image' :  //图片类文件格式
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            break;
        case 'file' :   //文件类文件格式
            $upload->exts = array('txt', 'doc', 'docx', 'xls', 'xlsx','data');
            break;
        case 'video' :  //音像类文件格式
            $upload->exts = array('wmv', 'mp4', 'mov', 'm4v', 'avi', 'dat', 'mkv', 'flv', 'mp3');
            break;
        default:
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            break;
    }

    $upload->rootPath = './Public/uploads/';
    $upload->savePath = 'projectimg/';
    $info = $upload->upload();
    if (!$info) {
        $result = ['state' => 'fail', 'message' => $upload->getError()];
    } else {
        foreach ($info as $file) {
            $filePath = 'uploads/' . $file['savepath'] . $file['savename'];
        }
        $result = array(
            'message' => $filePath,
            'state' => 'success'
        );
    }
    return $result;
}

/**
 * 取出二维数组的某一列值
 * @param $data
 * @param $key
 * @return array
 */
function removeArrKey($data, $key,$isNull = true)
{
    $arr = [];
    foreach ($data as $k => $value) {
        if(($isNull == true) || !empty($value[$key])){
            $arr[] = $value[$key];
        }   
    }
    return $arr;
}

/**
 * 二维数组去重
 * @param $array2D
 * @param bool|false $stkeep
 * @param bool|true $ndformat
 * @return mixed
 */
function uniqueArr($array2D, $stkeep = false, $ndformat = true)
{
    //判断是否保留一级数组键（一级数组键可以为非数字）
    if ($stkeep) $strArr = array_keys($array2D);

    //判断是否保留二级数组键（所有二级数组键必须相同）
    if ($ndformat) $ndArr = array_keys(end($array2D));

    //降维，也可以用implode，将一维数组转换为用逗号连接的字符串
    $temp = [];
    foreach ($array2D as $v) {
        $v = join(",", $v);
        $temp[] = $v;
    }
    //去掉重复字符串，也就是重复的一维数组
    $temp = array_unique($temp);

    //再将拆开的数组重新组装
    foreach ($temp as $k => $v) {
        if ($stkeep) $k = $strArr[$k];
        if ($ndformat) {
            $tempArr = explode(",", $v);
            foreach ($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
        } else {
            $output[$k] = explode(",", $v);
        }
    }

    return array_values($output);
}

/**
 * 根据用户权限判断是否展示页面
 */
function showViewsByPower()
{
    return true;
    $view = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
    $powers = cookie('operate_view');
    if (in_array($view, $powers)) {
        return true;
    } else {
        $obj = new \Think\View();
        $obj->display(T('Admin@Public/error'));
        die;
//        echo file_get_contents( T('Admin@Public/error'));die;
    }
}


/**
 * 递归替换数组
 * @param array $data 要处理的数组
 * @param string $search 要查找什么
 * @param string $replace 要替换成什么
 * @author baijingqi
 * @date 2018-04-04
 * @return array
 */
function recursionReplace($data, $search = null, $replace = '')
{
    foreach ($data as &$value) {
        if (is_array($value)) {
            $value = recursionReplace($value, $search, $replace);
        } else {
            if ($value == $search) $value = $replace;
        }
    }
    return $data;
}


/**
 * 判断该系统是否涉密
 * @return bool
 */
function systemIsSecret()
{
    $sysLevel = S('system_level');
    if (!empty($sysLevel)) {
        if ($sysLevel == '涉密') {
            return true;
        } else {
            return false;
        }
    } else {
        $model = M('sysconfig');
        $data = $model->field('sc_itemvalue')->where("sc_itemcode = 'SEC_ISSECRET'")->find();
        if ($data['sc_itemvalue'] == 1) {
            S('system_level', '涉密');
            return true;
        } else {
            S('system_level', '非密');
            return false;
        }
    }
}

//文件导入
function excelImport($path)
{
    vendor("PHPExcel.PHPExcel");
    $reader = new PHPExcel_Reader_Excel2007();
    $PHPExcel = $reader->load($path, 'utf-8');
    $sheet = $PHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $data = Array();
    $column = Array();
    for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
        for ($colIndex = "A"; $colIndex <= $highestColumn; $colIndex++) {
            $addr = $colIndex . $rowIndex;
            $cell = $sheet->getCell($addr)->getValue();
            if ($cell instanceof PHPExcel_RichText) {
                $cell = $cell->__toString();
            }
            $data[$rowIndex][$colIndex] = $cell;
        }
    }
    for($colIndex = "A"; $colIndex <= $highestColumn; $colIndex++){
        $columnAddr = $colIndex . 1;
        $columnCell = $sheet->getCell($columnAddr)->getValue();
        if ($columnCell instanceof PHPExcel_RichText) {
                $columnCell = $columnCell->__toString();
        }
        $column[] = $columnCell;
    }
    return Array("data"=>$data,"column"=>$column);
}

/*
 * 获取用户ID
 * @return string
 */
function getUserId()
{
    $userid = session("user_id");
    return $userid;
}

/*
 * 获取当前年
 * @return string
 */
function getYear()
{
    return date("Y", time());
}