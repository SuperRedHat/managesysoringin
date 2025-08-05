<?php

function post($str) {
    if (!isset($_POST[$str])) {
        return null;
    }
    $str=$_POST[$str];
    $str = htmlspecialchars($str);
    $str = strip_tags($str);
    $str=htmlencode($str);
    return $str;
}

function htmlencode($str) {
    if ($str == "")
        return $str;
    $str = trim($str);
    $str = str_ireplace("&", "＆", $str);
    $str = str_ireplace(">", "＞", $str);
    $str = str_ireplace("<", "＜", $str);
    $str = str_ireplace(chr(32), " ", $str);
    $str = str_ireplace(chr(9), " ", $str);
    $str = str_ireplace(chr(9), " ", $str);
    $str = str_ireplace(chr(34), "＆", $str);
    $str = str_ireplace(chr(39), "＇", $str);
    $str = str_ireplace(chr(13), "", $str);
    $str = str_ireplace("'", "＇", $str);
    $str = str_ireplace("select", "", $str);
    $str = str_ireplace("SCRIPT", "", $str);
    $str = str_ireplace("script", "", $str);
    $str = str_ireplace("join", "", $str);
    $str = str_ireplace("union", "", $str);
    $str = str_ireplace("where", "", $str);
    $str = str_ireplace("insert", "", $str);
    $str = str_ireplace("delete", "", $str);
    $str = str_ireplace("update", "", $str);
    $str = str_ireplace("like", "", $str);
    $str = str_ireplace("drop", "", $str);
    $str = str_ireplace("create", "", $str);
    $str = str_ireplace("modify", "", $str);
    $str = str_ireplace("rename", "", $str);
    $str = str_ireplace("alter", "", $str);
    $str = str_ireplace("cast", "", $str);
    return $str;
}


/**
 * 循环删除目录和文件函数
 * 黄文军 2012/3/20
 */
function delDirAndFile($dirName) {
    try {
        $handle = opendir("$dirName");
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dirName/$item")) {
                    delDirAndFile("$dirName/$item");
                } else {
                    if (!unlink("$dirName/$item")) {
                        echo "Error: 无法删除 $item <br>";
                    } //end if
                }//end if
            }
        } //end while
        closedir($handle);
        if (!rmdir($dirName)) {
            echo "Error:删除专题时出错";
        }
    } catch (Exception $err) {
        Echo "Error:" . $err;
    } //end try
}

/**
 * 将 "../../" 这种形式的相对路径转换成实际路径
 * 参数:_str :相对路径
 * 例如：subPathName(../../jslib)  转换为 a/b/jslib
 * 黄文军 2012/3/28  
 */
function tranPathName($_str) {
    $result = "";
    $sCount = substr_count($_str, "../"); //统计../这个串出现过多少次，下一步就截取多少级目录
    for ($i = 0; $i < $sCount; $i++) {
        $idx = strrpos($_str, "/");
        $result = "/" . substr($_str, $idx + 1, strlen($_str)) . $result;
        $_str = substr($_str, 0, $idx);
    }
    return $result;
}

/**
 * 
 *  $_file :文件名
 *  $_valName：变量名
 *  $_val：变量值
 */
function writeConfVal($_file, $_valName, $_val) {
    $_valName = trim($_valName);
    if (strpos($_valName, 0, 1) != "$") {
        $_valName = "$" . $_valName;
    }
    $aryFile = rFileToAry($_file);

    $zd = false;
    $hh = -1; //记录文件最后的 ？ > 符号的位置，如果需要添加变量时需要用到这个变量
    for ($jsq = 0; $jsq < count($aryFile); $jsq++) {
        $aryRow = explode("=", $aryFile[$jsq]);
        if (trim($aryRow[0]) == $_valName) {
            $aryVal = explode(";", $aryRow[1]); //考虑每一行后面是否有注释
            if (count($aryVal) > 1) {
                $zs = ";" . $aryVal[1];
            } else {
                $zs = ";";
            }
            $aryFile[$jsq] = $aryRow[0] . "=" . $_val . $zs;
            $zd = true;
            break;
        } else {
            if (trim($aryFile[$jsq]) == "?>") {
                $hh = $jsq;
            }
        }
    }
    if (!$zd) {  //如果没找到这个变量，则在后面添加它
        if ($hh < 0) {  //文件未尾没有？> 结束
            $aryFile[] = $_valName . "=" . $_val . ";\r\n";
        } else {
            $aryFile[$hh] = $_valName . "=" . $_val . ";\r\n";
        }
    }
//拼装出完整的新文件
    $str = "";
    for ($jsq = 0; $jsq < count($aryFile); $jsq++) {
        $str.=$aryFile[$jsq];
    }
    wFile($_file, $str);
}

function wFile($_file, $_content) {
    $jb = fopen($_file, "wb");
    if (fwrite($jb, $_content) === FALSE) {
        return "Error:修改设置文件时出错，请确保用户有此文件的修改权限！";
        ;
    }
    fclose($jb);
}

/**
 * 读取指定的文，并将文件以数组形式返回整个文件
 * 黄文军 2012/5/18
 * */
function rFileToAry($_file) {
    if (file_Exists($_file) === False) {
        return "Error:错误的文件名或是路径！";
    }
    $fileAry = file($_file);
    return $fileAry;
}

/**
 * 截取中文字字符，适合于gb2312 和 gbk字符集
 * $str 要截取的字符串
 * $len 要截取的长度
 * 黄文军 2012/12/5
 */
function gsubstr($str, $len) {
    $end = strlen($str);
    if ($end > $len) {
        $end = $len;
    }
    $result_str = "";
    if ($end == 0)
        return $result_str;
    for ($i = 0; $i < $end; $i++) {
        if (ord(substr($str, $i, 1)) > 0xa1) {
            $result_str.=substr($str, $i, 2);
            $i++;
        } else
            $result_str.=substr($str, $i, 1);
    }
    return $result_str;
}

/**
 * 根据数据表里的记录，生成列表所需要的option 项目
 * @param unknown $table
 * @param unknown $titleField
 * @param unknown $valField
 * 
 * 黄文军 2013/4/18
 */
function fillSelect($table, $titleField, $valField, $tj, $showVal = 0) {
    include_once dirname(__FILE__) . '/db.php';
    $rs = readdbByRs($table, "$titleField,$valField", $tj);
    $str = "";
    if (count($rs) > 0) {
        foreach ($rs as $item) {
            if ($showVal == 1) {
                $str.="<option value=" . $item[$valField] . ">" . $item[$valField] . $item[$titleField] . "</option>";
            } else {
                $str.="<option value=" . $item[$valField] . ">" . $item[$titleField] . "</option>";
            }
        }
    }
    echo $str;
}


?>