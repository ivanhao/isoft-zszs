<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title></title><link rel="stylesheet" type="text/css" href="__PUBLIC__/css/admincp.css" /><!-- button --><link rel="stylesheet" href="__PUBLIC__/js/jquery/themes/custom-theme/jquery.ui.all.css"><script src="__PUBLIC__/js/jquery/jquery-1.7.2.js"></script><script src="__PUBLIC__/js/jquery/ui/jquery.ui.core.js"></script><script src="__PUBLIC__/js/jquery/ui/jquery.ui.widget.js"></script><script src="__PUBLIC__/js/jquery/ui/jquery.ui.button.js"></script><script src="__PUBLIC__/js/jquery/ui/jquery.ui.button.js"></script><!-- button --></head><body><div class="container"><h3>操作日志</h3><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="left"><!--<select name="searchBy" id="searchBy"><option value="log_operator_name" <?php if($searchBy=='log_operator_name'){echo "selected='true'";}; ?>>用户名</option><option value="log_operator_realname" <?php if($searchBy=='log_operator_realname'){echo "selected='true'";}; ?>>真实姓名</option><option value="log_datetime" <?php if($searchBy=='log_datetime'){echo "selected='true'";}; ?>>时间</option><option value="log_action" <?php if($searchBy=='log_action'){echo "selected='true'";}; ?>>动作</option><option value="log_ip" <?php if($searchBy=='log_ip'){echo "selected='true'";}; ?>>IP</option></select>&nbsp;&nbsp;--><input type="text" size="30" name="keyword" id="keyword" value="<?php if($keyword!=null){echo $keyword;}else{echo '输入用户名、姓名、动作、IP';}; ?>" style='color:#CCC' onfocus='clearTip(this)'  onblur='fillTip(this)'/>&nbsp;&nbsp;
        <input type="button" class="btn" id="fastSearch" value="查询" onclick="goSearch()"/></td><td align="right"><?php if($_SESSION['user']['user_type']==1){ ?><input id="btnClearLog" class="btn" type="button" value="清空日志" /><?php } ?></td></tr></table><div class="mainbox"><form action="admin.php?m=cache&a=update" method="post"><table class="datalist fixwidth"><tr><th>用户名</th><th>真实姓名</th><th>时间</th><th>动作</th><th>IP地址</th></tr><?php foreach($list as $key=>$row){ ?><tr><td align="center"><?php echo $row["log_operator_name"]; ?></td><td align="center"><?php echo $row["log_operator_realname"]; ?></td><td align="center"><?php echo $row["log_datetime"]; ?></td><td align="center"><?php echo $row["log_action"]; ?></td><td align="center"><?php import('@.ORG.Util.SysLog');echo SysLog::getIP($row["log_ip"]); ?></td></tr><?php } ?><tr class="nobg"><td colspan="6" align="center"><?php echo $page; ?></td></tr></table></form></div></div><script language="JavaScript" src="__PUBLIC__/js/tpl/log_index.js" type="text/javascript"></script></body></html>