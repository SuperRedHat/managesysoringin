/**
 * throwError() 
 * 抛出异常
 * 参数： msg : 提示信息
 * 黄文军 2012/3/2
 */
function throwError(msg) {
    var e = new Error();
    e.message = msg;
    window.alert(msg);
    throw e;
}


/**
 * 在数组中进行查找。如找到，返回位置。如未找到，则返回-1
 * 黄文军
 */
function findArry(_ary, _val) {
    len = _ary.length;
    _val = trim(_val);
    for (i = 0; i < len; i++) {
        if (trim(_ary[i]) == _val) {
            return i;
        }
    }
    return -1;
}

/**
 * 从数组里删除一个指定值的元素,然后返回一个新的数组
 * 黄文军 2012/3/30
 */
function delArray(_ary, _val) {
    if (_ary.length == 0)
        return;
    _val = trim(_val);
    var newAry = Array();
    $(_ary).each(function (i) {
        if (trim(_ary[i]) != _val) {
            newAry.push(_ary[i]);
        }
    })
    return newAry;
}

/**
 * 从数组里删除一个指定编号的元素,然后返回一个新的数组
 * 黄文军 2012/3/30
 */
function delArray2(_ary, _val) {
    if (_ary.length == 0)
        return;
    var newAry = Array();
    $(_ary).each(function (i) {
        if (i != _val) {
            newAry.push(_ary[i]);
        }
    });
    return newAry;
}

/**
 *黄文军 2009.7
 *删除左右两端的空格 
 */
function trim(_str) {
    return _str.replace(/(^\s*)|(\s*$)/g, '');
}


/**
 * 类：MYAJAX
 * 为了解决ajax 无法保存参数的问题，所以用这个类来解决问题
 * 参数：
 * 				url="";		//服务页面地址
 *				data="";	//要发送到服务器上的数据
 *				dataType="json";
 *				type="POST";
 *				cache="false";		
 * 方法：setBackFunc
 * 				setBackData
 * 		   黄文军 2012/4/3
 * 返回：执行run()成功后，它会调用回调函数backFunc，并传给它三个参数：_hxr,_stat,_backData
 *              所以用户的回调函数接受这三个参数即可
 */
function MYAJAX() {

    var backFunc = function () {
    };	//执行完ajax后的回调函数
    var backData = new Array();		//在执行前进行保存，执行完毕后传递给回调函数的参数	
    this.setBackFunc = function (_func) {
        backFunc = _func;
    };
    this.setBackData = function (_data) {
        backData = _data;
    }

    this.url = "";
    this.data = "";
    this.dataType = "json";
    this.type = "POST";
    this.cache = "false";

    OnComplete = function (_hxr, _stat) {
        if (backFunc != undefined) {
            try {
                backFunc(_hxr, _stat, backData);
            } catch (err) {
                eval(backFunc + "(_hxr,_stat,backData)");
            }
        }
    }

    var onComplete = OnComplete;

    this.run = function () {
        $.ajax({
            dataType: this.dataType,
            type: this.type,
            url: this.url,
            data: this.data,
            cache: this.cache,
            complete: onComplete
        });
    }
}



/**
 *  getDmFromDb(_id,_obj,_data)
 * 从数据表中读取代码信息。为了提高安全性。不允许在js 代码中涉及任何数据表信息。读取的代码数据也统一转换成dm、mc 的格式
 */
function getDmFromDb(_id, _obj, _data) {
    var myAjax = new MYAJAX();
    myAjax.setBackFunc(getDm_back);
    myAjax.setBackData(Array({"id": _id}, {"obj": _obj}));
    myAjax.url = "/lib/getCode.php";
    myAjax.data = _data;
    myAjax.run();
}

function getDm_back(_hxr, _stat, _backData) {
    var js = eval(_hxr.responseText);
    var id = _backData[0]["id"];
    var obj = _backData[1]["obj"];
    var item = "";
    $(js).each(function (i) {
        item += "<option value='" + js[i].dm + "'>" + js[i].mc + "</option>";
    })
    var propertyList = findIdInArry(componetsArray, id).getPropertyList()
    propertyList = addOption(propertyList, obj, item);
    findIdInArry(componetsArray, id).setPropertyList(propertyList);
}



