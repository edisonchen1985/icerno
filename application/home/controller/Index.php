<?php
namespace app\home\controller;
use \think\Controller;
use \think\Request;

class Index extends Controller
{

    public function index(){
        return $this->fetch();
    }
    public function file_management(){
    	$roomId = Request::instance()->param('roomId',0);
    	echo $roomId;exit;
    	$this->assign('lists',$result);
    	return $this->fetch();
    }
        
}
















