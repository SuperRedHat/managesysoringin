<?php
if (!isset($_SESSION)) {
    session_start();
}
include './checkLogin.php';
include './config.php';
include './lib/tools.php';
global $sys_windowTitle;
$err = "";
$old_mm=post("old_mm");
if ($old_mm!=NULL) {
    if (strlen(trim($old_mm)) == 0) {
        $err = "没有输入旧密码，或者旧密码不正确！";
        $old_mm = sha1($old_mm);
    } else {
        $mm = sha1(post("mm"));
        if (strlen(trim($mm)) == 0) {
            $err = "没有输入新密码 ！";
        } else {
            include './lib/db.php';
            $yh = $_SESSION["czydm"];
            $sql = "select * from d_czydm where  czydm='$yh' and czymm='$old_mm' and uses=1";
            $rs = execSql($sql);
            if (count($rs) > 0) {
                $sql = "update dm_czy set czymm='$mm' where czydm='$yh'";
                runSql($sql);
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ./index.php');
            } else {
                $err = "Error:错误的旧密码！";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 修改密码</title>
        <style type="text/css">
            body{
                background-color: #ffffff;
                font-size: 12px;
            }

            #indexBar{
                width: 400px;
                text-align: center;
                height: 200px;
                background-color: #eeeeee;
                border-bottom-color: red;
                border-bottom-style: solid;
                border-bottom-width: 1px;
                display: block;
                position:absolute;
                left:50%;
                top:50%;
                margin:-100px 0 0 -200px;
            }

            .inputBox{
                width: 300px;
                height: 40px;
                display: inline-block;
                padding-left: 10px;
                padding-top: 10px;
            }

            #item{
                width: 150px;
            }

            .lab{
                display:inline-block;
                width: 40px;
                text-align: left;
            }

        </style>
        <script src="components/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script src="lib/tools.js" type="text/javascript"></script>
    </head>
    <body>
        <form action="changePwd.php" method="post">
            <div id=indexBar>
                <div class=inputBox> <?php echo $err; ?> </div>
                <div class=inputBox><span class=lab>旧密码</span><input type="text"  id="old_mm"  name="old_mm" class="item" />	</div>
                <div class=inputBox><span class=lab>新密码</span><input type="text"  id="mm"  name="mm"  class="item" /></div>
                <div class=inputBox><input type="submit" value="修改"></span>	</div>
            </div>
        </form>
    </body>
</html>