<?php
include dirname(__FILE__) . "/db.php";
include dirname(__FILE__) . "/tools.php";

$type=post("type");
if ($type==NULL) {
    echo 'Error:没有指定要删除的对象';
    return;
}
$tableName = ""; //要删除的记录存在哪张表里
$tj = "";   //删除条件
switch ($type) {
    case 'product':
        $pid=post("pId");
        if ($pid==NULL) {
            echo "Error:没有指定要删除的记录！";
            return;
        }
        //查询此产品是否已发生销售行为，如是，则不允许删除
        $rs=readdbByRs("d_vendition_info","*","productId=". $pid);
        if(count($rs)>0){
        	echo "Error:此产品已经发生了销售行为，不允许被删除。";
        	return;
        }
        $tableName = "d_product";
        $tj = 'productId=' . $pid;
        break;
        
    case 'client':
        $cName=post("cName");
        if ($cName==NULL) {
            echo "Error:没有指定要删除的记录！";
            return;
        }
        //首先查询此用户是否已有购物记录，如有，则不允许删除
        $rs = readdbByRs("d_vendition", "*", "clientName='$cName' limit 1 ");
        if (count($rs) > 0) {
            echo "Error:此客户已经有购物记录，所以不允许删除它！";
            return;
        } else {
            $tableName = "d_client";
            $tj = "clientName='" . $cName . "'";
        }
        break;
    default:
        break;
}

toDelete($tableName, $tj);
?>
