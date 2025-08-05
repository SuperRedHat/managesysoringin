<?php
//include '../checkLogin.php';
include '../config.php';
include '../lib/tools.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 查询销售数据</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
        <link rel="stylesheet" type="text/css" href="../components/easyui132/themes/<?php echo $sys_style; ?>/easyui.css">
        <script type="text/javascript" src="../components/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript" src="../lib/tools.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                var d = new Date();
                //mysql  var sj = d.getFullYear() + "/0" + (d.getMonth() + 1).toString() + "/" + d.getDate();
                var sj = d.getFullYear() + "/" + ('0' + (d.getMonth() + 1).toString()).slice(-0, 2) + "/" + ('0' + (d.getDate() + 1).toString()).slice(-0, 2);
                $("#qssj").datebox("setValue", sj);
                $("#jzsj").datebox("setValue", sj);

                $("#btn_go").click(function (data) {
                    $.post("./prod_go.php", {
                        tId: $("#tId").val(),
                        qssj: $("#qssj").datebox("getValue"),
                        jzsj: $("#jzsj").datebox("getValue")
                    }, function (data) {
                        if (data.indexOf("Error") >= 0) {
                            msg("data");
                            return;
                        }
                        //将json转换为表格
                        data = eval(data);
                        $('#dg').datagrid("loadData", data);

                        //计算汇总数
                        var hj = 0;
                        for (var item in data) {
                            hj += parseFloat(data[item].je);
                        }
                        msg("金额总计：" + hj + " 元", 0);
                    });//end post 
                }); //end click
            });//end ready

        </script>
    </head>
    <body>
        <div id=div_main>
            <div id="div_menu" style="padding-top: 10px; height: 95px;">
                产品类型: &nbsp;<select id="tId"> <?php fillSelect('c_productType', 'productTypeName', 'productTypeId', 'isUse=1'); ?></select>
                &nbsp;&nbsp;&nbsp;&nbsp;起始时间：&nbsp;<input class="easyui-datebox" id="qssj">
                &nbsp;&nbsp;&nbsp;&nbsp;截止时间：&nbsp;<input class="easyui-datebox" id="jzsj">
                &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="btn_go">查询</button>
                <div id="infoWin" style="height:40px;margin-top: 15px;"> </div>
            </div>  <!--end div_menu-->
            <div id="div_dataShow">
                <table class="easyui-datagrid" style="width:1086px;height:600px" data-options="singleSelect:true,collapsible:true,required:true" id="dg">
                    <thead>
                        <tr>
                            <th data-options="field:'mc',width:150">产品名称</th>
                            <th data-options="field:'bs',width:80">销售次数</th>
                            <th data-options="field:'sl',width:150">销售量</th>
                            <th data-options="field:'je',width:150">销售额</th>
                        </tr>
                    </thead>
                </table>
            </div>  <!--end div_dataShow-->
        </div> <!-- end div_main-->
    </body>
</html>