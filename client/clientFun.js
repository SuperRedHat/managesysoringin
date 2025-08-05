function toNewCreate() {
    if ($("#isChange").val() === "true") {
        if (confirm('修改的内容还没有保存，你确定要放弃这些内容吗？')) {
            document.getElementById("frm_input").reset();
            $("#isChange").val("false");
            $("#cName").focus();
              jQuery("#frm_input").validationEngine('hide');
        }
    } else {
        document.getElementById("frm_input").reset();
        $("#isChange").val("false");
        $("#cName").focus();
        jQuery("#frm_input").validationEngine('hide');
    }
}


function toSave() {
    if(!jQuery("#frm_input").validationEngine('validate')){
        return;
    }
    $.post("./saveClient.php", {
        cName: $("#cName").val(),
        mail: $("#mail").val(),
        reg: $("#reg").val(),
        jf: $("#jf").val(),
        bz: $("#bz").val()
    }, function(data) {
        if (data.indexOf("Error") < 0) {
            msg('保存用户成功', 2000);
            $("#isChange").val("false");
            toNewCreate();
        } else {
            msg("保存用户时出错:<br>" + data, 5000);
        }
    }
    );
}

function toDel() {
    if (trim($("#cName").val()).length === 0) {
        return;
    }
    if (confirm('你确定要删除这条记录?')) {
        $("[cname="+$("#cName").val()+"]").remove();
        $.post('../lib/delRecord.php', {type: "client", cName: $("#cName").val()}, function(data) {
            if (data.indexOf("Error") < 0) {
                document.getElementById("frm_input").reset();
                msg('记录已成功删除',2000);
            } else {
                msg("删除记录时出错:<br>"+data);
            }
        });
    }
}

function toClean(){
    $("#div_body input").val('');
    $("#div_body textarea").val('');
    $("#needSave").val(false);
}


function findClient() {
    if (trim($("#cName").val()).length === 0) {
        return false;
    }
    $.post("../lib/findClient.php", {cName: $('#cName').val()},
    function(data) {
        if (data.indexOf("Error") < 0) {
            data = eval(data);
            if (data.length === 0) {
                $('#mail').val("");
                $("#reg").val("");
                $('#jf').val(0);
                $('#bz').val("");
                $("#needSave").val(true);	//由于客户信息不在数据表中，所以设置状态，在保存数据的时候，同时保存客户的信息
                msg("将创建一个新客户")
            } else {
                msg("已成功加载客户信息");
                $('#cName').val(data[0].cName);
                $('#mail').val(data[0].mail);
                $("#reg").val(data[0].reg);
                $('#jf').val(data[0].jf);
                $('#bz').val(data[0].bz);
                $("#needSave").val(false);
            }
        }
    });
}

function findClientToo(e) {
    var keynum;
    if (window.event) // IE
    {
        keynum = e.keyCode;
    }
    else if (e.which) // Netscape/Firefox/Opera
    {
        keynum = e.which;
    }
    if (keynum === 13) {
        findClient();
    }
}