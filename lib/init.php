<?php
	try{
		$dns="sqlite:".dirname(__FILE__)."/iamvip.db";
		$conn=new PDO($dns,null,null, array(PDO::ATTR_PERSISTENT => false, PDO::ATTR_TIMEOUT => 30));
		$conn->query("SET NAMES utf8");
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
	}catch(Exception $e ){
		print $e;
	}	
?>