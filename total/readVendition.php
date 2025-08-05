<?php
include '../lib/db.php';
include '../lib/tools.php';

if (!isset($_POST["readType"])) {
    return;
}
$readType=post("readType");
switch ($readType) {
    case "product":
        if (!isset($_POST["typeId"])) {
            return;
        }
        $typeId=post("typeId");
        echo toSelect("v_d_product", "pId,pShort||pName pName,tId,pPrice,isSale,starTime,endTime,sale,jfdhbl", "tId=" . $typeId);
        break;
    case "histor":
        if (!isset($_POST["cName"])) {
            return;
        }
        $cName=post("cName");
        $num=post("num");
        if ($num===NULL) {
            $num=10;
        }
        //为解决空格被自动消失的问题，所以把空格替换为,号
        echo toSelect("v_vendition", "vId,replace(vTime,' ',',')||' --- '||note vTime", "cName='" .$cName . "' order by vTime  desc limit $num");        
//        echo toSelect("v_vendition", "vId,concat(vTime,'  ',note) vTime", "cName='" . $_POST["cName"] . "' order by vTime  desc limit $num");
        break;
    case "vend":
        if (!isset($_POST["vId"])) {
            return;
        }
        $vId=post("vId");
        echo toSelect("v_vendition_info", "*", "vId=" . $vId);
        break;
    default:
        break;
}
?>
