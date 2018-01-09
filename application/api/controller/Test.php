<?php
namespace app\api\controller;
use \think\Request;
use \think\Db;

class Test
{
    public function index(){
        header("Access-Control-Allow-Origin: *");
        $name = Request::instance()->param('name',false,'strval,trim');
        $age = Request::instance()->param('age',false,'strval,trim');
        $insert_data = array();
        $insert_data['name'] = $name;
        $insert_data['age'] = $age;
        $result = Db::table('test')->insert($insert_data);
        if($result){
            echo json_encode(array('message'=>'good'));
        }else{
            echo json_encode(array('message'=>'not good'));
        }
    }
}
















