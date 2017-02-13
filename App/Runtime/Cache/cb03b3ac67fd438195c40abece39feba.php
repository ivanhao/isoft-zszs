<?php if (!defined('THINK_PATH')) exit();?><?php

header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:filename=入库统计.xls");

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
</style></head><body><table border="0" cellpadding="0" cellspacing="0"><tr><th style="width:200px;">流水编号</th><th style="width:150px;">OA编号</th><th style="width:100px;">产品名称</th><th>产品单价</th><th>数量</th><th>金额</th><th>供应商</th><th>制单人</th><th>日期</th></tr><?php foreach($list as $key=>$row){ ?><tr><td><?php echo $row["ism_sn"]; ?></td><td><?php echo $row["iss_remark"]; ?></td><td><?php echo $row["iss_prodname"]; ?></td><td><?php echo $row["iss_price"]; ?></td><td><?php echo $row["count"]; ?></td><td><?php echo $row["total"]; ?></td><td><?php echo $row["iss_store_name"]; ?></td><td><?php echo $row["ism_writer_name"]; ?></td><td><?php echo $row["ism_datetime"]; ?></td></tr><?php } ?><tr><td align="center">合计</td><th></th><th></th><th></th><td align="center"><?php echo ($sumNum); ?></td><td align="center"><?php echo ($sumtotal); ?></td><th></th><th></th><th></th></tr></table></body></html>