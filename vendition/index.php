<?php
include '../checkLogin.php';
include '../config.php';
include '../lib/tools.php';
include '../lib/db.php';
$btn_add = true;
$btn_save = true;
$btn_del = false;
$btn_clean = true;
$findClient = true;

//读取产品类型
//$rs = readdbByRs("v_prodType", "tId,concat(tId,tName) as tName", "u=1");
$rs = readdbByRs("v_prodType", "tId,tId||tName as tName", "u=1");
$prodType = "<option value=''>请选择</option>";
if (count($rs) > 0) {
    foreach ($rs as $item) {
        $prodType .= "<option value=" . $item['tId'] . " > " . $item['tId'] . $item['tName'] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 销售管理v3</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
        <link rel="stylesheet" type="text/css" href="./vendition.css">
        <link rel="stylesheet" type="text/css"
              href="../components/Validation-Engine/css/validationEngine.jquery.css">

        <script type="text/javascript" src="../components/jquery-1.8.3.min.js"></script>
        <script type="text/javascript"
        src="../components/Validation-Engine/js/jquery.validationEngine.js"></script>
        <script type="text/javascript"
        src="../components/Validation-Engine/js/languages/jquery.validationEngine-zh_CN.js"></script>

        <script type="text/javascript" src="../lib/tools.js"></script>
        <script type="text/javascript" src="./venditionFun.js"></script>
        <script type="text/javascript">
            var sys_syjf =<?php echo $sys_syjf; ?>;
            var sys_jf_dhbl =<?php echo $sys_jf_dhbl; ?>;
            var sys_xsd =<?php echo $sys_xsd; ?>;
            var sys_historNum =<?php echo $sys_historNum; ?>;
            var sys_jf_jsff =<?php echo $sys_jf_jsff; ?>;
            var sys_jf_js =<?php echo $sys_jf_js; ?>

            $(document).ready(function () {
                jQuery("#frm_input").validationEngine('attach');

                $("#email,#note").keydown(function (e) {
                    $("#needSaveClient").val("true");
                });
                $("li").mouseover(function (e) {
                    $(this).addClass('selectColor');
                });
                $("li").mouseleave(function (e) {
                    $(this).removeClass('selectColor');
                });
            });
        </script>
    </head>
    <body>
        <div id=div_main>
            <?php include_once '../menu.php'; ?>
            <form id="frm_input">
                <div id=div_body>
                    <div id="div_left">
                        <div class="div_client">
                            <input type="hidden" id="needSaveClient" value="false"> 
                            <input type='hidden' id='listCount' value='0'> 
                            <input type='hidden' id='errMsg' value=''> 
                            <div>
                                <span class="span_title">客户名称(c)</span> 
                                <input  type="text" id="cName" onblur="findClient();" class="validate[required] text-input" maxlength="30" accesskey="c"> 
                            </div>
                            <div>
                                <span class="span_title">产品分类</span> 
                                <select  id="tId" style="width: 132px; height: 28px;" onchange="getProduct(this)"><?php echo $prodType; ?>
                                </select>
                            </div>
                            <div> 
                                <span class="span_title">产品名称</span>
                                <select id="pId" style="width: 132px; height: 28px;">
                                </select>
                            </div> 
                            <div>
                                <span class="span_title">数量(n)</span>
                                <input type="number" id="number"  class="validate[custom[number]] text-input" accesskey="n" maxlength="6">
                            </div>
                            <div style="text-align: center;">
                                <button type="button" id='btn_AddtoProdList' onclick='btn_AddtoList();' style="height: 25px; width: 80px; margin-top: 20px">增加(a)</button>
                            </div>
                        </div>

                        <div class="div_client">
                            <span class="span_title">应付合计:</span> <input type="text" disabled="disabled" id="txt_yfhj"  style="color: blue; font-size: 16px; height: 28px;">
                            <span class="span_title">本次积分</span>
                            <input type="text" disabled="disabled" id="point_me"> 
                            <span class="span_title">历史积分</span>
                            <input type="text" disabled="disabled" id="point">
                            <?php if ($sys_syjf != 0) { ?>
                                <span class="span_title">使用积分(f)</span>
                                <input type="number" id="txt_bcsyjf" disabled="disabled" maxlength="8" accesskey="f" onblur="txt_bcsyjf_onblue();"
                                       class="validate[custom[number],max[90000]] text-input"> <span
                                       class="span_title">
                                    剩余积分</span> 
                                <input type="text" disabled="disabled" id="txt_syjf">
                            <?php } ?>
                            <span class="span_title">实际应收(s)</span>
                            <input type="number" id="txt_ss" disabled="disabled" style="color: red; font-size: 20px; height: 28px;">
                        </div>
                        <div class="div_client">
                            <span class="span_title">收款(k)</span>
                            <input type="number" id="int_sk"  onblur="sk_onblur();" accesskey="k" maxlength="11" class="validate[custom[number],max[200000]] text-input"> 
                            <span class="span_title">找零</span> 
                            <input type="text" disabled="disabled" id="zl" style="color: green; font-size: 20px; height: 28px;">
                        </div>
                        <div class="div_client" style="text-align: center;">
                            <span class="span_title">本次配方备注</span> <br>
                            <input type="text" id="txt_pfbz" maxlength="10" style="width: 190px;" >
                        </div>
                        <div id="div_history"></div>
                    </div>
                    <div id="div_prodInfo">
                        <div id="div_prodInfo_memu">
                            <span id="info_menu_l"> 
                                <span class="span_title">消费记录</span>
                                <select id="ply_history" onchange="getHistory()"  style="width: 362px; height: 28px;">
                                </select> 
                                <span class="span_title">电子邮件</span> 
                                <input type="text" id="email" class="validate[custom[email] text-input" maxlength="200" style="width: 230px">
                                <span class="span_title">修改</span> 
                                <input type="checkbox" id="changeMe" value="0"  onchange="initChangeState()" style="width: 30px;">

                            </span> 
                            <span id="info_menu_r"> 
                                <span class="span_title">对客户的备注信息</span>
                                <textarea id="note" maxlength="100" onblur="setNeedSaveClient();"></textarea>
                            </span>
                        </div>
                        <div id="div_saleList_title">
                            <span class=div_saleList_item1><b>编号</b> </span> 
                            <span class=div_saleList_item2><b>产品名称</b> </span> 
                            <span class=div_saleList_item><b>数量</b> </span> 
                            <span class=div_saleList_item><b>单价</b> </span> 
                            <span class=div_saleList_item><b>小计</b> </span> 
                            <span class=div_saleList_item><b>清除</b> </span>
                        </div>
                        <div id='div_saleList'></div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
