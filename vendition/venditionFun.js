function findClient() {
    if (isEmpty($('#cName').val())) {
        return;
    }
    $("#needSaveClient").val(true); // 默认输入的是新客户，在保存时需要保存客户的资料
    initChangeState(0);
    $.post("../lib/findClient.php", {
        cName: $('#cName').val()
    }, function (data) {
        if (data.indexOf("Error") < 0) {
            data = eval(data);

            // 在数据表中找到这个客户的信息
            if (data.length > 0) {
                //将过去的历史购物记录清空
                $("#div_saleList").empty();
                // 显示客户的资料
                $('#cName').val(data[0].cName);
                $('#email').val(data[0].mail);
                $('#point').val(data[0].jf);
                $('#txt_syjf').val(data[0].jf);
                $('#note').val(data[0].bz);
                $("#needSaveClient").val(false);

                // 如果客户有累计积分，则显示使用积分的输入框，否则禁止它。
                if (isEmpty(data[0].jf)) {
                    $("#txt_bcsyjf").attr("disabled", "disabled");
                } else {
                    $("#txt_bcsyjf").removeAttr("disabled");
                }
                // 下面读取此客户的购物记录
                $.post("../total/readVendition.php", {
                    readType: "histor",
                    cName: $("#cName").val(),
                    num: sys_historNum
                }, function (d) {
                    d = eval(d);
                    if (d.length === 0) {
                        return;
                    }
                    var str = "<option value=''>请选择</option>";
                    $(d).each(function (i) {
                        str += "<option value=" + this.vId + ">" + this.vTime + "</option>";
                    });
                    $("#ply_history").html(str);
                });
            } else { // 如果没有找到这个客户的资料
                $("#txt_bcsyjf").attr("disabled", "disabled"); // 将使用积分的输入框禁用
                init(); // 清空所有的输入项目
            }
        }
    });
}

/**
 * 当选择产品类型号，读取此类型下的所有产品
 * @param obj
 */
function getProduct(obj) {
    var tid = obj[ obj.selectedIndex].value;
    $.post("../total/readVendition.php", {typeId: tid, readType: "product"}, function (data) {
        data = eval(data);
        var str = "";
        $(data).each(function (i) {
            str += "<option value='";
            str += this.pId + "~";
            str += this.pName + "~";
            str += this.tId + "~";
            str += this.pPrice + "~";
            str += this.isSale + "~";
            str += this.starTime + "~";
            str += this.endTime + "~";
            str += this.sale + "~";
            str += this.jfdhbl + "~";
            str += "'>" + this.pName + "</option>";
        });
        $("#pId").html(str);
    });
}



/**
 * 在选择产品后，将它添加到下面的已选产品列表中
 */
function btn_AddtoList() {
    var err = "";

    if ($("#pId").val().length === 0) {
        err += "没有输入产品的名称<br>";
    }
    if (!checkRate($("#number").val())) {
        err += "没有输入数量<br>";
    }
    if (err.length > 0) {
        msg(err);
        return;
    }
    var num = parseInt($("#listCount").val()) + 1; // 将已添加产品的数量加1
    var pAry = $("#pId").val().split("~");

    $("#listCount").val(num); // 将产品数量保存到隐藏组件中

    addGoods(num.toString(), pAry[0], pAry[1], $("#number").val(), pAry[3], num, pAry[8]);
    sumAll(); // 计算总合计金额
    $("#tId").focus();

}
;

/**
 * 将用户购买的产品显示到下面的列表中
 * 
 *            a 购买产品的编号
 *            b 产品ID
 *            c 产品名称
 *            d 数量
 *            e 单价
 *            f 序列号
 *            g 积分兑换比例。如果为0，表示不积分
 */
