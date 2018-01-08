<?php
namespace app\home\controller;
use \think\Controller;
use \think\Request;
use think\Db;

class Index extends Controller
{

    public function index(){
        return $this->fetch();
    }
    //rocketchat内嵌的iframe页面
    public function file_management(){
    	$roomId = Request::instance()->param('roomId',0);

    	$mongo = 'mongodb://localhost:3001';
        $m = new \MongoClient($mongo); // 连接


        $db = $m->meteor;            // 选择一个数据库
        $collection = $db->rocketchat_uploads; // 选择集合
        $where = array();
        $where['rid']=$roomId;
        $cursor = $collection->find($where);

        $file_lists = array();
        foreach ($cursor as $document) {
        	$file = array();
        	$image_url = 'http://localhost:3000/file-upload/'.$document['_id'].'/'.$document['name'];
        	$file['url'] = $image_url;
        	$file['name'] = $document['name'];
        	$file_lists[] = $file;
		}
    	// echo $roomId;
    	$this->assign('file_lists',$file_lists);
    	$this->assign('roomId',$roomId);
    	return $this->fetch();
    }
    //保存文件和文件夹
    public function files_save(){
    	$folder_name = Request::instance()->param('folder_name',false,'trim');
    	$files = Request::instance()->param('files',false);
    	$roomId = Request::instance()->param('roomId',false,'trim');
    	if(empty($folder_name)){
    		$code = '-1';
    		$message = '缺少文件夹名称';
    	}else if(empty($files)){
    		$code = '-2';
    		$message = '缺少文件名称';
    	}else if(empty($roomId)){
    		$code = '-3';
    		$message = '缺少房间ID';
    	}else{
    		$files = json_decode($files,true);
    		$where = array();
    		$where['channel_id'] = $roomId;
    		//判断此房间名是否已经被存储过
    		$if_exist = Db::table('tm_room_names')->where($where)->find();
    		if(empty($if_exist)){
    			$tm_room_names = array();
    			$tm_room_names['channel_id'] = $roomId;
    			$tm_room_names['create_time'] = time();
    			$room_id = Db::table('tm_room_names')->insertGetId($tm_room_names); //存储房间名称并返回主键ID
    		}else{
    			$room_id = $if_exist['id'];
    		}
    		//存 储文件夹名称
    		$tm_folder_names = array();
    		$tm_folder_names['name'] = $folder_name;
    		$tm_folder_names['create_time'] = time();
    		$tm_folder_names['room_id'] = $room_id;
    		$folder_id = Db::table('tm_folder_names')->insertGetId($tm_folder_names); //存储文件夹名称并返回主键ID
    		foreach ($files as $key => $value) {
    			# code...
    			$tm_file_names = array();
    			$tm_file_names['name'] = $value;
    			$tm_file_names['folder_id'] = $folder_id;
    			$tm_file_names['create_time'] = time();
    			Db::table('tm_file_names')->insert($tm_file_names);
    		}
    		$code = '0';
    		$message = '成功';
    	}

    	$response = array();
    	$response['code'] = $code;
    	$response['message'] = $message;
    	return json($response);
    }

    //展示用户的文件夹和文件
    public function file_show(){
    	$roomId = Request::instance()->param('roomId',false,'trim');
    	if(empty($roomId)){
    		$code = '-1';
    		$message = '缺少房间ID';
    	}
    	$where = array();
		$where['channel_id'] = $roomId;
		$result = Db::table('tm_room_names')->where($where)->find();
		if(empty($result)){

		}else{
			$where = array();
			$where['room_id'] = $result['id'];
			$folder_result = Db::table('tm_folder_names')->where($where)->select();
			dump($folder_result);
		}
    }
        
}
















