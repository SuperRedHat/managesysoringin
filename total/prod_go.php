<?php
//include '../checkLogin.php';
include '../config.php';
include '../lib/tools.php';
$err = "";
$tId=post("tId");
if ($tId===NULL) {
    $err.="没有产品类型";
    $tId = "";
}
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
$jzsj=  post("jzsj");
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
$sql = "select pName mc, sum(number) sl,sum(money) je,count(1) bs  from v_vendition_info where  tId='$tId' and ";
if (strlen($qssj) > 0) {
    // mysql $sql.=" date(sj)>=str_to_date('$qssj','%Y-%m-%d')";
    $sql.=" date(vendTime)>=date('$qssj')";
    if (strlen($jzsj) > 0) {
        //mysql  $sql.=" and date(sj)<=str_to_date('$jzsj','%Y-%m-%d')";
        $sql.=" and date(vendTime)<=date('$jzsj')";
    }
}
$sql.="group by pName order by je desc";
include_once '../lib/db.php';
$rs = execSql($sql);
echo array_to_json($rs);
?>
