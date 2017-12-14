<?php
namespace app\api\controller;
use \think\Request;
/*
后台登陆：
http://www.tuling123.com/member/robot/index.jhtml
2435882011@qq.com
edison111
*/
class Index
{
    public function index()
    {
        $api_key = config('tuling_api_key');
        $info = Request::instance()->param('info/s','你好','strval');
        $userid = Request::instance()->param('userid/s','001','strval');
        $array = array();
        $array['key'] = $api_key;
        $array['info'] = $info;
        $array['userid'] = $userid;
        $json_data = json_encode($array);
        // dump(json_encode($array));exit;
        echo $json_data;
        $tuling_http = 'http://www.tuling123.com/openapi/api';
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$tuling_http);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
        	array(
	        	'Content-Type: application/json; charset=utf-8',
	        	'Content-Length:' . strlen($json_data)
	        )
		);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$json_data);
        $result = json_decode(curl_exec($curl),true);
        curl_close($curl);
        dump($result);
    }
}
