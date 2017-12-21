<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Request;
use think\Db;
/*
后台
*/
class Index extends Controller
{

    public function index(){
        $result = Db::table('room_action_log')->order('log_time desc')->select();
        $this->assign('lists',$result);
        return $this->fetch();
    }
        
}
















