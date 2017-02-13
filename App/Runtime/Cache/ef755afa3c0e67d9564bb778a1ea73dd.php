<?php if (!defined('THINK_PATH')) exit();?><?php

header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:filename=出库统计.xls");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title></title><style>body{
	font-size:12px;
}
th{
	
}
table{
	border-left:1px solid #000000;
	border-top:1px solid #000000;
}
td{
	border-right:1px solid #000000;
	border-bottom:1px solid #000000;
	text-align:center;
	padding-left:9px;
	padding-right:9px;
}
tr{
	line-height:25px;	
}
</style></head><body><table border="0" cellpadding="0" cellspacing="0"><tr><th style="width:200px;">流水编号</th><th style="width:150px;">OA编号</th><th style="width:100px;">产品名称</th><th>入库日期</th><th>产品单价</th><th>总数量</th><th>总金额</th><th>客户</th><th>部门</th><th>中心</th><th>供应商</th><th>制单人</th><th>日期</th></tr><?php foreach($list as $key=>$row){ ?><tr><td><?php echo $row["osm_sn"]; ?></td><td><?php echo $row["oss_remark"]; ?></td><td><?php echo $row["oss_prodname"]; ?></td><td><?php echo $row["stk_datetime"]; ?></td><td><?php echo $row["price"]; ?></td><td><?php echo $row["count"]; ?></td><td><?php echo $row["total"]; ?></td><td><?php echo $row["cust_name"]; ?></td><td><?php echo $row["dept_name"]; ?></td><td><?php echo $row["center_name"]; ?></td><td><?php echo $row["oss_store_name"]; ?></td><td><?php echo $row["osm_writer_name"]; ?></td><td><?php echo $row["osm_datetime"]; ?></td></tr><?php } ?><tr><td align="center">合计</td><th></th><th></th><th></th><th></th><td align="center"><?php echo ($sumNum); ?></td><td align="center"><?php echo ($sumtotal); ?></td><th></th><th></th><th></th><th></th><th></th><th></th></tr></table></body></html>