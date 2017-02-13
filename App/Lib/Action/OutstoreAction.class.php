<?php // 5isoft.cn , Copyright(C) , All rights reserved.

class OutstoreAction extends AppAction{
public function index() {
import("@.ORG.Util.Page");
$model=D("Outstoremain");
if($_GET["searchBy"]!="")$map[$_GET["searchBy"]]=array("like","%{$_GET['keyword']}%");
if($_GET["osm_sn"]!="")$map["osm_sn"]=array("like","%{$_GET['osm_sn']}%");
if($_GET["osm_customer_name"]!="")$map["cust_name"]=array("like","%{$_GET['osm_customer_name']}%");
if($_GET["osm_operator_name"]!="")$map["e.emp_name"]=array("like","%{$_GET['osm_operator_name']}%");
if($_GET["oss_total_start"]!=""&&$_GET["oiss_total_end"]=="")$map["osm_total"]=array("egt","{$_GET['oss_total_start']}");
if($_GET["oss_total_start"]==""&&$_GET["oss_total_end"]!="")$map["osm_total"]=array("elt","{$_GET['oss_total_end']}");
if($_GET["oss_total_start"]!=""&&$_GET["oss_total_end"]!=""){
$map["osm_total"]=array(array("egt","{$_GET['oss_total_start']}"),array("elt","{$_GET['oss_total_end']}"));
}
if($_GET["osm_writer_name"]!="")$map["f.user_realname"]=array("like","%{$_GET['osm_writer_name']}%");
if($_GET["osm_date_start"]!=""&&$_GET["osm_date_end"]=="")$map["osm_datetime"]=array("egt","{$_GET['osm_date_start']}".' 00:00:00');
if($_GET["osm_date_start"]==""&&$_GET["osm_date_end"]!="")$map["osm_datetime"]=array("elt","{$_GET['osm_date_end']}".' 59:59:59');
if($_GET["osm_date_start"]!=""&&$_GET["osm_date_end"]!=""){
$map["osm_datetime"]=array(array("egt","{$_GET['osm_date_start']}".' 00:00:00'),array("elt","{$_GET['osm_date_end']}".' 59:59:59'));
}
if($_GET["oss_prodname"]!=""){
$map["oss_prodname"]=array("like","%{$_GET['oss_prodname']}%");
$model->join("twms_outstore_sub on osm_id=oss_mainid");
}
if($_GET["oss_store_name"]!=""){
$map["sto_name"]=array("like","%{$_GET['oss_store_name']}%");
}
//将查询条件放入session
$_SESSION["os_map"]=$map;
$list = $model->join("twms_outstore_sub as b on osm_id=oss_mainid")->
join('twms_store as c on sto_id=oss_store')->
where($map)->group("oss_mainid")->field('osm_id')->select();
$count=count($list);

$Page = new Page($count,D('Setting')->where(array('set_id'=>1))->getField('set_list_pagesize'));
$show = $Page->show();
$this->assign('page',$show);
$list=$model->alias("a")->join("twms_outstore_sub as b on a.osm_id=b.oss_mainid")->
join('twms_store as c on c.sto_id=b.oss_store')->
join('twms_customer as d on a.osm_customer=d.cust_id')->
join('twms_employee as e on a.osm_operator=e.emp_id')->
join('twms_user as f on a.osm_writer=f.user_id')->
join('twms_stock as g on b.oss_stockid=g.stk_id')->
join('twms_dept as h on a.osm_dept=h.id')->
join('twms_dept as i on i.id=h.pid')->
where($map)->
field("a.*,sum(oss_price*oss_count) as total,sum(oss_count) as count,cust_name,e.emp_name as osm_operator_name,oss_prodname,f.user_realname as osm_writer_name,oss_price price,c.sto_name as oss_store_name,b.oss_remark,g.stk_datetime,h.name as dept_name,i.name as center_name")->
group("oss_mainid")->limit($Page->firstRow.','.$Page->listRows)->order("osm_id desc")->select();
$list_sum=$model->alias("a")->join("twms_outstore_sub as b on a.osm_id=b.oss_mainid")->
join('twms_store as c on sto_id=oss_store')->
join('twms_customer as d on a.osm_customer=d.cust_id')->
join('twms_employee as e on a.osm_operator=e.emp_id')->
join('twms_user as f on a.osm_writer=f.user_id')->
where($map)->field("sum(oss_count) as sumNum,sum(oss_price*oss_count) as sumtotal")->select();
$sumNum=$list_sum['0']["sumNum"];
$sumtotal=$list_sum['0']["sumtotal"];
$this->assign("sumNum",$sumNum);
$this->assign("sumtotal",$sumtotal);
$this->assign("searchBy",$_GET['searchBy']);
$this->assign("keyword",$_GET['keyword']);
$this->assign("list",$list);
// $this->assign("list_sum",$list_sum);
$this->display();
}
public function delete(){
$model_outsub=D("Outstoresub");
$model_outmain=D("Outstoremain");
$model_outsub->where(array('oss_mainid'=>$_GET['osm_id']))->delete();
$model_outmain->where(array('osm_id'=>$_GET['osm_id']))->delete();
import('@.ORG.Util.SysLog');
SysLog::writeLog("删除出库记录");
$this->redirect("index");
}
public function view(){
$model_main=M('outstore_main');
$main=$model_main->alias('a')->
join('twms_employee as b on a.osm_operator=b.emp_id')->
join('twms_employee as c on a.osm_writer=c.emp_id')->
join('twms_customer as d on a.osm_customer=d.cust_id')->
where(array('osm_id'=>$_GET['osm_id']))->
field('a.*,b.emp_name as osm_operator_name,c.emp_name as osm_writer_name,d.cust_name as osm_customer_name')->find();
$model_sub=M('outstore_sub');
$list_sub=$model_sub->join('twms_prod_cate on oss_cate=pdca_id')->
join('twms_product on oss_prod=prod_id')->
join('twms_store on sto_id=oss_store')->where(array('oss_mainid'=>$_GET['osm_id']))->select();
$model_store=M('store');
$list_store=$model_store->select();
$this->assign('list_store',$list_store);
$this->assign('main',$main);
$this->assign('list_sub',$list_sub);
$this->assign('action','view');
$this->display('add');
}
public function toEdit(){
$model_main=D('Outstoremain');
$main=$model_main->alias('a')->
join('twms_employee as b on a.osm_operator=b.emp_id')->
join('twms_user as f on a.osm_writer=f.user_id')->
join('twms_customer as d on a.osm_customer=d.cust_id')->
where(array('osm_id'=>$_GET['osm_id']))->
field('a.*,b.emp_name as osm_operator_name,f.user_realname as osm_writer_name,d.cust_name as osm_customer_name')->find();
$model_sub=D('Outstoresub');
$list_sub=$model_sub->join('twms_product on prod_id=oss_prod')->
join('twms_store on sto_id=oss_store')->
join('twms_prod_cate on oss_cate=pdca_id')->
where(array('oss_mainid'=>$_GET['osm_id']))->select();
$model_store=D('Store');
$list_store=$model_store->select();
$this->assign('list_store',$list_store);
$this->assign('main',$main);
$this->assign('action','update');
$this->assign('list_sub',$list_sub);
$this->display('add');
}
public function doEdit(){
$model_main=D("Outstoremain");
$model_main->create();
$model_main->osm_datetime=date('Y-m-d H:i:s');
$model_sub=D('Outstoresub');
$model_sub->where(array('oss_mainid'=>$_POST['osm_id']))->delete();
for($i=0;$i<count($_POST["oss_prodname"]);$i++){
$data["oss_prod"]=$_POST["oss_prod"][$i];
$data["oss_prodname"]=$_POST["oss_prodname"][$i];
$data["oss_cate"]=$_POST["oss_cate"][$i];
$data["oss_count"]=$_POST["oss_count"][$i];
$data["oss_price"]=$_POST["oss_price"][$i];
$data["oss_store"]=$_POST["oss_store"][$i];
$data['oss_total']=$_POST['oss_total'][$i];
$data["oss_remark"]=$_POST["oss_remark"][$i];
$data["oss_datetime"]=date('Y-m-d H:i:s');
$data["oss_mainid"]=$_POST['osm_id'];
$data["oss_stockid"]=$_POST['oss_stockid'][$i];
$model_main->osm_total+=$data['oss_total'];
$model_sub->add($data);
}
$model_main->save();
import('@.ORG.Util.SysLog');
SysLog::writeLog("编辑出库记录");
$this->redirect("index");
}
public function add() {
$main['osm_sn']='OU-'.date('Ymd-His-').rand(100,999);
$this->assign('action','add');
$this->assign('main',$main);
$this->display();
}
public function doAdd(){
$model_outmain=D("Outstoremain");
$model_outsub=D("Outstoresub");
$model_stock=D("Stock");
$model_cust=D("Customer");
$model_outmain->create();
$model_outmain->osm_datetime=date('Y-m-d H:i:s');
$main_id=$model_outmain->add();
$data_main["osm_writer"]=$_SESSION['user']['user_id'];
for($i=0;$i<count($_POST["oss_prodname"]);$i++){
$data_sub["oss_stockid"]=$_POST["oss_stockid"][$i];
$data_sub["oss_mainid"]=$main_id;
$data_sub["oss_prodname"]=$_POST["oss_prodname"][$i];
$data_sub["oss_prod"]=$_POST["oss_prod"][$i];
$data_sub["oss_price"]=$_POST["oss_price"][$i];
$data_sub["oss_count"]=$_POST["oss_count"][$i];
$data_sub["oss_total"]=$_POST["oss_total"][$i];
$data_sub["oss_store"]=$_POST["oss_store"][$i];
$data_sub["oss_remark"]=$_POST["oss_remark"][$i];
$data_sub["oss_cate"]=$_POST["oss_cate"][$i];
$data_sub["oss_datetime"]=date('Y-m-d H:i:s');
$model_outsub->add($data_sub);
$data_main["osm_total"]+=$_POST["oss_total"][$i];
$model_stock->setDec("stk_count","stk_id={$_POST['oss_stockid'][$i]}",$_POST["oss_count"][$i]);
$model_stock->setDec("stk_total","stk_id={$_POST['oss_stockid'][$i]}",$_POST["oss_count"][$i]*$_POST["oss_price"][$i]);
}
$model_outmain->where(array('osm_id'=>$main_id))->save($data_main);
$model_stock->where(array("stk_count"=>array('elt',0)))->delete();
import('@.ORG.Util.SysLog');
SysLog::writeLog("添加出库记录");
$this->redirect("Outstore/index");
}
public function getProduct(){
$model=D("Product");
if($_GET['term']){
$prod_name=trim($_GET['term']);
$map["a.prod_name"]=array("like","%$prod_name%");
}
$list=$model->alias("a")->join("twms_prod_cate as b on a.prod_cate=b.pdca_id")->where($map)->order('pdca_id,prod_name')->select();
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
echo json_encode($result);
}
public function getStock(){
$model=M('stock');
if($_GET['term']){
$prod_name=trim($_GET['term']);
$map['stk_prodname']=array('like',"%$prod_name%");
}
$list=$model->join('twms_prod_cate on stk_cate=pdca_id')->
join('twms_store on stk_store=sto_id')->
where($map)->order('pdca_id desc,stk_datetime desc')->select();
if(count($list)>0){
foreach($list as $row){
$result[]=array(
'label'=>$row['stk_id']."\t/\t".$row['stk_prodname']."\t/\t".$row['pdca_name']."\t/\t".$row['stk_price']."\t/\t".$row['stk_count']."\t/\t".$row['stk_total']."\t/\t".$row['sto_name']."\t/\t".mb_substr($row['stk_datetime'],0,10,'utf-8'),
'category'=>"ID\t/\t产品名\t/\t类别\t/\t单价\t/\t数量\t/\t总价\t/\t仓库\t/\t日期",
'value'=>$row['stk_prodname'],
'stk_prod'=>$row['stk_prod'],
'stk_id'=>$row['stk_id'],
'stk_price'=>$row['stk_price'],
'stk_store'=>$row['stk_store'],
'stk_cate'=>$row['stk_cate'],
'stk_count'=>$row['stk_count'],
'stk_total'=>$row['stk_total'],
'stk_remark'=>$row['stk_remark'],
'stk_datetime'=>$row['stk_datetime'],
'stk_inmainid'=>$row['stk_inmainid'],
'pdca_name'=>$row['pdca_name']
);
}
}else{
$result[]=array(
'label'=>'（无）',
'category'=>'',
'value'=>''
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
public function getCustomer(){
$model=D('Customer');
if($_GET['term']){
$cust_name=trim($_GET['term']);
$map['cust_name']=array('like',"%$cust_name%");
}
// $list=$model->where($map)->order('cust_id desc')->select();
$list=$model->
alias("a")->
join("twms_dept as b on a.cust_dept=b.id")->
join("twms_dept as c on b.pid=c.id")-> 
field("a.*,if(b.pid='0', b.name, concat(c.name,'-',b.name)) as cust_dept_name")->
where($map)->order("cust_id asc")->select();
foreach($list as $row){
$result[]=array(
'id'=>$row['cust_id'],
'label'=>$row['cust_name']."-".$row["cust_dept_name"],
'value'=>$row['cust_name'],
'cust_dept'=>$row['cust_dept'],
// 'center'=>$row['pid']
);
}
echo json_encode($result);
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
'value'=>$row['sto_name']
);
}
echo json_encode($result);
}
public function getStockById(){
$model=D('Stock');
$list=$model->where(array('stk_id'=>$_GET['stk_id']))->order('stk_id desc')->select();
echo json_encode($list);
}

public function export() {
import("@.ORG.Util.Page");
$model=D("Outstoremain");
$map=$_SESSION["os_map"];
$list = $model->join("twms_outstore_sub as b on osm_id=oss_mainid")->
join('twms_store as c on sto_id=oss_store')->
where($map)->group("oss_mainid")->field('osm_id')->select();
$count=count($list);

$Page = new Page($count,D('Setting')->where(array('set_id'=>1))->getField('set_list_pagesize'));
$show = $Page->show();
$this->assign('page',$show);
$list=$model->alias("a")->join("twms_outstore_sub as b on a.osm_id=b.oss_mainid")->
join('twms_store as c on c.sto_id=b.oss_store')->
join('twms_customer as d on a.osm_customer=d.cust_id')->
join('twms_employee as e on a.osm_operator=e.emp_id')->
join('twms_user as f on a.osm_writer=f.user_id')->
join('twms_stock as g on b.oss_stockid=g.stk_id')->
join('twms_dept as h on a.osm_dept=h.id')->
join('twms_dept as i on i.id=h.pid')->
where($map)->
field("a.*,sum(oss_price*oss_count) as total,sum(oss_count) as count,cust_name,e.emp_name as osm_operator_name,oss_prodname,f.user_realname as osm_writer_name,oss_price price,c.sto_name as oss_store_name,b.oss_remark,g.stk_datetime,h.name as dept_name,i.name as center_name")->
group("oss_mainid")->limit($Page->firstRow.','.$Page->listRows)->order("osm_id desc")->select();
$list_sum=$model->alias("a")->join("twms_outstore_sub as b on a.osm_id=b.oss_mainid")->
join('twms_store as c on sto_id=oss_store')->
join('twms_customer as d on a.osm_customer=d.cust_id')->
join('twms_employee as e on a.osm_operator=e.emp_id')->
join('twms_user as f on a.osm_writer=f.user_id')->
where($map)->field("sum(oss_count) as sumNum,sum(oss_price*oss_count) as sumtotal")->select();
$sumNum=$list_sum['0']["sumNum"];
$sumtotal=$list_sum['0']["sumtotal"];
$this->assign("sumNum",$sumNum);
$this->assign("sumtotal",$sumtotal);
$this->assign("searchBy",$_GET['searchBy']);
$this->assign("keyword",$_GET['keyword']);
$this->assign("list",$list);
// $this->assign("list_sum",$list_sum);
$this->display();
// $this->echoExecl($list);
}

private function echoExecl($records)
{
error_reporting(E_ALL);
Vendor('Execl.PHPExcel');
Vendor('Execl.PHPExcel.IOFactory');
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
->setLastModifiedBy("Maarten Balliauw")
->setTitle("Office 2007 XLSX Test Document")
->setSubject("Office 2007 XLSX Test Document")
->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
->setKeywords("office 2007 openxml php")
->setCategory("Test result file");
$objPHPExcel->setActiveSheetIndex(0);
$arrStyle_title = array(
'font'=>array(
'bold'=>true,
'size'=>18,
'color'=>array('argb'=>'FF000000'),
),
'alignment'=>array(
'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
)
);
$arrStyle_column = array(
'font'=>array(
'bold'=>true,
'size'=>12,
'color'=>array('argb'=>'FFFFFFFF'),
),
'alignment'=>array(
'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
),
'borders'=>array(
'top'=>array(
'style'=>PHPExcel_Style_Border::BORDER_THIN,
),
'bottom'=>array(
'style'=>PHPExcel_Style_Border::BORDER_THIN,
),
),
'fill'=>array(
'type'=>PHPExcel_Style_Fill::FILL_SOLID,
'color'=>array('argb'=>'FF969696'),
)
);
$arrStyle_right = array(
'borders'=>array(
'right'=>array(
'style'=>PHPExcel_Style_Border::BORDER_THIN,
)
)
);
$arrStyle_content_column = array(
'alignment'=>array(
'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
)
);
$objPHPExcel->getActiveSheet()->setCellValue('A1','出库统计');
$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($arrStyle_title);
$objPHPExcel->getActiveSheet()
->setCellValue('A2','流水编号')
->setCellValue('B2','OA编号')
->setCellValue('C2','产品名称')
->setCellValue('D2','入库日期')
->setCellValue('E2','产品单价')
->setCellValue('F2','数量')
->setCellValue('G2','金额')
->setCellValue('H2','客户')
->setCellValue('I2','供应商')
->setCellValue('J2','制单人')
->setCellValue('K2','出库日期');
$objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($arrStyle_column);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(array('borders'=>array('left'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN))));
$objPHPExcel->getActiveSheet()->getStyle('K2')->applyFromArray($arrStyle_right);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
foreach($records as $key=>$list ){
$A = $list['osm_sn'];
$B = $list['oss_remark'];
$C = $list['oss_prodname'];
$D = $list['stk_datetime'];
$E = $list['price'];
$F = $list['count'];
$G = $list['total'];
$H = $list['cust_name'];
$I = $list['oss_store_name'];
$J = $list['osm_writer_name'];
$K = $list['osm_datetime'];
$n=$key+3;
$objPHPExcel->getActiveSheet()->getStyle('A'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('B'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('C'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('D'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('E'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('F'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('G'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('H'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('I'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('J'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('K'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()
->setCellValue('A'.$n,$A)
->setCellValue('B'.$n,$B)
->setCellValue('C'.$n,$C)
->setCellValue('D'.$n,$D)
->setCellValue('E'.$n,$E)
->setCellValue('F'.$n,$F)
->setCellValue('G'.$n,$G)
->setCellValue('H'.$n,$H)
->setCellValue('I'.$n,$I)
->setCellValue('J'.$n,$J)
->setCellValue('K'.$n,$K);
}
$objPHPExcel->getActiveSheet()->setTitle('出库统计');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="OA_total.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
$objWriter->save('php://output');
exit;
}

}
