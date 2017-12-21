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
        $result = Db::table('room_action_log')->order('log_time desc')->paginate(10);
        // 获取分页显示
        $page = $result->render();
        $this->assign('lists',$result);
        $this->assign('page',$page);
        return $this->fetch();
    }
        
}
















