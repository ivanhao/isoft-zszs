<?php
class LogAction extends AppAction{
public function index() {
import("@.ORG.Util.Page");
$model=M("log");
if($_GET["keyword"]!=''){
$map['log_operator_name']=array("like","%{$_GET['keyword']}%");
$map['log_operator_realname']=array("like","%{$_GET['keyword']}%");
$map['log_action']=array("like","%{$_GET['keyword']}%");
$map['log_ip']=array("like","%{$_GET['keyword']}%");
$map['_logic']='or';
$this->assign("keyword",$_GET['keyword']);
}
$count=$model->where($map)->count();
$Page = new Page($count,D('Setting')->where(array('set_id'=>1))->getField('set_list_pagesize'));
$show = $Page->show();
$this->assign('page',$show);
$list=$model->where($map)->order("log_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
$this->assign("list",$list);
$this->assign("searchBy",$_GET['searchBy']);
$this->assign("keyword",$_GET['keyword']);
$this->display();
}
public function clearLog(){
$model=M("log");
$model->where(array("log_id"=>array("neq","")))->delete();
$this->redirect("index");
}
}
