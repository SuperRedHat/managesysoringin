<?php
    include '../lib/tools.php';
    include '../checkLogin.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>查询销售数据</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
        <style type="text/css">
            #div_dataShow{
                text-align: left;
                min-height: 540px;
                padding: 30px;
            }
            
        </style>
        <script type="text/javascript" src="../components/jquery-1.8.3.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("#btn_go").click(function(data) {
                    $.post("./client_go.php", {
                        cName: $("#cName").val(),
                    }, function(data) {
                        if (data.indexOf("Error") >= 0) {
                            msg("data");
                            return;
                        }
                        $("#div_dataShow").html(data);
                    });//end post 
                }); //end click
            });//end ready

        </script>
    </head>
    <body>
        <div id=div_main>
            <div id="div_menu" style="padding-top: 40px; height: 65px;">
                &nbsp;&nbsp;&nbsp;&nbsp;客户名称：<input type="text" maxlength="30" id="cName">
                &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="btn_go">查询</button>
            </div>  <!--end div_menu-->
            <div id="div_dataShow">

            </div>  <!--end div_dataShow-->
        </div> <!-- end div_main-->
    </body>
</html>