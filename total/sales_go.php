<?php

include '../checkLogin.php';
include '../config.php';
include '../lib/tools.php';

$cName=  post("cName");
if ($cName===NULL) {
    $cName = "";
}
$err = "";
$qssj=post("qssj");
if ($qssj!=NULL) {  //起始时间
    $qssj = str_replace("/", "-", $qssj);
    //最多只允许查询$sys_cx_maxDay天的数据
    if ($sys_cx_maxDay != 0) {
        if ((strtotime(date("Y-m-d")) - strtotime($qssj)) / 3600 / 24 > $sys_cx_maxDay) {
            $qssj = new DateTime(date("Y-m-d"));
            $qssj = $qssj->modify("-$sys_cx_maxDay day");
        }
    }
} else {
    $err.="Error:必须要输入查询起始时间 <br>";
}
$jzsj=post("jzsj");
if ($jzsj!=NULL) {  //截止时间
    $jzsj = str_replace("/", "-", $jzsj);
    if (strlen(trim($jzsj)) > 0 && strtotime($jzsj) < strtotime($qssj)) {
        $err.="Error:查询截止时间不能小于开始时间 <br>";
    }
} else {
    $jzsj = "";
}

if (strlen($err) > 0) {
    echo $err;
    return;
}
$tj = "";
if (strlen($cName) > 0) {
    $tj.="clientName='$cName' and ";
}
if (strlen($qssj) > 0) {
    $tj.=" date(vendTime)>=date('$qssj')";
    if (strlen($jzsj) > 0) {
        $tj.=" and date(vendTime)<=date('$jzsj')";
    }
}
$sql = "select '合计' cname,''sj,sum(money) hj,sum(usePoint) jf,count(1) as cs from d_vendition where " . $tj . ' union ';
$sql.='select clientName,vendTime,money,usePoint,1 from d_vendition where  ' . $tj;
include_once '../lib/db.php';
$rs = execSql($sql);
echo array_to_json($rs);
?>
