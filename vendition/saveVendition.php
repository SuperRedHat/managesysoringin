<?php

include '../checkLogin.php';
include '../lib/tools.php';

if (isset($_POST["info"])) {
    $info = post("info");
    $changeState = post("changeState");
    $ply_history = post("ply_history");
} else {
    echo "Error:没有传入要保存的数据";
    return;
}
include '../lib/db.php';
$info = explode('|~~|', $info);   //分割传过来的数据，第一部份为产品的汇总信息。第二部份为明细
if (count($info) < 2) {
    echo "Error:传入的数据有误！";
    return;
}
$client = explode("|~|", $info[0]);
$hz = explode("|~|", $info[1]);
$mx = explode("*", $info[2]);
$err = "";

//保存客户信息
if (strlen(trim($client[0])) > 0) {
    toInsertOrUpdate("d_client", "clientName,email,point,note", "$client[0],$client[1],$client[2],$client[3]", "clientName", $client[0]);
} else {
    //修改客户的积分
    if ($changeState == 0) {
        $err .= toUpdate("d_client", "point", $info[3], "clientName='$hz[0]'");
    } else {
        $rs = execSql("select point from d_vendition where clientName='{$hz[0]}' and vendid='$ply_history'");
        if (count($rs) > 0) {
            if ($rs[0]["point"] > $hz[5]) {   //如果原有积分大于新的积分，则把客户的累计积分减少。否则增加
                runSql("update d_client set point= point-" . ($rs[0]["point"] - $hz[5]) . " where clientName='{$hz[0]}'");
            } else {
                runSql("update d_client set point= point+" . ($hz[5] - $rs[0]["point"]) . " where clientName='{$hz[0]}'");
            }
        }
    }
}
//保存产品汇总信息
if ($changeState == 0) {
    $vendId = toInsert("d_vendition", "clientName,receivable,money,usePoint,note,point,czydm", "$hz[0],$hz[1],$hz[2],$hz[3],$hz[4],$hz[5],{$_SESSION['czydm']}");
} else {
    toUpdate("d_vendition", "receivable,money,usePoint,note,point,czydm", "{$hz[1]},{$hz[2]},{$hz[3]},{$hz[4]},{$hz[5]},{$_SESSION['czydm']}", "clientName='{$hz[0]}' and vendid='{$ply_history}'");
}

//保存产品明细信息
if ($changeState == 0) {
    foreach ($mx as $i) {
        $item = explode("|~|", $i);
        $err .= toInsert("d_vendition_info", "vendId,productId,number,price,money", "$vendId,$item[0],$item[1],$item[2],$item[3]");
    }
} else {    //对原销售记录进行修改
    //先查出此笔销售的vendid
    //记录原销售记录
    $rs = execSql("select pid,number,money,pName from v_vendition_info where clientName='{$hz[0]}' and vid='$ply_history'");
    $oldVend = "";
    foreach ($rs as $item) {
        $oldVend .= "产品代码：{$item['pid']}，产品名称：{$item['pName']}，数量：{$item['number']}，金额：{$item['money']} ";
    }
    //先删除原先的记录，再重新插入新记录
    runSql("delete from d_vendition_info where vendid='$ply_history'");
    $newVend = "";
    foreach ($mx as $i) {
        $item = explode("|~|", $i);
        $err .= toInsert("d_vendition_info", "vendId,productId,number,price,money", "$ply_history,$item[0],$item[1],$item[2],$item[3]");
        $newVend .= "产品代码：{$item[0]} ，数量：{$item[1]}，金额：{$item[3]}";
    }
    //将变量情况写入日志表
    runSql("insert into d_changeLog (vendId,oldVend,newVend,czydm) values ('$ply_history', '$oldVend','$newVend','{$_SESSION['czydm']}')");
}
echo $err;
?>