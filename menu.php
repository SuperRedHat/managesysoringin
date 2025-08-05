<script type="text/javascript">
    $(document).ready(function () {
        //当用户修改任何项目后，将当前状态设置为“修改”
        $("input,textarea").keydown(function () {
            $("#isChange").val("true");
        });

        jQuery("#frm_input").validationEngine('attach');

        $("body").keydown(function (e) {
            if (e.srcElement.type === "button") {
                return;
            }
            switch (e.keyCode) {
                case 13:
                    e.keyCode = 9;
                    break;
<?php if ($findClient) { ?>
                    case 117:   //F6
                        toFindClientInfo();
                        e.keyCode = 0;
                        e.cancelBubble = true;
                        break;
<?php } ?>
<?php if ($btn_add) { ?>
                    case 118:
                        toNewCreate();  //按F7键
                        e.keyCode = 0;
                        e.cancelBubble = true;
                        return false;
                        break;
<?php } ?>
<?php if ($btn_save) { ?>
                    case 119:
                        toSave(); //按F8键
                        e.keyCode = 0;
                        e.cancelBubble = true;
                        return false;
                        break;
<?php } ?>
<?php if ($btn_del) { ?>
                    case 120:
                        toDel(); //按F9键
                        e.keyCode = 0;
                        e.cancelBubble = true;
                        return false;
                        break;
<?php } ?>
                default:
                    break;
            }//end switch
        }); //end keydown
        //
        //设置按钮的样式
        $(".menuBtn").mouseover(function (data) {
            $(this).addClass("iconBorder");
        });
        $(".menuBtn").mouseout(function (data) {
            $(this).removeClass("iconBorder");
        });
        $(".menuBtn").mousedown(function (data) {
            $(this).find("img").css("width", "65px");
            $(this).find("img").css("height", "55px");
        });
        $(".menuBtn").mouseup(function (data) {
            $(this).find("img").css("width", "70px");
            $(this).find("img").css("height", "60px");
        });

        /**
         * 如果没有看清错误提示，那么点击错误提示区域，会重新显示最后一条错误信息
         */
        $("#infoWin").click(function (e) {
            if (isEmpty($("#infoWin").html())) {
                $("#infoWin").html($("#errMsg").val());
            } else {
                $("#infoWin").html("");
            }
        })

    });//end ready

    function toFindClientInfo() {
        if (isEmpty($("#cName").val())) {
            return;
        }
        $.post("../total/findClientInfo.php", {cName: $("#cName").val()}, function (data) {
            if (isEmpty(data)) {
                msg("没有查询到此客户的信息");
            } else {
                if (data.indexOf("Error") > 0) {
                    msg("查询客户信息时出错");
                } else {
                    data = eval(data);
                    var str = $("#cName").val() + "客户共购物：" + data[0].gwcs + " 次 \n";
                    str += "合计购物金额：" + data[0].hjje + " 元 \n";
                    str += "共使用积分：" + data[0].syjf + " 点 \n";
                    str += "首次购物时间：" + data[0].dyc + "\n";
                    str += "最后一次购物时间:" + data[0].zyhy;
                    alert(str);
                }
            }
        });
    }


</script>
<input type="hidden" id="isChange" value="false">
<div id=div_menu>
    <div id="div_menu_btn">
        <?php
        $key = "";
        if ($findClient) {
            $key .= " <li>F6 查询</li>";
        }
        if ($btn_add) {
            echo '<span id="btn_add" class="menuBtn" title="新建" ><img src="../img/new.png" onclick="toNewCreate()"></span>';
            $key .= " <li>F7 新建</li>";
        }

        if ($btn_save) {
            echo '<span id="btn_save" class="menuBtn" title="保存"><img src="../img/save.png" onclick="toSave()"></span>';
            $key .= " <li>F8 保存</li>";
        }

        if ($btn_del) {
            echo '<span id="btn_del" class="menuBtn" title="删除" ><img src="../img/del.png" onclick="toDel()"></span>';
            $key .= " <li>F9 删除</li>";
        }
        if ($btn_clean) {
            echo '<span id="btn_clean" class="menuBtn" title="清除输入的内容，但不删除它" ><img src="../img/clean.png" onclick="toClean()"></span>';
        }
        ?>     
    </div>
    <div id="keyWin">
        <ul>
            <?php echo $key; ?>            
        </ul>
    </div>
    <div id="infoWin"> </div>
    <div style="clear: both"></div>

</div>