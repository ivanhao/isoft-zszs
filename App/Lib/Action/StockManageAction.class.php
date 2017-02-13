<?php // 5isoft.cn , Copyright(C) , All rights reserved.

class StockManageAction extends AppAction{
public function stock(){
import("@.ORG.Util.Page");
$model=D("Stock");
if($_GET["searchBy"]!="")$map[$_GET["searchBy"]]=array("like","%{$_GET['keyword']}%");
// if($_GET["osm_sn"]!="")$map["osm_sn"]=array("like","%{$_GET['osm_sn']}%");
// if($_GET["osm_customer_name"]!="")$map["cust_name"]=array("like","%{$_GET['osm_customer_name']}%");
// if($_GET["osm_operator_name"]!="")$map["e.emp_name"]=array("like","%{$_GET['osm_operator_name']}%");
// if($_GET["oss_total_start"]!=""&&$_GET["oiss_total_end"]=="")$map["osm_total"]=array("egt","{$_GET['oss_total_start']}");
// if($_GET["oss_total_start"]==""&&$_GET["oss_total_end"]!="")$map["osm_total"]=array("elt","{$_GET['oss_total_end']}");
// if($_GET["oss_total_start"]!=""&&$_GET["oss_total_end"]!=""){
// $map["osm_total"]=array(array("egt","{$_GET['oss_total_start']}"),array("elt","{$_GET['oss_total_end']}"));
// }
// if($_GET["osm_writer_name"]!="")$map["f.emp_name"]=array("like","%{$_GET['osm_writer_name']}%");
// if($_GET["osm_date_start"]!=""&&$_GET["osm_date_end"]=="")$map["osm_datetime"]=array("egt","{$_GET['osm_date_start']}".' 00:00:00');
// if($_GET["osm_date_start"]==""&&$_GET["osm_date_end"]!="")$map["osm_datetime"]=array("elt","{$_GET['osm_date_end']}".' 59:59:59');
// if($_GET["osm_date_start"]!=""&&$_GET["osm_date_end"]!=""){
// $map["osm_datetime"]=array(array("egt","{$_GET['osm_date_start']}".' 00:00:00'),array("elt","{$_GET['osm_date_end']}".' 59:59:59'));
// }
if($_GET["oss_prodname"]!=""){
$map["prod_name"]=array("like","%{$_GET['oss_prodname']}%");
$model->join("twms_product on stk_prod=prod_id");
}
if($_GET["oss_store_name"]!=""){
$map["sto_name"]=array("like","%{$_GET['oss_store_name']}%");
}
$_SESSION["sm_map"]=$map;
$count=$model->count();
$Page = new Page($count,D('Setting')->where(array('set_id'=>1))->getField('set_list_pagesize'));
$show = $Page->show();
$this->assign('page',$show);
$list=$model->alias('a')->join('twms_prod_cate as b on stk_cate=pdca_id')->
join('twms_product as c on c.prod_id=stk_prod')->
join('twms_store on stk_store=sto_id')->
where($map)->
order('stk_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
$this->assign("list",$list);
$model_store=D('Store');
$list_store=$model_store->order('sto_id desc')->select();
$this->assign("list_store",$list_store);
$model_prodcate=D('Prodcate');
$list_prodcate=$model_prodcate->order('pdca_id desc')->select();
$this->assign("list_prodcate",$list_prodcate);
$this->display();
}
public function export() {
$model=D("Stock");
$list=$model->alias('a')->join('twms_prod_cate as b on stk_cate=pdca_id')->
join('twms_product as c on c.prod_id=stk_prod')->
join('twms_store on stk_store=sto_id')->
where($_SESSION["sm_map"])->
order('stk_id desc')->select();
$this->assign("list",$list);
$this->display();
}
public function stats(){
import("@.ORG.Util.Page");
$model=M("stock");
$list = $model->alias("a")->join("twms_prod_cate as b on stk_cate=pdca_id")->
group("stk_prod")->field('stk_prodname')->select();
$count=count($list);
$Page = new Page($count,D('Setting')->where(array('set_id'=>1))->getField('set_list_pagesize'));
$show = $Page->show();
$this->assign('page',$show);
$list=$model->alias("a")->join("twms_prod_cate as b on stk_cate=pdca_id")->
group("stk_prod")->field("a.*,b.pdca_name,sum(stk_count) as `count`,sum(stk_total) as `total`")->
order('stk_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
$this->assign("list",$list);
$this->display();
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

public function getStockById(){
$model=D('Stock');
$one=$model->where(array('stk_id'=>$_GET['stk_id']))->find();
echo json_encode($one);
}
public function doEditStock(){
$model=M("Stock");
$model->save($_GET);
import('@.ORG.Util.SysLog');
SysLog::writeLog("编辑库存");
echo 1;
}
public function delete(){
$model_main=D("Stock");
$map["stk_id"]=$_GET['stk_id'];
$model_main->where($map)->delete();
import('@.ORG.Util.SysLog');
SysLog::writeLog("删除库存记录");
$this->redirect("stock");
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
$objPHPExcel->getActiveSheet()->setCellValue('A1','库存统计');
$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($arrStyle_title);
$objPHPExcel->getActiveSheet()
->setCellValue('A2','产品')
->setCellValue('B2','单价')
->setCellValue('C2','数量')
->setCellValue('D2','总价')
->setCellValue('E2','仓库');
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($arrStyle_column);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(array('borders'=>array('left'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN))));
$objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($arrStyle_right);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
foreach($records as $key=>$list ){
$A = $list['iss_name'];
$B = $list['iss_price'];
$C = $list['count'];
$D = $list['total'];
$E = $list['iss_store'];
$n=$key+3;
$objPHPExcel->getActiveSheet()->getStyle('A'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('B'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('C'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('D'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()->getStyle('E'.$n)->applyFromArray($arrStyle_content_column);
$objPHPExcel->getActiveSheet()
->setCellValue('A'.$n,$A)
->setCellValue('B'.$n,$B)
->setCellValue('C'.$n,$C)
->setCellValue('D'.$n,$D)
->setCellValue('E'.$n,$E);
}
$objPHPExcel->getActiveSheet()->setTitle('库存统计');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="OA_total.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
$objWriter->save('php://output');
exit;
}

}


