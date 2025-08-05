<?php
    include '../checkLogin.php';
    include '../config.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 查询销售数据</title>
        <link rel="stylesheet" type="text/css" href="../iamvip.css">
   </head>
    <body>
        <div id=div_main_menu>
            <ul>
                <li><a href="sales.php" target="_self">1. 查询销售额</a></li>
                 <li><a href="prod.php" target="_self">2. 查询产品销售情况</a></li>
                 <li><a href="client.php" target="_self">3. 查询客户详细信息</a></li>
                 <li><a href="clientInfo.php" target="_self">4. 查询所有客户</a></li>
                 <li><a href="changeInfo.php" target="_self">5. 查询销售修改记录</a></li>                 
            </ul>
        </div>
    </body>
</html>
