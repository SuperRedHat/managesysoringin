<?php
include '../checkLogin.php';
include '../config.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 查询数据修改记录</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
        <link rel="stylesheet" type="text/css" href="../components/easyui132/themes/<?php echo $sys_style; ?>/easyui.css">
        <script type="text/javascript" src="../components/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript" src="../lib/tools.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                var d = new Date();
                // mysql var sj=d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate();
                var sj = d.getFullYear() + "/" + ('0' + (d.getMonth() + 1).toString()).slice(-0, 2) + "/" + ('0' + (d.getDate() + 1).toString()).slice(-0, 2);
                $("#qssj").datebox("setValue", sj);
                $("#jzsj").datebox("setValue", sj);

                $("#btn_go").click(function (data) {
                    $.post("./change_log_go.php", {
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
                    });//end post 
                }); //end click
            });//end ready

        </script>
    </head>
    <body>
        <div id=div_main>
            <div id="div_menu" style="padding-top: 10px; height: 90px;">
                &nbsp;&nbsp;&nbsp;&nbsp;起始时间：<input class="easyui-datebox" id="qssj">
                &nbsp;&nbsp;&nbsp;&nbsp;截止时间：<input class="easyui-datebox" id="jzsj">
                &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="btn_go">查询</button>
                <div id="infoWin" style="height:40px;margin-top: 15px;"> </div>
            </div>  <!--end div_menu-->
            <div id="div_dataShow">
                <table class="easyui-datagrid" style="width:1086px;height:600px" data-options="singleSelect:true,collapsible:true,required:true" id="dg">
                    <thead>
                        <tr>
                            <th data-options="field:'vid',width:50">流水号</th>
                            <th data-options="field:'ctime',width:130">修改时间</th>
                            <th data-options="field:'vold',width:410">原记录</th>
                            <th data-options="field:'vnew',width:410">新记录</th>
                            <th data-options="field:'czymc',width:70,align:'right'">操作人</th>
                        </tr>
                    </thead>
                </table>


            </div>  <!--end div_dataShow-->
        </div> <!-- end div_main-->
    </body>
</html>