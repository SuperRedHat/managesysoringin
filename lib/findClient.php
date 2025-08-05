<?php

include './tools.php';
$cName=post('cName');
if ($cName==NULL) {
    return;
}
include_once dirname(__FILE__) . '/db.php';
echo toSelect('v_client', '*', 'cName="' . $cName . '"');
?>
