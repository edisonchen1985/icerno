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
    private $ai_login_name;
    private $ai_login_password;

    public function __construct(){
        define('REST_API_ROOT', '/api/v1/');
        define('ROCKET_CHAT_INSTANCE', 'https://cc.nomalis.com');
        $this->ai_login_name = 'ai';
        $this->ai_login_password = 'ai';
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
        //如果消息是请假的话，则创建一个请假房间
        if($info == '请假'){
            $user_name = $data['user_name'];
            $room = $this->ai_login_name.'-'.$user_name.'-'.time();
            $respond_message = "请假房间已为您创建成功，请从左侧房间导航处寻找：".$room;
            $users = array();
            $users[] = $this->ai_login_name;
            $users[] = $user_name;
            $group_welcome_message = "欢迎您进入AI请假室";
            $this->createGroup($group_welcome_message,$room,$users);
            $this->respond($respond_message,$room);
        }else{
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
        }
        
        $room = $data['channel_id'];
        $this->respond($respond_message,$room);
    }
    public function respond($respond_message,$room){

        // $api = new \RocketChat\Client();
        // login as the main admin user
        $admin = new \RocketChat\User($this->ai_login_name, $this->ai_login_password);
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
    public function createGroup($respond_message,$room,$users){
         /*rocket-chat会调用此接口，并传过来json数据，格式为：
            {"token":"wDkSSZB3tq4k69Kpi5MtHxTn","bot":false,"trigger_word":"ai","channel_id":"WWXkMNX2dRerYqmBY","channel_name":"test","message_id":"C6N39ZTuivcwYsnnp","timestamp":"2017-12-13T07:59:36.764Z","user_id":"SYEcdXMeBnEkdY5Qs","user_name":"edison","text":"i hahaha"}
        */
        if(!is_array($users)){
            return false;
        }
        $admin = new \RocketChat\User($this->ai_login_name, $this->ai_login_password);
        if( $admin->login() ) {
            // echo "admin user logged in\n";
            // $admin->info();
            // echo "I'm {$admin->nickname} ({$admin->id}) "; echo "\n";
            // create a new channel
            $channel = new \RocketChat\Channel( $room,$users);
            $channel->create();
            // post a message
            $channel->postMessage($respond_message);
        };
    }
}
















