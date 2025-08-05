<?php
    include '../checkLogin.php';
    include '../lib/tools.php';
    //查询客户购物信息
    $cName=post("cName");
    if($cName===NULL){
        return "Error:没有客户信息";
    }
    include '../lib/db.php';
    echo array_to_json(execSql("select COUNT(1) gwcs,sum(money) hjje,sum(usePoint) syjf,min(vTime) dyc,max(vTime) zyhy from v_vendition where cName='$cName'"));
?>
