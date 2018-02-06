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

    //test 返回json数据
    public function test(){
        $response = array();
        $response['uuid'] = '116L068M3665G0E3';
        $response['corpUuid'] = '113K11A4B8R3L003';
        $response['deptUuid'] = '116G12IOL065G3HK';
        $response['deptName'] = '战略研发部';
        $response['idType'] = '身份证';
        $response['idCode'] = '110108199003013116';
        $response['staffCode'] = 'A02032';
        $response['perName'] = '翟露露';
        $response['jobTitle'] = 'SDG.战略研发部';
        echo json_encode($response);
    }
    //test 返回jsonp数据
    public function jsonp(){
        $response = array();
        $response['uuid'] = '116L068M3665G0E3';
        $response['corpUuid'] = '113K11A4B8R3L003';
        $response['deptUuid'] = '116G12IOL065G3HK';
        $response['deptName'] = '战略研发部';
        $response['idType'] = '身份证';
        $response['idCode'] = '110108199003013116';
        $response['staffCode'] = 'A02032';
        $response['perName'] = '翟露露';
        $response['jobTitle'] = 'SDG.战略研发部';
        return jsonp($response);
    }
    public function qmt_login(){
        header("Access-Control-Allow-Origin: *");
        $data = file_get_contents("php://input");
        $data_array = json_decode($data,true);
        $url = $data_array['apiUrl'];
        // $url = 'http://192.168.6.214:8080/CCP/rest/api/v1/formpost';
        // $data = array();
        // $data['name'] = 'Tom';
        // $data['age'] = '12';
        // $json_data = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length:' . strlen($data)
            )
        );
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        $result = json_decode(curl_exec($curl),true);
        curl_close($curl);
        echo json_encode($result);

    }
    public function qmt_login_check(){
        header("Access-Control-Allow-Origin: *");
        $data = file_get_contents("php://input");
        $data_array = json_decode($data,true);
        $url = $data_array['apiUrl'];
        $param = json_encode($data_array['param']);
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length:' . strlen($param)
            )
        );
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$param);
        $result = json_decode(curl_exec($curl),true);
        curl_close($curl);
        echo json_encode($result);

    }
}
















