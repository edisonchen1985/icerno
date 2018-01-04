<?php
namespace app\home\controller;
use \think\Controller;
use \think\Request;

class Index extends Controller
{

    public function index(){
        return $this->fetch();
    }
    public function file_management(){
    	$roomId = Request::instance()->param('roomId',0);

    	$mongo = 'mongodb://localhost:3001';
        $m = new \MongoClient($mongo); // 连接


        $db = $m->meteor;            // 选择一个数据库
        $collection = $db->rocketchat_uploads; // 选择集合
        $where = array();
        $where['rid']=$roomId;
        $cursor = $collection->find($where);
        foreach ($cursor as $document) {
        	$image_url = 'http://localhost:3000/file-upload/'.$document['_id'].'/'.$document['name'];
        	echo '<a href="'.$image_url.'">'.$document['name'];
        	echo '<br>';
		}
    	// echo $roomId;
    	exit;
    	$this->assign('lists',$result);
    	return $this->fetch();
    }
        
}
















