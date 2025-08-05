<?php

include '../lib/db.php';
include '../checkLogin.php';

/**
 * showAccordion	
 * 通过数据查询，生成accordion组件中的各个面板
 * 黄文军 2013/4/18
 */
function showAccordion() {
    $rs = readdbByRs('c_productType', 'productTypeId,productTypeName', 'isUse=1');
    $div = "";
    foreach ($rs as $item) {
        $div.='<div title=' . $item['productTypeName'] . ' data-options ="iconCls:\'icon-blank\'" style="overflow:auto;padding:10px;" name=acc_' . $item['productTypeId'] . '> ';
        $divitem = '';
        $rs_prod = readdbByRs('d_product', 'productId,productName', 'productTypeId=' . $item['productTypeId']);
        $str = '';
        if (count($rs_prod) > 0) {
            $str = "<ul class=accordUl>";
            foreach ($rs_prod as $item_prod) {
                $str.='<li class=a_li value=' . $item_prod['productId'] . ' onclick=showProduct(' . $item_prod['productId'] . ')>' . $item_prod['productName'] . ' </li>';
            }
            $str.='</ul>';
        }
        $div.=$str . '</div>';
    }
    echo $div;
}
?>

