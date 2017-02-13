<?php // 5isoft.cn , Copyright(C) , All rights reserved.

class InstoreAction extends AppAction{
public function add(){
$model=D('Supplier');
$main['ism_sn']='IN-'.date('Ymd-His-').rand(100,999);
$list_supplier=$model->select();
$this->assign('list_supplier',$list_supplier);
$this->assign('main',$main);
$this->assign('action','add');
$this->assign('title','入库登记');
$this->display();
}
public function checkDataEmpty() {
$model_product=D('Product');
$count_product=$model_product->count();
$model_prodcate=D('Prodcate');
$count_prodcate=$model_prodcate->count();
$model_supplier=D('Supplier');
$count_supplier=$model_supplier->count();
$model_customer=D('Customer');
$count_customer=$model_customer->count();
$model_store=D('Store');
$count_store=$model_store->count();
$model_employee=D('Employee');
$count_employee=$model_employee->count();
if($count_product==0){
$msgArray[]='产品';
}
if($count_prodcate==0){
$msgArray[]='产品类型';
}
if($count_supplier==0){
$msgArray[]='供应商';
}
if($count_customer==0){
$msgArray[]='客户';
}
if($count_store==0){
$msgArray[]='仓库';
}
if($count_employee==0){
$msgArray[]='员工';
}
echo implode('，',$msgArray);
}
public function getProduct(){
$model=M("product");
if($_GET['term']){
$prod_name=trim($_GET['term']);
$map["a.prod_name"]=array("like","%$prod_name%");
}
$list=$model->alias("a")->join("twms_prod_cate as b on a.prod_cate=b.pdca_id")->where($map)->order('pdca_id,prod_name')->select();
if(count($list)>0){
foreach($list as $row){
$result[]=array(
'label'=>$row['prod_name'],
'category'=>$row['pdca_name'],
'value'=>$row['prod_name'],
'prod_id'=>$row['prod_id'],
'prod_name'=>$row['prod_name'],
'prod_price'=>$row['prod_price'],
'prod_cate'=>$row['prod_cate'],
'pdca_name'=>$row['pdca_name']
);
}
}else{
$result[]=array(
'label'=>'（无）',
'category'=>'',
'value'=>'',
);
}
echo json_encode($result);
}
public function getSupplier(){
$model=D('Supplier');
if($_GET['term']){
$sup_name=trim($_GET['term']);
$map['sup_name']=array('like',"%$sup_name%");
}
$list=$model->where($map)->order('sup_id desc')->select();
foreach($list as $row){
$result[]=array(
'id'=>$row['sup_id'],
'label'=>$row['sup_name'],
'value'=>$row['sup_name']
);
}
echo json_encode($result);
}
public function getOperator(){
$model=D('Employee');
if($_GET['term']){
$emp_name=trim($_GET['term']);
$map['emp_name']=array('like',"%$emp_name%");
}
$map['emp_isleave']=0;
$list=$model->where($map)->order('emp_id desc')->select();
foreach($list as $row){
$result[]=array(
'id'=>$row['emp_id'],
'label'=>$row['emp_name'],
'value'=>$row['emp_name']
);
}
echo json_encode($result);
}
public function doAdd(){
$model_main=M("instore_main");
$model_main->create();
$model_main->ism_datetime=date('Y-m-d H:i:s');
$model_main->ism_writer=$_SESSION['user']['user_id'];
$model_main->ism_supplier=$_POST["ism_supplier"];
$main_id=$model_main->add();
$model_sub=M("instore_sub");
$model_stock=M('stock');
for($i=0;$i<count($_POST["row_count"]);$i++){
$data_sub["iss_mainid"]=$main_id;
$data_sub["iss_prod"]=$_POST["iss_prod"][$i];
$data_sub["iss_prodname"]=$_POST["iss_prodname"][$i];
$data_sub["iss_price"]=$_POST["iss_price"][$i];
$data_sub["iss_count"]=$_POST["iss_count"][$i];
$data_sub["iss_total"]=$_POST["iss_total"][$i];
$data_sub["iss_store"]=$_POST["iss_store"][$i];
$data_sub["iss_remark"]=$_POST["iss_remark"][$i];
$data_sub["iss_cate"]=$_POST["iss_cate"][$i];
$data_sub["iss_datetime"]=date('Y-m-d H:i:s');
$model_sub->add($data_sub);
$data_main["ism_total"]+=$_POST["iss_total"][$i];
$data_stock["stk_inmainid"]=$main_id;
$data_stock["stk_prod"]=$_POST["iss_prod"][$i];
$data_stock["stk_prodname"]=$_POST["iss_prodname"][$i];
$data_stock["stk_cate"]=$_POST["iss_cate"][$i];
$data_stock["stk_price"]=$_POST["iss_price"][$i];
$data_stock["stk_count"]=$_POST["iss_count"][$i];
$data_stock["stk_total"]=$_POST["iss_total"][$i];
$data_stock["stk_store"]=$_POST["iss_store"][$i];
$data_stock["stk_remark"]=$_POST["iss_remark"][$i];
$data_stock["stk_datetime"]=date('Y-m-d H:i:s');
$model_stock->add($data_stock);
}
$model_main->where(array("ism_id"=>$main_id))->save($data_main);
import('@.ORG.Util.SysLog');
SysLog::writeLog("添加入库记录:".$_POST["ism_supplier_name"]);
$this->redirect("Instore/index");
}
public function getStore(){
$model=D('Store');
if($_GET['term']){
$sto_name=trim($_GET['term']);
$map['sto_name']=array('like',"%$sto_name%");
}
$list=$model->where($map)->order('sto_id desc')->select();
foreach($list as $row){
$result[]=array(
'id'=>$row['sto_id'],
'label'=>$row['sto_name'],
'value'=>$row['sto_name'],
'isdefault'=>$row['sto_isdefault']
);
}
echo json_encode($result);
}
public function index() {
import("@.ORG.Util.Page");
$model=M("instore_main");
if($_GET["searchBy"]!="")$map[$_GET["searchBy"]]=array("like","%{$_GET['keyword']}%");
if($_GET["ism_sn"]!="")$map["ism_sn"]=array("like","%{$_GET['ism_sn']}%");
// if($_GET["ism_supplier_name"]!="")$map["d.sup_name"]=array("like","%{$_GET['ism_supplier_name']}%");
// if($_GET["ism_operator_name"]!="")$map["e.emp_name"]=array("like","%{$_GET['ism_operator_name']}%");
if($_GET["iss_total_start"]!=""&&$_GET["iss_total_end"]=="")$map["ism_total"]=array("egt","{$_GET['iss_total_start']}");
if($_GET["iss_total_start"]==""&&$_GET["iss_total_end"]!="")$map["ism_total"]=array("elt","{$_GET['iss_total_end']}");
if($_GET["iss_total_start"]!=""&&$_GET["iss_total_end"]!=""){
$map["ism_total"]=array(array("egt","{$_GET['iss_total_start']}"),array("elt","{$_GET['iss_total_end']}"));
}
if($_GET["ism_writer_name"]!="")$map["f.user_realname"]=array("like","%{$_GET['ism_writer_name']}%");
if($_GET["ism_date_start"]!=""&&$_GET["ism_date_end"]=="")$map["ism_datetime"]=array("egt","{$_GET['ism_date_start']}".' 00:00:00');
if($_GET["ism_date_start"]==""&&$_GET["ism_date_end"]!="")$map["ism_datetime"]=array("elt","{$_GET['ism_date_end']}".' 59:59:59');
if($_GET["ism_date_start"]!=""&&$_GET["ism_date_end"]!=""){
$map["ism_datetime"]=array(array("egt","{$_GET['ism_date_start']}".' 00:00:00'),array("elt","{$_GET['ism_date_end']}".' 59:59:59'));
}
if($_GET["iss_prodname"]!=""){
$map["iss_prodname"]=array("like","%{$_GET['iss_prodname']}%");
}
if($_GET["iss_store_name"]!=""){
$map["c.sto_name"]=array("like","%{$_GET['iss_store_name']}%");
}
$_SESSION["is_map"]=$map;
$list=$model->alias('a')->join("twms_instore_sub as b on ism_id=iss_mainid")->
join('twms_store as c on c.sto_id=b.iss_store')->
join('twms_supplier as d on a.ism_supplier=d.sup_id')->
join('twms_employee as e on a.ism_operator=e.emp_id')->
join('twms_user as f on a.ism_writer=f.user_id')->
where($map)->group("iss_mainid")->field('ism_id')->select();
$count=count($list);
$Page = new Page($count,D('Setting')->where(array('set_id'=>1))->getField('set_list_pagesize'));
$show = $Page->show();
$this->assign('page',$show);
$list=$model->alias('a')->join("twms_instore_sub as b on a.ism_id=b.iss_mainid")->
join('twms_store as c on c.sto_id=b.iss_store')->
join('twms_supplier as d on a.ism_supplier=d.sup_id')->
join('twms_employee as e on a.ism_operator=e.emp_id')->
join('twms_user as f on a.ism_writer=f.user_id')->
where($map)->group("iss_mainid")->
field("a.*,sum(iss_price*iss_count) as total,sum(iss_count) as `count`,sup_name,e.emp_name as operator,f.user_realname as ism_writer_name,b.iss_remark,c.sto_name as iss_store_name,b.iss_prodname,b.iss_price")->
order("ism_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
$list_sum=$model->alias("a")->join("twms_instore_sub as b on a.ism_id=b.iss_mainid")->
join('twms_store as c on c.sto_id=b.iss_store')->
join('twms_supplier as d on a.ism_supplier=d.sup_id')->
join('twms_employee as e on a.ism_operator=e.emp_id')->
join('twms_user as f on a.ism_writer=f.user_id')->
where($map)->field("sum(b.iss_count) as sumNum,sum(b.iss_price*b.iss_count) as sumtotal")->select();
$sumNum=$list_sum['0']["sumNum"];
$sumtotal=$list_sum['0']["sumtotal"];
$this->assign("sumNum",$sumNum);
$this->assign("sumtotal",$sumtotal);
$this->assign("searchBy",$_GET['searchBy']);
$this->assign("keyword",$_GET['keyword']);
$this->assign("list",$list);
$this->display();
}
public function delete(){
$model_main=D("Instoremain");
$model_main->where(array('ism_id'=>$_GET['ism_id']))->delete();
$model_sub=D("Instoresub");
$model_sub->where(array("iss_mainid"=>$_GET['ism_id']))->delete();
import('@.ORG.Util.SysLog');
SysLog::writeLog("删除入库记录");
$this->redirect("index");
}
public function view(){
$model_main=D("Instoremain");
$main=$model_main->alias('a')->join('twms_supplier as b on a.ism_supplier=b.sup_id')->
join('twms_employee as c on a.ism_operator=c.emp_id')->join('twms_employee as d on a.ism_writer=d.emp_id')->
join('twms_user as e on a.ism_writer=e.user_id')->
where(array("ism_id"=>$_GET['ism_id']))->
field('a.ism_sn,c.emp_name as ism_operator_name,e.user_realname as ism_writer_name,b.sup_name as ism_supplier_name')->find();
$model_sub=M("instore_sub");
$list_sub=$model_sub->join('twms_product on iss_prod=prod_id')->
join('twms_store on sto_id=iss_store')->
join('twms_prod_cate on pdca_id=iss_cate')->
where(array("iss_mainid"=>$_GET['ism_id']))->order('iss_id asc')->select();
$model_store=M('store');
$list_store=$model_store->select();
$this->assign('list_store',$list_store);
$this->assign("main",$main);
$this->assign("list_sub",$list_sub);
$this->assign('action','view');
$this->display('add');
}
public function toEdit(){
$model_main=D("Instoremain");
$main=$model_main->alias('a')->
join('twms_supplier as b on a.ism_supplier=b.sup_id')->
join('twms_employee as c on a.ism_operator=c.emp_id')->
join('twms_user as f on a.ism_writer=f.user_id')->
where(array("ism_id"=>$_GET['ism_id']))->
field('a.*,c.emp_name as ism_operator_name,f.user_realname as ism_writer_name,b.sup_name as ism_supplier_name')->find();
$model_sub=M('instore_sub');
$list_sub=$model_sub->alias('a')->
join('twms_product on iss_prod=prod_id')->
join('twms_prod_cate on iss_cate=pdca_id')->
join('twms_store on sto_id=iss_store')->
where(array('iss_mainid'=>$_GET['ism_id']))->order('iss_id asc')->select();
$model_store=M('store');
$list_store=$model_store->select();
$this->assign('list_store',$list_store);
$this->assign('main',$main);
$this->assign('list_sub',$list_sub);
$this->assign('title','入库编辑');
$this->assign('action','update');
$this->display('add');
}
public function doEdit(){
$model_main=D("Instoremain");
$model_main->ism_datetime=date('Y-m-d H:i:s');
$model_main->create();
$model_sub=D("Instoresub");
$model_sub->where(array('iss_mainid'=>$_POST['ism_id']))->delete();
for($i=0;$i<count($_POST["iss_prodname"]);$i++){
$data['iss_mainid']=$_POST['ism_id'];
$data['iss_prodname']=$_POST['iss_prodname'][$i];
$data['iss_prod']=$_POST['iss_prod'][$i];
$data['iss_cate']=$_POST['iss_cate'][$i];
$data['iss_price']=$_POST['iss_price'][$i];
$data['iss_count']=$_POST['iss_count'][$i];
$data['iss_total']=$_POST['iss_total'][$i];
$data['iss_store']=$_POST['iss_store'][$i];
$data['iss_remark']=$_POST['iss_remark'][$i];
$data['iss_datetime']=date('Y-m-d H:i:s');
$model_main->ism_total+=$data['iss_total'];
$model_sub->add($data);
}
$model_main->save();
import('@.ORG.Util.SysLog');
SysLog::writeLog("编辑入库记录");
$this->redirect("index");
}

public function export() {
import("@.ORG.Util.Page");
$model=M("instore_main");
$map=$_SESSION["is_map"];
$list=$model->alias('a')->join("twms_instore_sub as b on ism_id=iss_mainid")->
join('twms_store as c on c.sto_id=b.iss_store')->
join('twms_supplier as d on a.ism_supplier=d.sup_id')->
join('twms_employee as e on a.ism_operator=e.emp_id')->
join('twms_user as f on a.ism_writer=f.user_id')->
where($map)->group("iss_mainid")->field('ism_id')->select();
$count=count($list);
$Page = new Page($count,D('Setting')->where(array('set_id'=>1))->getField('set_list_pagesize'));
$show = $Page->show();
$this->assign('page',$show);
$list=$model->alias('a')->join("twms_instore_sub as b on a.ism_id=b.iss_mainid")->
join('twms_store as c on c.sto_id=b.iss_store')->
join('twms_supplier as d on a.ism_supplier=d.sup_id')->
join('twms_employee as e on a.ism_operator=e.emp_id')->
join('twms_user as f on a.ism_writer=f.user_id')->
where($map)->group("iss_mainid")->
field("a.*,sum(iss_price*iss_count) as total,sum(iss_count) as `count`,sup_name,e.emp_name as operator,f.user_realname as ism_writer_name,b.iss_remark,c.sto_name as iss_store_name,b.iss_prodname,b.iss_price")->
order("ism_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
$list_sum=$model->alias("a")->join("twms_instore_sub as b on a.ism_id=b.iss_mainid")->
join('twms_store as c on c.sto_id=b.iss_store')->
join('twms_supplier as d on a.ism_supplier=d.sup_id')->
join('twms_employee as e on a.ism_operator=e.emp_id')->
join('twms_user as f on a.ism_writer=f.user_id')->
where($map)->field("sum(b.iss_count) as sumNum,sum(b.iss_price*b.iss_count) as sumtotal")->select();
$sumNum=$list_sum['0']["sumNum"];
$sumtotal=$list_sum['0']["sumtotal"];
$this->assign("sumNum",$sumNum);
$this->assign("sumtotal",$sumtotal);
$this->assign("searchBy",$_GET['searchBy']);
$this->assign("keyword",$_GET['keyword']);
$this->assign("list",$list);
$this->display();
}


}
