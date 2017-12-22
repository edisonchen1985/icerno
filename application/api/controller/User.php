<?php
namespace app\api\controller;
use \think\Request;
use \think\Db;
/*
用户相关
*/
class User
{
    //jsonp调用的验证邀请码的接口
    public function register(){
        $invitation_code = Request::instance()->param('invitation_code',false);
        $response = array();
        if(empty($invitation_code)){
            $response['code'] = '-1';
            $response['message'] = '缺少code';
        }else{
            if($invitation_code != '6666'){
                $response['code'] = '-2';
                $response['message'] = '邀请码不对';
            }else{
                $response['code'] = '0';
                $response['message'] = '成功';
            }
            
        }
        return jsonp($response);
    }
}
















