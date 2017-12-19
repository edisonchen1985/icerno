<?php
namespace app\api\controller;
use \think\Request;
/*
后台登陆：
http://www.tuling123.com/member/robot/index.jhtml
2435882011@qq.com
edison111
*/
/*
php版本的rocket-chat API client:
https://github.com/Fab1en/rocket-chat-rest-client
*/
class Index
{
    public function __construct(){
        define('REST_API_ROOT', '/api/v1/');
        define('ROCKET_CHAT_INSTANCE', 'http://localhost:3000');
    }

    public function index()
    {
        /*rocket-chat会调用此接口，并传过来json数据，格式为：
            {"token":"wDkSSZB3tq4k69Kpi5MtHxTn","bot":false,"trigger_word":"ai","channel_id":"WWXkMNX2dRerYqmBY","channel_name":"test","message_id":"C6N39ZTuivcwYsnnp","timestamp":"2017-12-13T07:59:36.764Z","user_id":"SYEcdXMeBnEkdY5Qs","user_name":"edison","text":"i hahaha"}
        */
        $data = file_get_contents("php://input");
        $data = json_decode($data,true);

        
        $info = $data['text'];
        $userid = $data['user_id'];

        $api_key = config('tuling_api_key');
        // $info = Request::instance()->param('info/s','你好','strval');
        // $userid = Request::instance()->param('userid/s','001','strval');
        $array = array();
        $array['key'] = $api_key;
        $array['info'] = $info;
        $array['userid'] = $userid;
        $json_data = json_encode($array);
        // dump(json_encode($array));exit;
        // echo $json_data;
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
        

        $respond_message = $result['text'];
        $room = $data['channel_id'];
        $this->respond($respond_message,$room);
    }
    public function respond($respond_message,$room){
        
        // define('ROCKET_CHAT_INSTANCE', 'http://192.168.6.35:3000');

        // $api = new \RocketChat\Client();
        // login as the main admin user
        //local的账户
        $rocket_login_username = 'old';
        $rocket_login_password = 'old';
        $admin = new \RocketChat\User($rocket_login_username, $rocket_login_password);
        if( $admin->login() ) {
            // echo "admin user logged in\n";
            // $admin->info();
            // echo "I'm {$admin->nickname} ({$admin->id}) "; echo "\n";
            // create a new channel
            $channel = new \RocketChat\Channel( $room);
            // $channel->create();
            // post a message
            $channel->postMessage($respond_message);
        };
        

    }
    public function createGroup(){
        /*rocket-chat会调用此接口，并传过来json数据，格式为：
            {"token":"wDkSSZB3tq4k69Kpi5MtHxTn","bot":false,"trigger_word":"ai","channel_id":"WWXkMNX2dRerYqmBY","channel_name":"test","message_id":"C6N39ZTuivcwYsnnp","timestamp":"2017-12-13T07:59:36.764Z","user_id":"SYEcdXMeBnEkdY5Qs","user_name":"edison","text":"i hahaha"}
        */
        $data = file_get_contents("php://input");
        $data = json_decode($data,true);

        
        $info = $data['text'];
        $userid = $data['user_id'];
        //ai的账户
        $rocket_login_username = 'ai1';
        $rocket_login_password = 'ai1';
        $admin = new \RocketChat\User($rocket_login_username, $rocket_login_password);

        //edison的账户
        $edison_login_username = 'edison';
        $edison_login_password = '';
        $edison = new \RocketChat\User($edison_login_username, $edison_login_password);
        if( $admin->login() ) {
            $room = 'ask-for-a-leave3';
            // create a new channel
            $channel = new \RocketChat\Channel( $room,array($admin,$edison));
            // $channel->create();
            $url = 'https://www.baidu.com';
            $channel->postMessage($url);
        }
    }

    public function createUser(){
        $rocket_login_username = 'edison';
        $rocket_login_password = 'edison';
        $admin = new \RocketChat\User($rocket_login_username, $rocket_login_password);
        if( $admin->login() ) {
            // $rocket_login_username = 'ai1';
            // $rocket_login_password = 'ai1';
            // $fields = array();
            // $fields['email'] = 'ai@qq.com';
            // $fields['nickname'] = 'ai1';
            // $new = new \RocketChat\User($rocket_login_username, $rocket_login_password,$fields);
            // $result = $new->create();
            // dump($result);
            $client = new \RocketChat\Client();
            $all = $client->list_users();
            dump($all);
        };
        
    }
}
















