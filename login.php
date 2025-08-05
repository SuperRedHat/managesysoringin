<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once  dirname(__FILE__).'/config.php';
include_once  dirname(__FILE__).'/lib/tools.php';
global $sys_windowTitle;

$_SESSION["islogin"] = false;
$err = "";
try {
    $yh = post("yh");
    if ($yh!=NULL) {  //收到了用户登录的数据
        $mm=post("mm");
        if ($mm!=null) {
            $mm = sha1($mm);
            include_once  './lib/db.php';
            $sql = "select * from d_czydm where  czydm='$yh' and czymm='$mm' and uses=1";
            $rs = execSql($sql);
            if (count($rs) > 0) {
                $_SESSION["czydm"] = $yh;
                $_SESSION["czyjb"]=$rs[0]["czyjb"];
                $_SESSION["islogin"] = true;
                header('HTTP/1.1 301 Moved Permanently');
                if ($rs[0]["czyjb"]==0)
                    header('Location: ./index.php');
                else
                    header('Location: /vendition');
                exit;
            } else {
                throw new Exception("Error:错误的用户名和密码！");
            }
        } else {
            throw new Exception("Error:错误的用户名和密码！");
        }
    }
} catch (Exception $e) {
    $_SESSION["czydm"] = "";
    $err = $e->getMessage();
    
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $sys_windowTitle; ?> - 登录 </title>
        <style type="text/css">
            body{
                background-color: #00458a;
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
                width: 390px;
                height: 40px;
                display: block;
                padding-left: 10px;
                padding-top: 10px;
            }

            #item{
                width: 150px;
                display: inline-block;
            }

            .lab{
                display:inline-block;
                width: 60px;
                height: 20px;
                text-align: left;
                margin-left: 10px;
            }

        </style>
        <script src="components/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script src="lib/tools.js" type="text/javascript"></script>
    </head>
    <body>
        <form action="./login.php" method="post">
            <div id=indexBar>
                <div class=inputBox> <?php echo $err; ?> </div>
                <div class=inputBox><span class=lab>名&nbsp;&nbsp;称</span><input type="text"  id="yh"  name="yh" class="item" />	</div>
                <div class=inputBox><span class=lab>密&nbsp;&nbsp;码</span><input type="password"  id="mm"  name="mm"  class="item" /></div>
                <div class=inputBox><span><input type="submit" value="登录"></span>	</div>
            </div>
        </form>
    </body>
</html>