function addGoods(a, b, c, d, e, f, g) {
    var recordId = "listCount_" + a;
    var str = "";
    str += "<div id=" + recordId + ">";
    str += "<input class=list_pid type=hidden value=" + b + ">"; // 产品编号
    str += "<span class=div_saleList_item1>" + f + "</span>";
    str += "<span class=div_saleList_item2>" + c + "</span>";
    str += "<span class=div_saleList_item><input class=list_number maxlength='8' value="
            + d + " onchange=changeNumber('#" + recordId + "')></span>"; // 数量
    str += "<span class=div_saleList_item><input class=list_price value=" + e
            + " disabled='disabled'></span>"; // 单价
    str += "<span class=div_saleList_item><input class=list_sum value="
            + (d * e).toFixed(sys_xsd) + " disabled='disabled'></span>"; // 小计
    str += "<span class=div_saleList_item><button  type=button  onclick=delReocrd('"
            + recordId + "') >删除</button> </span>";
    str += "<input type=hidden class=list_kjf value=" + g + ">";
    str += "</div>";
    $("#div_saleList").append(str);
}

/**
 * 删除已选择产品列表中的一条记录
 * 
 * @param {type}
 *            recordId 记录号
 */
function delReocrd(recordId) {
    $("#" + recordId).remove();
    sumAll(); // 重新计算总合计金额

}

/**
 * 如果修改了已选择产品列表中某记录的数量，则重新计算各项金额
 * 
 * @param {type}
 *            recordId
 */
function changeNumber(recordId) {
    var a = toNumber($(recordId).find(".list_price").val()); // 单价
    var b = toNumber($(recordId).find(".list_number").val()); // 数量
    $(recordId).find(".list_sum").val(a * b);
    sumAll(); // 重新计算总合计金额
}

/**
 * 计算选购产品的单项和总计价格
 */
function sumAll() {
    var lj = 0;
    jf = 0;
    $("#div_saleList>div").each(function (data) {
        var a = toNumber($(this).find(".list_price").val()); // 单价
        var b = toNumber($(this).find(".list_number").val()); // 数量
        lj += a * b;
        var jfdhbl = toNumber($(this).find(".list_kjf").val());
        if (jfdhbl > 0) {
            jf += (a * b / sys_jf_js) * jfdhbl;
        }
    });
    lj = lj.toFixed(sys_xsd);
    $("#txt_yfhj").val(lj);

    // 计算本次购物积分
    if (sys_jf_jsff == 1) {
        jf = Math.round(jf);
    } else {
        jf = Math.floor(jf);
    }
    $("#point_me").val(jf);

    //计算实收
    var ss = lj;
    if (sys_syjf !== 0) { // 如果系统参数设置为使用积分
        if (toNumber($("#point").val()) > 0) {
            var syjf = toNumber($("#txt_bcsyjf").val());
            var ss = lj - syjf * sys_jf_dhbl;
            if (ss < 0) {
                msg("由于购买商品的金额小于您准备使用的积分，所以系统自动将使用的积分进行了调整");
                syjf = syjf - Math.abs(ss) / sys_jf_dhbl;
                $("#txt_bcsyjf").val(syjf);
                $("#txt_syjf").val(toNumber($("#point").val()) - syjf);
                ss = 0;
            }
        }
    }
    $("#txt_ss").val(ss);
    checkZl(); // 重新计算找零金额
}

/**
 * 当输入用户付的钱数后，根据货款计算应找零数量
 */

function checkZl() {
    var resu = new Object(); // 用于存储返回的信息
    resu.err = false; // 默认返回没有问题
    resu.info = "";
    if (toNumber($("#int_sk").val()) === 0) { // 还没有输入收款数
        $("#zl").val("");
        return resu;
    }
    if ($("#txt_ss").val().trim() === '') {
        return resu;
    }

    sk = toNumber($("#int_sk").val());
    ss = toNumber($("#txt_ss").val());
    var zl = (sk - ss).toFixed(2); // 找零
    $("#zl").val(zl);
    if (zl < 0) {
        resu.err = true;
        resu.info += '输入的现金数量小于应收款金额，请检查！';
    } else {
        resu.err = false;
    }
    return resu;
}

/**
 * 设置状态，在保存购物信息的时候，也同时保存客户的信息
 */
function setNeedSaveClient() {
    $("#needSaveClient").val(true);
}

/**
 * 用户输入要使用的积分后，执行下面的操作
 */
