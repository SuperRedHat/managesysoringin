<?php

include '../checkLogin.php';
include '../lib/tools.php';
$id = post("id");
if ($id != NULL) {
    include '../lib/db.php';
    $rs = toSelect('v_d_product', '*', 'pId=' . $id);
    echo $rs;
} else {
    return;
}