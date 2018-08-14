<?php
namespace app\api\controller;
use \think\Request;
use \think\Db;
class General
{
    //房间归档的回调接口
    public function archive(){
        // 是否为 POST 请求
        if (!Request::instance()->isPost()){
            return json(array('code'=>'-1','message'=>'only support for POST request'));
        }
        $data = file_get_contents("php://input");
        if(empty($data)){
            return json(array('code'=>'-2','message'=>'POST data is empty'));
        }
        $data = json_decode($data,true);
        $insert_data = array();

        $insert_data['channel_id'] = $data['channel_id'];
        $insert_data['channel_name'] = $data['channel_name'];
        $insert_data['user_id'] = $data['user_id'];
        $insert_data['user_name'] = $data['user_name'];
    }
}
















