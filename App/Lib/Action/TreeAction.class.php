<?php
class TreeAction extends AppAction{
    // static public $treeList = array(); //存放无限分类结果如果一页面有多个无限分类可以使用 Tool::$treeList = array(); 清空
    // /**
    //  * 无限级分类
    //  * @access public 
    //  * @param Array $data     //数据库里获取的结果集 
    //  * @param Int $pid             
    //  * @param Int $count       //第几级分类
    //  * @return Array $treeList   
    //  */
    // public function tree(&$data,$pid = 0,$count = 1) {
    //     foreach ($data as $key => $value){
    //         if($value['Pid']==$pid){
    //             $value['Count'] = $count;
    //             self::$treeList []=$value;
    //             unset($data[$key]);
    //             self::tree($data,$value['Id'],$count+1);
    //         } 
    //     }
    //     return self::$treeList ;
    // }
    public function index(){
        $model=D('Dept');
        $datas=$model->field("id,name,pid,path,concat(path,'-',id) as bpath")->order("bpath")->select();
        foreach($datas as $key=>$value){ 
            $datas[$key]['count']=substr_count($value['bpath'],'-');
            $datas[$key]['npath']=str_repeat('---',$datas[$key]['count']-1).$datas[$key]['name'];
        } 
        echo "<br>";
        $this->assign("list",$datas);
        $this->display();
    }
    
}