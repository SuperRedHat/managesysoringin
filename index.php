<?php
if (!isset($_SESSION)) {
    session_start();
}
include './checkLogin.php';
if (isset($_SESSION['czyjb']) && $_SESSION['czyjb'] != '0')
    header('Location: /vendition');
include "./config.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 首页 </title>
        <link rel="stylesheet" type="text/css" href="iamvip.css">
        <style type="text/css">
            body{
                background-color: #144167;
            }
            #div_main1{
                width: 1000px;
                height: 210px;
                display: block;
                text-align: center;
                padding-top: 20px;
                position:absolute;
                left:50%;
                top:50%;
                margin:-200px 0 0 -500px;  
                border-bottom: 1px #447c95 solid;
            }

            img{
                width: 64px;
                display: block;
                border: none;
                margin: auto auto;
            }

            span{
                width: 104px;
                height: 104px;
                display: inline-block;
                text-align: center;
                margin:40px 20px 0px 0px;
            }

            a{
                display: inline-block;
                height: 104px;
                width: 104px;
            }

            label{
                width: 104px;
                height: 30px;
                text-align: center;
                display: block;
                margin-top: 10px;
                color:#fff;
                font-size: 24px;
            }
            .mainIcon{
                padding: 15px;
                margin-right: 25px;
                display: inline-block;
            }
            
        </style>
        <script src="./components/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function (e) {
                $(".mainIcon").mouseover(function () {
                    $(this).addClass("iconBorder");
                });
                $(".mainIcon").mouseout(function () {
                    $(this).removeClass("iconBorder");
                });
            });
        </script>
    </head>
    <body>
        <div id=div_main1>
            <span class="mainIcon">
                <a href="vendition/" target="_blank" >
                    <img src="./img/vendition.png">
                    <label class='icon-txt'>销售管理</label>
                </a>
            </span>
            <span class="mainIcon">
                <a href="client/" target="_blank" >
                    <img src="./img/client.png">
                    <label class='icon-txt'>客户信息</label>
                </a>
            </span>
            <span class="mainIcon">
                <a href="product/" target="_blank" >
                    <img src="./img/product.png">
                    <label class='icon-txt'>产品信息</label>
                </a>
            </span>
            <span class="mainIcon">
                <a href="total/" target="_blank" >
                    <img src="./img/total.png">
                    <label class='icon-txt'>数据查询</label>
                </a>
            </span>
            <span  class="mainIcon">
                <a href="changePwd.php" target="_blank" >
                    <img src="./img/pwd.png">
                    <label class='icon-txt'>修改密码</label>
                </a>
            </span>
        </div>


    </body>
</html>