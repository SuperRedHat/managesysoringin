<?php

include "./lib/db.php";
$table='d_vendition';
$field='vendTime';
$id='vendId';
$sql="select $id,$field from $table";
$rs=execSql($sql);
if (count($rs)==0){
    exit;
};
for($i=0;$i<count($rs);$i++){
    $d=explode('-',$rs[$i][$field]);
    $s=$d[0].'-';
    $s.=substr('0'.$d[1],-2).'-';
    $d1=  explode(' ', $d[2]);
    $s.=substr('0'.$d1[0],-2).' ';
    $s.=$d1[1];
    $sql="update $table set $field='$s' where $id={$rs[$i][$id]}";
    echo $sql;
    runSql($sql);
    
}

