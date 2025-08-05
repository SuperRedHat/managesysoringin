<?php

include '../checkLogin.php';
include '../lib/tools.php';
$tj = "";
$sl = '';
$cName = post("cName");
if (trim($cName) != '') {
    $tj = " where cName='$cName'  ";
    $sql = "select '合计' cName,'' zcsj,sum(money) je,'' jf,'' note  FROM v_vendition" . $tj;
    $sql .= " union ";
    $sql .= " SELECT cName ,vTime zcsj,money je,usePoint jf ,note FROM v_vendition " . $tj;
} else {
    $sql = " SELECT cName ,'' zcsj,sum(money) je,usePoint jf ,note FROM v_vendition group by cname,zcsj,jf,note order by je desc limit 100";
}
include '../lib/db.php';
$rs = execSql($sql);
if (count($rs) == 0) {
    echo "Error:没有找到客户的基本信息";
    return;
}
echo array_to_json($rs);
