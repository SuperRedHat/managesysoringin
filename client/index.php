<?php
include '../checkLogin.php';
include '../config.php';
include '../lib/tools.php';
include '../lib/db.php';
//控制菜单上显示哪些按钮
$btn_add = true;
$btn_save = true;
$btn_del = $sys_client_canDelClient;
$btn_clean = true;
$findClient = true;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 客户信息管理</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
        <link rel="stylesheet" type="text/css" href="./client.css">
        <link rel="stylesheet" type="text/css" href="../components/Validation-Engine/css/validationEngine.jquery.css">

        <script type="text/javascript" src="../components/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="../components/Validation-Engine/js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="../components/Validation-Engine/js/languages/jquery.validationEngine-zh_CN.js"></script>

        <script type="text/javascript" src="../lib/tools.js"></script>
        <script type="text/javascript" src="./clientFun.js"></script>
        <script type="text/javascript">
            function showClient(cName) {
                document.getElementById("frm_input").reset();
                $("#cName").val(cName);
                findClient();
            }
            $(document).ready(function () {
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
                <input type="hidden" id="needSave" value="false">
                <div id="div_list">
                    <?php
                    if ($sys_client_showNum > 0) {
                        $num = 'limit ' . $sys_client_showNum;
                    } else {
                        $num = '';
                    }
                    $rs = readdbByRs("d_client", "clientName,note", "1=1 order by registryTime desc " . $num);

                    if (count($rs) > 0) {
                        $str = "<ul class=clientList>";
                        foreach ($rs as $item) {
                            $str.="<li cName={$item['clientName']}><a href=# onclick=showClient('" . $item['clientName'] . "') class='a_clientList'>" . $item['clientName'] . "&nbsp;" . $item['note'] . "</a></li>";
                        }
                        $str.="</ul>";
                        echo $str;
                    }
                    ?>
                </div>
                <div id=div_body>		
                    <div class="div_input_title"> 客户名称</div>
                    <div class="div_input"> <input type="text" id="cName" maxlength="30" class="validate[required] text-input" onblur="findClient();" onkeydown="findClientToo(event);"> </div>

                    <div class="div_input_title"> 电子邮件</div>
                    <div class="div_input"> <input type="text" id="mail" maxlength="200" class="validate[custom[email]] text-input" > </div>

                    <div class="div_input_title"> 注册时间</div>
                    <div class="div_input"> <input type="text" id="reg" disabled="true"> </div>

                    <div class="div_input_title"> 累计积分</div>
                    <div class="div_input"> <input type="text" id="jf" maxlength="11" <?php echo $sys_client_disModifyPoint; ?>  class="validate[custom[integer]] text-input" > </div>

                    <div class="div_input_title"> 备注</div>
                    <div class="div_input"> <textarea type="text" id="bz" maxlength="200">  </textarea> </div>
                </div>
            </form>
        </div>
    </body>
</html>