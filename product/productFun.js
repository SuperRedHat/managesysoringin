/**
 * 在输入产品信息时，是否显示打折输入界面
 */
function showDiv_sale() {
    if ($("#isSale").attr("checked") === "checked") {
        $('#div_sale').css('visibility', 'visible');
    } else {
        $('#div_sale').css('visibility', 'hidden');
        //将折扣区的所有输入项目设置为不验证
        $("#starTime").val('');
        $("#endTime").val('');
        $("#sale").val('');
    }
}
/**
 * 当用户在左边分类窗口中点某个产品后，在输入窗口中显示此产品的详细信息
 * @param _productId
 * "[ { 'productId': '1', 'productName': '蜂蜜', 'productTypeId': '1', 'addTime': '2013-04-18 12:33:07', 'price': '10', 'isSale': '0', 'saleStarTime': '', 'saleEndTime': '', 'sale': '' } ]"
 */
function showProduct(_pId) {
    $.post('readProduct.php', {'id': _pId}, function(data) {
        data = eval(data);
        if (data.length === 0) {	//如果没查询到记录
            return;
        }
        $('#pId').val(data[0].pId);
        $('#tId').val(data[0].tId);
        $('#pName').val(data[0].pName);
        $('#pShort').val(data[0].pShort);
        $('#pPrice').val(data[0].pPrice);
        $('#jfdhbl').val(data[0].jfdhbl);

        if (data[0].useIt === "0") {   
            $("#useIt").removeAttr("checked");
        } else {
            $('#useIt').attr("checked", "true");
        }
        
        if (data[0].isSale === "0") {    //根据是否打折决定是否显示下面的对象。0：不打折，1：打折
            $("#isSale").removeAttr("checked");
            $('#div_sale').css("visibility", "hidden");
        } else {
            $('#isSale').attr("checked", "true");
            $('#div_sale').css("visibility", "visible");
        }
        $('#starTime').val(data[0].starTime);
        $('#endTime').val(data[0].endTime);
        $('#sale').val(data[0].sale);
    });

}


/**
 * toAdd 新
 * @returns {undefined}
 */
function toNewCreate() {
    if ($("#isChange").val() === "true") {
        if (confirm('修改的内容还没有保存，你确定要放弃这些内容吗？')) {
            init();
            $("#productName").focus();
        }
    } else {
        init();
        $("#productName").focus();
    }
}


/**
 * 当用户在输入界面中选择产品分类后，自动切换左边窗口到对应的分类
 * obj :select 中选择的记录
 * 黄文军 2013/4/18
 */
function productTypeIdChange(obj) {
    var title = obj[obj.value - 1].label;
    $('#div_productType').accordion('select', title);

}

function toSave() {
	if($("#isSale").attr("checked") != "checked"){	//如果不打折，则不验证这几个项目
	    $("#starTime").val('');
	    $("#endTime").val('');
	    $("#sale").val('');		
	}else{
		var err="";
		if (!IsDate($("#starTime").val())){
			err+="打折开始时间格式错误<br>";
		}
		if (!IsDate($("#endTime").val())){
			err+="打折结束时间格式错误";			
		}
		if(toNumber($("#sale").val())<=0){
			err+="优惠浮动必须大于0";
		}
		if(err.length>0){
			msg(err);
			return;			
		}
	}
	
    //检查输入合法性
    if (!jQuery("#frm_input").validationEngine('validate')) {
        msg("输入内容没有通过检查");
        return;
    }
    $.post('./saveProduct.php',
            {
                pId: $("#pId").val(),
                tId: $("#tId").val(),
                pName: $("#pName").val(),
                pShort: $("#pShort").val(),
                pPrice: $("#pPrice").val(),
                jfdhbl:$("#jfdhbl").val(),
                isSale: $("#isSale").attr("checked") === "checked" ? 1 : 0,
                starTime: $("#starTime").val(),
                endTime: $("#endTime").val(),
                sale: $("#sale").val(),
                useIt:$("#useIt").attr("checked") === "checked" ? 1 : 0,
            },
    function(data) {
        if (data.indexOf("Error") < 0) {
        	//检查产品名称在右边是否已存在，存在则修改。否则增
        	var e= $('#div_productType').accordion("getSelected");
        	var cd=$(e).find("li").length;
        	var r=false;
        	for(var j=0;j<=cd;j++){
        		if ($(e).find("li").eq(j).val()==$("#pId").val()){
        			$(e).find("li").eq(j).html($("#pName").val());
        			r=true;
        		};
        	}
        	if (!r){
        		$(e).find("ul").append("<li class=a_li value="+data+" onclick=showProduct("+data+")>"+$("#pName").val()+"</li>");        		
        	}
        	
            //保存后的初始化工作
            init();
            msg('保存成功', 2000);
        } else {
            alert("保存时出错:<br>" + data);
        }
    });
}

function init() {
    $("#pId").val("");
    $("#tId").val("");
    $("#pName").val("");
    $("#pShort").val("");
    $("#pPrice").val("");
    $("#jfdhbl").val("");
    $("#starTime").val("");
    $("#endTime").val("");
    $("#sale").val("");
    $("#isSale").removeAttr("checked");
    $('#div_sale').css("visibility", "hidden");
    jQuery("#frm_input").validationEngine('hide');

}

function toDel() {
    if (trim($("#pId").val()).length === 0) {
        init();
        return;
    }
    if (confirm('你确定要删除这条记录?')) {
        $.post('../lib/delRecord.php', {type: "product", pId: $("#pId").val()}, function(data) {
            if (data.indexOf("Error") < 0) {
            	//从面板上删除此记录
            	var e= $('#div_productType').accordion("getSelected");
            	var cd=$(e).find("li").length;
            	for(var j=0;j<=cd;j++){
            		if ($(e).find("li").eq(j).val()==$("#pId").val()){
            			$(e).find("li").eq(j).remove();
            			break;
            		};
            	}
            	//清空输入界面
                document.getElementById("frm_input").reset();
                msg('记录已成功删除');
                var panel = $('#div_productType').accordion("getSelected");
            } else {
                alert("删除记录时出错:\n"+data);
            }

        });
    }
}