function txt_bcsyjf_onblue() {
    var resu = checkJf(); // 检查与积分相关的项目是否正确
    if (!resu.err) {
        // 计算剩余积分
        $("#txt_syjf").val($("#point").val() - $("#txt_bcsyjf").val());
        // 计算应收金额
        if (toNumber($("#txt_yfhj").val()) > 0) {
            var syjf = toNumber($("#txt_bcsyjf").val()); // 在本次输入的“使用积分”
            var yf = toNumber($("#txt_yfhj").val()); // 应付金额
            var ss = yf - (syjf * sys_jf_dhbl); // 实收 - （使用积分*积分兑换比例）
            $("#txt_ss").val(ss);
            checkZl(); // 重新计算找零金额
        }
    } else {
        if (resu.info.length > 0) {
            msg(resu.info);
            $("#txt_bcsyjf").focus();
        }
    }
}

/**
 * 检查输入的积分是否正确. 如果检查通过，返回空字符串。否则返回错误信息
 */
function checkJf() {
    var resu = new Object(); // 用于存储返回的信息
    resu.err = false; // 默认返回没有问题
    resu.info = "";
    syjf = toNumber($("#txt_bcsyjf").val());
    if (syjf > 0) {
        var lsjf = toNumber($("#point").val());
        if (lsjf === 0) {
            resu.err = true;
            resu.info += "还没有可以使用的积分";
        } else {
            if (syjf > lsjf) {
                resu.err = true;
                resu.info += '本次使用的积分不能大于历史累分,请检查！';
            }
        }
    }
    return resu;
}

function txt_ss_onblue() {
    var resu = checkSsje();
    if (resu.err) {
        if (resu.info.length > 0) {
            msg(resu.info);
        }
    } else {
        checkZl(); // 重新计算找零金额
    }

}

/**
 * 检查实收金额加上积分是否大于应收金额 如果没有任何问题，则返回字符"true" ,否则返回相关错误信息。如果err为空，表示有未输入的项目
 * 
 * @returns
 */
function checkSsje() {
    var resu = new Object(); // 用于存储返回的信息
    resu.err = false; // 默认返回没有问题
    resu.info = "";

    var ss = toNumber($("#txt_ss").val());
    var yf = toNumber($("#txt_yfhj").val());

    // 将实收金额和积分加起来进行检查
    if (sys_syjf !== 0) { // 如果系统参数设置为使用积分
        if (toNumber($("#txt_bcsyjf").val()) > 0) {
            ss += toNumber($("#txt_bcsyjf").val()) * sys_jf_dhbl;
        }
    }
    c = yf - ss; // 应付 - 实收（包含积分）之间的差
    if (c < 0) {
        resu.err = true;
        resu.info += "实收金额多于应收金额，多收客户的钱了。请检查实收金额输入是否正确<br>";
        if (sys_syjf !== 0) {
            resu.info += "实收金额=应付金额-积分";
        }
    }
    return resu;
}

/**
 * 帮助计算需要找零的金额
 */
function sk_onblur() {
    resu = checkZl();
    if (resu.err) {
        msg(resu.info);
        $("int_sk").focus();
    }
}

function toNewCreate() {
    init();
    $("#cName").val("");
    $("#cName").focus();
}

function init() {
    $("#number").val("");
    $("#txt_yfhj").val("");
    $("#int_sk").val("");
    $("#point_me").val("");
    $("#point").val("");
    $("#txt_ss").val("");
    $("#txt_bcsyjf").val("");
    $("#txt_syjf").val("");

    $("#zl").val("");
    $("#ply_history").empty();
    $("#email").val("");
    $("#note").val("");
    $("#div_saleList").empty();
    $("#txt_bcsyjf").attr("disabled", "disabled");
    $("#txt_pfbz").val("");
    initChangeState(0);
}

