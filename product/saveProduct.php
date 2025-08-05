<?php
    include '../checkLogin.php';
    include '../lib/tools.php';
    $err="";
    $fName="";  //字段名
    $fval="";   //字段值
    $tmp=post("tId");
    if($tmp!=NULL){
        $fName.='productTypeId,';
        $fval.=$tmp.',';                
    }  else {
        $err.='缺少必要数据项';
    }
    $tmp=post("pName");
    if($tmp!=NULL){
        $fName.='productName,';
        $fval.=$tmp.','; 
    }  else {
        $err.='缺少必要数据项';
    }
    $tmp=post("pShort");
    if($tmp!=NULL){
        $fName.='shortName,';
        $fval.=$tmp.','; 
    }  else {
        $err.='缺少必要数据项';
    }    
    $tmp=post("pPrice");
    if($tmp!=NULL){
        $fName.='price,';
        $fval.=$tmp.','; 
    }  else {
        $err.='缺少必要数据项';
    }
    $tmp=  post("isSale");
    if($tmp!=NULL){
        $fName.='isSale,';
        $fval.=$tmp.','; 
    }  else {
        $err.='缺少必要数据项';
    }
    $tmp=post("isSale");
    if($tmp=="1"){
        $tmp=post("starTime");
    	if($tmp!=NULL){
    		$fName.='saleStarTime,';
    		$fval.=$tmp.',';
    	}
    	$tmp=post("endTime");
    	if($tmp!=NULL){
    		$fName.='saleEndTime,';
    		$fval.=$tmp.',';
    	}
        $tmp= post("sale");
    	if($tmp!=NULL){
    		$fName.='sale,';
    		$fval.=$tmp.',';
    	}
    }
    
    $tmp=post("useIt");
    if($tmp!=NULL){
    	$fName.='isUse,';
    	$fval.=$tmp.',';
    }  else {
    	$err.='缺少必要数据项';
    }
    $tmp=post("jfdhbl");
    if($tmp!=NULL){
    	$fName.='pointVsMoney,';
    	$fval.=$tmp.',';
    }  else {
    	$err.='缺少必要数据项';
    }
    
    $fName=  substr($fName,0,-1);
    $fval=  substr($fval,0,-1);
    
    include_once '../lib/db.php';
    $info=toInsertOrUpdate("d_product", $fName, $fval, "productId", post("pId"));
    echo $info;

?>
