<?php
namespace app\api\controller;
use \think\Request;
use \think/Db;
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
            $room = $this->ai_login_name.'-'.$user_name.'-'.substr(time(),5);
            $respond_message = "请假房间:".$room." 已为您创建成功，请从左侧频道列表处进入！";
            $sender = new \RocketChat\User($user_name, 'empty');
            $li_lei = new \RocketChat\User('lilei', 'empty');//现在固定一个
            $ai = new \RocketChat\User($this->ai_login_name, $this->ai_login_password);
            $users = array();
            $users[] = $ai;
            $users[] = $sender;
            $users[] = $li_lei;
            $group_welcome_message = "欢迎".$user_name."来到AI请假室";
            $this->createGroup($group_welcome_message,$room,$users);
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
    //用户离开房间调用的接口
    public function leaveRoom(){

        $data = file_get_contents("php://input");
        if(empty($data)){
            exit;
        }
        $data = json_decode($data,true);


        $insert_data = array();
        $insert_data['channel_id'] = $data['channel_id'];
        $insert_data['channel_name'] = $data['channel_name'];
        $insert_data['log_type'] = 1; //log_type为1是离开房间
        $insert_data['user_id'] = $data['user_id'];
        $insert_data['user_name'] = $data['user_name'];
        $insert_data['log_time'] = time();

        Db::table('room_action_log')->insert($insert_data);
    }

    //用户加入公共房间调用的接口
    public function leaveRoom(){

        $data = file_get_contents("php://input");
        if(empty($data)){
            exit;
        }
        $data = json_decode($data,true);


        $insert_data = array();
        $insert_data['channel_id'] = $data['channel_id'];
        $insert_data['channel_name'] = $data['channel_name'];
        $insert_data['log_type'] = 0; //log_type为0是离开房间
        $insert_data['user_id'] = $data['user_id'];
        $insert_data['user_name'] = $data['user_name'];
        $insert_data['log_time'] = time();

        Db::table('room_action_log')->insert($insert_data);
    }
}
