//把XML转换成json
function xmlToJson(xml) {
    // Create the return object
    var obj = {};
    if (xml.nodeType == 1) { // element
        if (xml.attributes.length > 0) {
            obj["@attributes"] = {};
            for (var j = 0; j < xml.attributes.length; j++) {
                var attribute = xml.attributes.item(j);
                obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
            }
        }
    } else if (xml.nodeType == 3) { // text
        obj = xml.nodeValue;
    }
    // do children
    if (xml.hasChildNodes()) {
        for (var i = 0; i < xml.childNodes.length; i++) {
            var item = xml.childNodes.item(i);
            var nodeName = item.nodeName;
            if (typeof (obj[nodeName]) == "undefined") {
                obj[nodeName] = xmlToJson(item);
            } else {
                if (typeof (obj[nodeName].length) == "undefined") {
                    var old = obj[nodeName];
                    obj[nodeName] = [];
                    obj[nodeName].push(old);
                }
                obj[nodeName].push(xmlToJson(item));
            }
        }
    }
    return obj;
}
;


function checkRate(input)
{
    var re = /^[0-9]+.?[0-9]*$/;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/   
    return re.test(input);
}

function msg(_msg, _time) {
    if (_time === undefined) {
        _time = 4000;
    }
    $("#infoWin").html(_msg);
    $("#errMsg").val(_msg);
    if (_time !== 0) {
        setTimeout(function () {
            $("#infoWin").html("");
        }, _time);
    }
}


/*
 * 获取中文日期
 * 
 * */
function getNowDateStr() {
    var nowDate = new Date();
    var nowYear = nowDate.getFullYear();
    var nowMonth = nowDate.getMonth() + 1;
    var nowDay = nowDate.getDate();
    var nowWeekDay = nowDate.getDay();

    var nowYearStr = nowYear + "";
    var nowMonthStr = nowMonth + "";
    if (nowMonthStr.length == 1)
    {
        nowMonthStr = "0" + nowMonthStr;
    }
    var nowDayStr = nowDay + "";
    if (nowDayStr.length == 1)
    {
        nowDayStr = "0" + nowDayStr;
    }
    var nowWeekDayStr = "星期";
    if (nowWeekDay == 1)
    {
        nowWeekDayStr += "一";
    }
    if (nowWeekDay == 2)
    {
        nowWeekDayStr += "二";
    }
    if (nowWeekDay == 3)
    {
        nowWeekDayStr += "三";
    }
    if (nowWeekDay == 4)
    {
        nowWeekDayStr += "四";
    }
    if (nowWeekDay == 5)
    {
        nowWeekDayStr += "五";
    }
    if (nowWeekDay == 6)
    {
        nowWeekDayStr += "六";
    }
    if (nowWeekDay == 0)
    {
        nowWeekDayStr += "日";
    }

    return nowYearStr + "年" + nowMonthStr + "月" + nowDayStr + "日 " + nowWeekDayStr;
}



/**
 * 检查输入的数据是否为空或是零。
 * @param {type} v
 * @returns {Boolean}
 */
function isEmpty(v) {
    if (trim(v).length === 0 || trim(v) === "0" || v === 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * 将文本转换为数字
 * @param {type} d
 * @returns {Number}
 */
function toNumber(d) {
    var resu = 0;
    if (trim(d).length > 0) {
        try {
            resu = parseFloat(d);
        } catch (e) {
            resu = 0;
        }
    }
    return parseFloat(resu);
}


function IsDate(dateval) {
    var arr = new Array();

    if (dateval.indexOf("-") != -1) {
        arr = dateval.toString().split("-");
    } else if (dateval.indexOf("/") != -1) {
        arr = dateval.toString().split("/");
    } else {
        return false;
    }

    //yyyy-mm-dd || yyyy/mm/dd
    if (arr[0].length == 4) {
        var date = new Date(arr[0], arr[1] - 1, arr[2]);
        if (date.getFullYear() == arr[0] && date.getMonth() == arr[1] - 1 && date.getDate() == arr[2]) {
            return true;
        }
    }
    //dd-mm-yyyy || dd/mm/yyyy
    if (arr[2].length == 4) {
        var date = new Date(arr[2], arr[1] - 1, arr[0]);
        if (date.getFullYear() == arr[2] && date.getMonth() == arr[1] - 1 && date.getDate() == arr[0]) {
            return true;
        }
    }
    //mm-dd-yyyy || mm/dd/yyyy
    if (arr[2].length == 4) {
        var date = new Date(arr[2], arr[0] - 1, arr[1]);
        if (date.getFullYear() == arr[2] && date.getMonth() == arr[0] - 1 && date.getDate() == arr[1]) {
            return true;
        }
    }

    return false;
}