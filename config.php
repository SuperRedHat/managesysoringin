<?php
$sys_style='gray';			//界面的风格
$sys_jf_js=10;				//积分的计算基数，也就是多少钱可以换一个积分
$sys_syjf=0;                //在销售过程中，是否可以使用积分来当现金使用
$sys_jf_dhbl=0;           //每一积分相当于几块钱。默认一积分相当于1块钱

$sys_client_disModifyPoint="";          //在客户管理界面中，是否允许修改客户的累计积分。一般只在系统初始录入时设为空"" .正常运行时应该设置为 "disable"
$sys_client_canDelClient=true;          //是否允许在客户管理界面中删除客户资料. true:允许，false 不允许
$sys_client_showNum=0;					//在客户信息编辑的界面的右边列表中显示多少条客户信息。小于=0 表示显示所有客户。

$sys_xsd=1;                             //金额精确到小数点后几位
$sys_historNum=10;                      //在销售过程中，显示几条客户的历史购物记录

$sys_cx_maxDay=0;                     //最多允许查询多少天的数据
$sys_jf_jsff=2;							//积分的计算方法，1：宽松，允许四舍五入。2：严格，不做四舍五入处理
$sys_windowTitle='神龙磨坊';
