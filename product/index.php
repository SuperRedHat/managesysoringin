<?php
include '../checkLogin.php';
include '../config.php';
include './productFun.php';
include '../lib/tools.php';
$btn_add = true;
$btn_save = true;
$btn_del = true;
$btn_clean=false;
$findClient = false;  //是否可以按F6查找客户信息
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 产品信息管理</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
        <link rel="stylesheet" type="text/css" href="./product.css">
        <link rel="stylesheet" type="text/css" href="../components/easyui132/themes/<?php echo $sys_style; ?>/easyui.css">
        <link rel="stylesheet" type="text/css" href="../components/Validation-Engine/css/validationEngine.jquery.css">      

        <script type="text/javascript" src="../components/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript" src="../components/Validation-Engine/js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="../components/Validation-Engine/js/languages/jquery.validationEngine-zh_CN.js"></script>
        <script type="text/javascript" src="../lib/tools.js"></script>
        <script type="text/javascript" src="./productFun.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#div_productType").accordion();
                jQuery("#frm_input").validationEngine('attach');
                $(".a_li").mouseover(function (e) {
                    $(this).addClass('selectColor');
                });
                $(".a_li").mouseleave(function (e) {
                    $(this).removeClass('selectColor');
                });
            });
        </script>
    </head>
    <body>
        <div id=div_main>
            <?php include_once '../menu.php'; ?>
            <div id=div_body>
                <div id=div_left_Accordion>
                    <div id=div_productType class="easyui-accordion" >
                        <?php showAccordion(); ?>
                    </div>
                </div>
                <div id=div_body_right>
                    <form id=frm_input>	
                        <div class="divRow">
                            <span class=div_input_title>产品ID</span>
                            <span class=div_input>
                                <input  type="text"  id="pId" value=""  maxlength="40" disabled="true" style="background-color: #cccccc">
                            </span>				
                        </div>
                        <div class="divRow">
                            <span class=div_input_title>所属分类</span>
                            <span class=div_input><select id=tId onChange="productTypeIdChange(this);"> <?php fillSelect('c_productType', 'productTypeName', 'productTypeId', 'isUse=1'); ?></select></span>
                        </div>
                        <div class="divRow">
                            <span class=div_input_title>产品名称</span>
                            <span class=div_input>
                                <input  type="text"  id="pName"  maxlength="40" class="validate[required] text-input" >
                            </span>				
                        </div>
                        <div class="divRow">
                            <span class=div_input_title>产品简称</span>
                            <span class=div_input>
                                <input type="text"  id="pShort"  maxlength="10" class="validate[required] text-input" >
                            </span>				
                        </div>
                        <div class="divRow">
                            <span class=div_input_title>单价</span>
                            <span class=div_input><input type="text" id="pPrice"  class="validate[required,custom[number],max[1000]] text-input" ></span>
                        </div>
                        <div class="divRow">
                            <span class=div_input_title>积分兑换比例</span>
                            <span class=div_input> 
                                <input type="text" id=jfdhbl class="validate[required,custom[number],max[1000]] text-input" title="每<?php echo $sys_jf_js ?>元钱兑换多少积分>">
                            </span>
                        </div>
                        <div class="divRow">
                            <span class=div_input_title>启用</span>
                            <span class=div_input> 
                                <input type="checkbox" id=useIt  checked>
                            </span>
                        </div>
                        <div class="divRow">
                            <span class=div_input_title>打折</span>
                            <span class=div_input> 
                                <input type="checkbox" id=isSale  onclick='showDiv_sale();' >
                            </span>
                        </div>

                        <div id=div_sale>
                            <div class="divRow">
                                <span class=div_input_title>打折起始时间</span>
                                <span class=div_input> 
                                    <input type="date"  id=starTime  class="validate[custom[date],future[NOW]] text-input"> 
                                </span>
                            </div>
                            <div class="divRow">
                                <span class=div_input_title>打折结束时间</span>
                                <span class=div_input> 
                                    <input type="date" id="endTime" class="validate[custom[date],future[NOW]] text-input"> 
                                </span>
                            </div>
                            <div class="divRow">
                                <span class=div_input_title>优惠幅度</span>
                                <span class=div_input><input  type="text" id="sale" class="validate[custom[number]] text-input" ></span>
                            </div>
                        </div>                        
                    </form>			
                </div>
            </div>		
        </div>

    </body>

</html>