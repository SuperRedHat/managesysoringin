<?php
    include '../checkLogin.php';
    include '../lib/tools.php';
    $err="";
    $fName="";  //字段名
    $fval="";   //字段值
    
    $fval.=post("cName");
    if($fval!= NULL) {
        $fName.='clientName,';
        $fval.=',';  
    }  else {
        $err.='缺少必要数据项';
    }

    $tmp=post("mail");
    if($tmp!=NULL){
        $fName.='email,';
        $fval.=$tmp.','; 
    }  else {
        $err.='缺少必要数据项';
    }
    $tmp=post("jf");
    if($tmp!=NULL){
        $fName.='point,';
        $fval.=$tmp.','; 
    }  else {
        $err.='缺少必要数据项';
    }    
    $tmp=post("bz");
    if($tmp!=NULL){
	$fName.='note,';
	$fval.=$tmp.',';  
    } 
    $fName=  substr($fName,0,-1);
    $fval=  substr($fval,0,-1);
    include_once '../lib/db.php';
    $info=toInsertOrUpdate("d_client", $fName, $fval, "clientName", $_POST["cName"]);
    echo $info;
?>
