查询今天的销售数据 
select * from d_vendition where DATE_FORMAT(vendTime,"%Y%m%d")=DATE_FORMAT(CURRENT_DATE(),"%Y%m%d")

查询本周的销售数据
select DATE_FORMAT(vendTime,"%Y%m%w") from d_vendition where DATE_FORMAT(vendTime,"%Y%m%w")=DATE_FORMAT(CURRENT_DATE(),"%Y%m%w")

查询本月的销售数据
select DATE_FORMAT(vendTime,"%Y%m%w") from d_vendition where DATE_FORMAT(vendTime,"%Y%m")=DATE_FORMAT(CURRENT_DATE(),"%Y%m")

查询某一段时间的销售数据
select DATE_FORMAT(vendTime,"%Y%m%w") from d_vendition where DATE_FORMAT(vendTime,"%Y%m")=DATE_FORMAT(CURRENT_DATE(),"%Y%m")


查询销售额前20名的客户

查询某一个用户的购买数据 - 显示它从注册开始到现在的分月数据