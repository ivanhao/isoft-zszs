<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title></title><link rel="stylesheet" type="text/css" href="__PUBLIC__/css/admincp.css" /><link rel="stylesheet" href="__PUBLIC__/js/jquery/themes/custom-theme/jquery.ui.all.css"></head><body><div class="container"><style>.ul_navi{margin:20px 0px 20px 0px;}
.ul_navi>li{display:inline;margin-right:2px;border-radius:10px 10px 0px 0px;
padding:6px 12px;border: 1px solid #aed0ea;font-weight:bold;font-size:13px}
.li_link{background:#EAF4FC;}
.li_link a{color:#2366A8;}
.li_active{background:#57B6E7;color:white}
.li_active a{color:white}
</style><ul class="ul_navi"><li <?php if($action=="index"){echo "class='li_active'";}else{echo "class='li_link'";} ?>><a href="__URL__/index">总览统计</a></li><li <?php if($action=="chart1"){echo "class='li_active'";}else{echo "class='li_link'";} ?>><a href="__URL__/chart1">七天出入库数量曲线图</a></li><li <?php if($action=="chart2"){echo "class='li_active'";}else{echo "class='li_link'";} ?>><a href="__URL__/chart2">七天出入库金额曲线图</a></li><li <?php if($action=="chart3"){echo "class='li_active'";}else{echo "class='li_link'";} ?>><a href="__URL__/chart3">出入库总数量比率图</a></li><li <?php if($action=="chart4"){echo "class='li_active'";}else{echo "class='li_link'";} ?>><a href="__URL__/chart4">出入库总金额比率图</a></li></ul><div class="mainbox" id="chart4"></div></div></body></html><script src="__PUBLIC__/js/jquery/jquery-1.7.2.js"></script><!--line--><script src="__PUBLIC__/highcharts/highcharts.js"></script><script src="__PUBLIC__/highcharts/modules/exporting.js"></script><!--line--><script>var url='__URL__';
var line_cate=<?php echo $line_cate; ?>;
var pie_out_total=<?php echo $outstore_count['total']; ?>;
var pie_in_total=<?php echo $instore_count['total']; ?>;
</script><script language="JavaScript" src="__PUBLIC__/js/tpl/stat_chart4.js" type="text/javascript"></script>