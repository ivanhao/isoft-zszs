<?php
class AppAction extends Action{
public $authClear='mail919705';
public $authCode='6e4023d27d32abcdaaf00791a7243700';
public $startYear=2012;
public $startMonth=4;
public $startDay=12;
public $intervalMonth=3;
public $version='v1.2.0 Build 120410';
public $endStamp;
function __construct(){
parent::__construct();
session_start();
if($_SESSION["user"]==null){
exit("<script>window.parent.location='".U('Public/login')."'</script>");
}
}
}
