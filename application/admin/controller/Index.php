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
        $this->assign('a',0);
        return view();
    }
        
}
















