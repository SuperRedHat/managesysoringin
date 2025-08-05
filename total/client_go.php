<?php
    include '../checkLogin.php';
    include '../lib/tools.php';
    $err="";
    $cName=post("cName");
    if ($cName===NULL) {
        $err.="没有客户信息";
        $cName = "";
    }
    include '../lib/db.php';
    $rs=  readdbByRs("d_client", "*", "clientName='$cName'");
    if(count($rs)==0){
        echo "没有找到此客户的基本信息";
        return;
    };
    $info="-------------------- 客户基本信息 --------------------------<br><br>";
    $info.="客户名称：".$rs[0]["clientName"]."<br>";
    $info.="Email：".$rs[0]["email"]."<br>";
    $info.="注册时间：".$rs[0]["registryTime"]."<br>";
    $info.="积分：".$rs[0]["point"]."<br>";
    $info.="备注：".$rs[0]["note"]."<br><br>";
    $info.="<br><br>";
    
    $sql="select count(1) cs ,sum(money) hj,MAX(money) zd,min(money) zx, avg(money) pj from d_vendition where clientName='$cName'";
    $rs=execSql($sql);
    if(count($rs)==0){
        echo "没有找到此客户的购物数据";
        return;
    };    
    $info.="--------------------- 消费数据统计 -------------------------<br><br>";
    $info.="总购物次数：".$rs[0]["cs"]." 次<br>";
    $info.="最大一次消费金额：".$rs[0]["zd"]." 元<br>";
    $info.="最小一次消费金额：".$rs[0]["zx"]." 元<br>";
    $info.="平均消费金额：".$rs[0]["pj"]." 元<br>";
    $info.="消费总额：".$rs[0]["hj"]." 元<br>";
    $info.="<br><br>";

    $sql="select vendTime,money from d_vendition where clientName='$cName' order by vendTime desc limit 50";
    $rs=execSql($sql);
    
    $info.="--------------------- 最后五十次消费记录 -------------------------<br><br>";    
    foreach ($rs as $item){
        $info.=$item["vendTime"]."&nbsp;&nbsp;--&nbsp;&nbsp;".$item["money"]." 元<br>";    
    }
    $info.="<br><br>";
    
    
    
    echo $info;
    
    
        
?>
