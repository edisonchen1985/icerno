<?php
namespace app\api\controller;
use \think\Request;
use think\Db;
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
        error_reporting(E_ALL^E_NOTICE^E_WARNING);//关闭所有notice 和 warning 级别的错误。
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
        
        $room = $data['channel_id'];
        /*
        if($info == 'Uploaded an image'){
            //处理上传一个图片
        }
        */

        if($info == '小姐姐'){
            $respond_message = '正在努力为你搜索小姐姐的照片...';
            $this->respond($respond_message,$room);
            sleep(2);
            $this->updateMongo();
        }else if($info == '小哥哥'){
            $respond_message = '正在努力为你搜索小哥哥的照片...';
            $this->respond($respond_message,$room);
            sleep(2);
            $this->updateMongo2();

        }else if($info == '网站'){
            $respond_message = '正在后台大数据匹配寻找最有才华隆正小哥哥的网站：';
            $this->respond($respond_message,$room);
            sleep(3);
            $this->updateMongo3();

        }else{
            $respond_message = $result['text'];
            $this->respond($respond_message,$room);
        }
    }
    public function respond($respond_message,$room){
        
        // define('ROCKET_CHAT_INSTANCE', 'http://192.168.6.35:3000');

        // $api = new \RocketChat\Client();
        // login as the main admin user
        //local的账户
        $rocket_login_username = 'dizzy.ai';
        $rocket_login_password = 'Dizzy.ai';
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
    public function joinRoom(){

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

    //培训时直接更改MONGODB的函数--小姐姐
    public function updateMongo(){
        $data = Db::table('room_action_log')->where(array('channel_name'=>'test'))->find();
        $number = intval($data['log_type']);


        $mongo = 'mongodb://localhost:3001';
        $m = new \MongoClient($mongo); // 连接


        $db = $m->meteor;            // 选择一个数据库
        $collection = $db->rocketchat_message; // 选择集合
        $cursor = $collection->find()->sort(array('ts'=>-1))->limit(1);
        $id = '';
        // 迭代显示文档标题
        foreach ($cursor as $document) {
            // echo $document["_id"] . "\n";
            $id = $document['_id'];
        }



        $title_link = ['/file-upload/KWM8rNNPeES6siuBC/4.jpeg','/file-upload/vwawuyDtgqH6edHKP/1.jpeg','/file-upload/fSudYrhzgiLQpqwi5/2.jpeg','/file-upload/kCAB2iNmCxE7fmsco/3.jpeg'];
        $attachments = array();
        $file = array();
        $file['title'] = $number.'.jpeg';
        $file['type'] = 'file';
        $file['description'] = '小姐姐美不美哦';
        $file['title_link'] = $title_link[$number];
        $file['title_link_download'] = true;
        $file['image_type'] = 'image/jpeg';
        $file['image_url'] = $title_link[$number];
        $file['image_size'] = 444;


        $attachments[] = $file;

        $number++;
        Db::table('room_action_log')->where(array('channel_name'=>'test'))->update(array('log_type'=>$number));

        // 更新文档
        $result = $collection->update(array('_id'=>$id), array('$set'=>array("attachments"=>$attachments,'msg'=>'怎么样？')));
        $m->close();
    }

    //培训时直接更改MONGODB的函数--小哥哥
    public function updateMongo2(){
        $data = Db::table('room_action_log')->where(array('channel_name'=>'test'))->find();
        $number = intval($data['log_type']);


        $mongo = 'mongodb://localhost:3001';
        $m = new \MongoClient($mongo); // 连接


        $db = $m->meteor;            // 选择一个数据库
        $collection = $db->rocketchat_message; // 选择集合
        $cursor = $collection->find()->sort(array('ts'=>-1))->limit(1);
        $id = '';
        // 迭代显示文档标题
        foreach ($cursor as $document) {
            // echo $document["_id"] . "\n";
            $id = $document['_id'];
        }



        $title_link = ['/file-upload/KWM8rNNPeES6siuBC/4.jpeg','/file-upload/vwawuyDtgqH6edHKP/1.jpeg','/file-upload/fSudYrhzgiLQpqwi5/2.jpeg','/file-upload/kCAB2iNmCxE7fmsco/3.jpeg','/file-upload/RFe8y5E5bBWG4AqWg/IMG_0205.jpeg','/file-upload/nGM3Rj7TF5g9hMjJE/IMG_0186.jpeg'];
        $attachments = array();
        $file = array();
        $file['title'] = $number.'.jpeg';
        $file['type'] = 'file';
        $file['description'] = '小哥哥哦';
        $file['title_link'] = $title_link[$number];
        $file['title_link_download'] = true;
        $file['image_type'] = 'image/jpeg';
        $file['image_url'] = $title_link[$number];
        $file['image_size'] = 444;


        $attachments[] = $file;

        $number++;
        Db::table('room_action_log')->where(array('channel_name'=>'test'))->update(array('log_type'=>$number));

        // 更新文档
        $result = $collection->update(array('_id'=>$id), array('$set'=>array("attachments"=>$attachments,'msg'=>'怎么样？')));
        $m->close();
    }

    //培训时直接更改MONGODB的函数--小哥哥
    public function updateMongo3(){


        $mongo = 'mongodb://localhost:3001';
        $m = new \MongoClient($mongo); // 连接


        $db = $m->meteor;            // 选择一个数据库
        $collection = $db->rocketchat_message; // 选择集合
        $cursor = $collection->find()->sort(array('ts'=>-1))->limit(1);
        $id = '';
        // 迭代显示文档标题
        foreach ($cursor as $document) {
            // echo $document["_id"] . "\n";
            $id = $document['_id'];
        }



        $urls = array();
        $url = array();
        $url['url'] = "https://edisonchen1985.top/icerno/public/index.php/home/index";
        $url['meta'] = array('pageTitle'=>"隆正最有才华的小哥哥");
        $url['headers'] = array('contentType'=>"text/html; charset=utf-8");
        $parsedUrl = array();
        $parsedUrl['host'] = "edisonchen1985.top";
        $parsedUrl['pathname'] = "/icerno/public/index.php/home/index";
        $parsedUrl['host'] = "edisonchen1985.top";
        $parsedUrl['protocol'] = "https:";
        $parsedUrl['hostname'] = "edisonchen1985.top";
        $url['parsedUrl'] = $parsedUrl;
        $urls[] = $url;



        // 更新文档
        $result = $collection->update(array('_id'=>$id), array('$set'=>array("urls"=>$urls,'msg'=>'https://edisonchen1985.top/icerno/public/index.php/home/index')));
        $m->close();
    }
}
















