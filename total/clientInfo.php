<?php  
    include '../checkLogin.php';
    include '../config.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 查询客户信息</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
        <link rel="stylesheet" type="text/css" href="../components/easyui132/themes/<?php echo $sys_style; ?>/easyui.css">
        <script type="text/javascript" src="../components/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../components/easyui132/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript" src="../lib/tools.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                var d=new Date();
                var sj=d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate();
                $("#qssj").datebox("setValue",sj);
                $("#jzsj").datebox("setValue",sj);
                
                $("#btn_go").click(function(data) {
                    $.post("./clientInfo_go.php", {
                        cName: $("#cName").val()
                    }, function(data) {
                        if (data.indexOf("Error") >= 0) {
                            msg(data);
                            return;
                        }
                        //将json转换为表格
                        data = eval(data);
                        msg("共 "+data.length+" 条记录",0);
                        $('#dg').datagrid("loadData",data);
                    });//end post 
                }); //end click
            });//end ready

        </script>
    </head>
    <body>
        <div id=div_main>
            <div id="div_menu" style="padding-top: 10px; height: 95px;">
                客户名称: <input type="text" id="cName" maxlength="30" title="如果为空，则统计所有客户的数据">
                <button type="button" id="btn_go">查询</button>
                 <div id="infoWin" style="height:40px;margin-top: 15px;"> 如客户信息为空，表示查所有客户</div>
            </div>  <!--end div_menu-->
            <div id="div_dataShow">
                <table class="easyui-datagrid" style="width:1086px;height:600px" data-options="singleSelect:true,collapsible:true,required:true" id="dg">
                    <thead>
                        <tr>
                            <th data-options="field:'cName',width:150">客户名称</th>
                            <th data-options="field:'zcsj',width:150">时间</th>
                            <th data-options="field:'je',width:150">合计消费金额</th>
                            <th data-options="field:'jf',width:100">积分</th>
                             <th data-options="field:'note',width:480">备注</th>
                        </tr>
                    </thead>
                </table>


            </div>  <!--end div_dataShow-->
        </div> <!-- end div_main-->
    </body>
</html>