function toSave() {
    // 检查输入合法性
    if (!jQuery("#frm_input").validationEngine('validate')) {
        msg("输入内容没有通过检查");
        return;
    }
    var yf=trim($("#txt_yfhj").val());
    if ((yf=="") || (yf==0)){
        msg("没有需要保存的记录");
        return;        
    }
    var err = "";
    if (sys_syjf != 0) {
        err += checkJf().info; // 检查与积分相关的项目是否正确
    }
    err += checkSsje().info;
    err += checkZl().info;
    if (trim(err).length > 0) {
        msg(err);
        return;
    }
    sumAll(); // 重新计算一次总合计金额

    // 如果新增或修改了客户信息，则保存它
    var str = "|~~|";
    if ($("#needSaveClient").val() === "true") {
        str = $("#cName").val() + "|~|";
        str += $("#email").val() + "|~|";
        if (sys_syjf != 0) {
            str += (toNumber($("#point_me").val()) + toNumber($("#txt_syjf").val())) + "|~|";
        } else {
            str += (toNumber($("#point_me").val()) + toNumber($("#point").val())) + "|~|";
        }
        str += $("#note").val() + "|~~|";
    }

    // 保存产品销售信息到数据表
    // 下面是销售汇总
    str += $("#cName").val() + "|~|";
    str += toNumber($("#txt_yfhj").val()) + "|~|";
    str += toNumber($("#txt_ss").val()) + "|~|";
    if (sys_syjf != 0) {
        str += toNumber($("#txt_bcsyjf").val()) + "|~|";
    } else {
        str += "0|~|";
    }
    str += $("#txt_pfbz").val() + "|~|";
    str += toNumber($("#point_me").val()) + "|~~|";
    // 下面是产品明细
    $("#div_saleList div").each(function (record) {
        str += toNumber($(this).find(".list_pid").val()) + "|~|";
        str += toNumber($(this).find(".list_number").val()) + "|~|";
        str += toNumber($(this).find(".list_price").val()) + "|~|";
        str += toNumber($(this).find(".list_sum").val()) + "|~|*";
    });
    str = str.substr(0, str.length - 4);
    // 积分
    if (sys_syjf != 0) {
        str += "|~~|" + (toNumber($("#txt_syjf").val()) + toNumber($("#point_me").val()));
    } else {
        str += "|~~|" + (toNumber($("#point_me").val()) + toNumber($("#point").val()));
    }
    $.post("./saveVendition.php", {
        info: str,
        changeState:$("#changeMe").val(),
        ply_history:$("#ply_history").val()
    }, function (data) {
        if (data.indexOf("Error") < 0) {
            init();
            $("#cName").val("");
            $("#cName").focus();
            msg("保存成功！");
            initChangeState(0); //取消修改状态
        } else {
            msg('保存时出现问题,具体错误为：<br>' + data);
        }
    });

}



/**
 * 当选择购买的历史记录时，显示当时的产品列表
 */
function getHistory() {
    if ($("#ply_history").val().length === 0) {
        return;
    }
    initChangeState(0);
    $("#div_saleList").empty();	//清空用户选择产品列表
    $("#listCount").val(0);
    $.post("../total/readVendition.php", {readType: "vend", vId: $("#ply_history").val()}, function (d) {
        d = eval(d);
        if (d.length === 0) {
            return;
        }
        $("#txt_pfbz").val(d[0].bz);
        $(d).each(function (i) {
            $("#listCount").val(i + 1);
            addGoods(i + 1, this.pId, this.pName, this.number, this.pPrice, i + 1, this.jfdhbl);
        });
        sumAll();	//统计应付金额
        if($("#ply_history").find("option:selected").text().indexOf("---")>0 )
            $("#txt_pfbz").val($("#ply_history").find("option:selected").text().split("---")[1]);
    });
}

function toClean() {
    $('input').val("");
    $('select').val('');
    $("#div_saleList").html('');
    $("#needSaveClient").val(false);
    $("#listCount").val(0);
    $("#errMsg").val('');
    initChangeState(0);
    $("#cName").focus();
}

function initChangeState(state) {
    if(state===undefined){
        if ($("#changeMe").attr("checked") ===undefined)
            state=0;
        else
            state=1;
    }
    if (state == 0) {
        $("#changeMe").val("0");
        $("#changeMe").attr("checked", false);
    } else {
        $("#changeMe").val("1");
        $("#changeMe").attr("checked", true);
    }
}