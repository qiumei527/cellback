<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2016/8/19
 * Time: 上午9:56
 */

namespace app\index\controller;



use app\index\cell\Cellserver;
use app\index\table\tableHospital;
use think\controller\Rest;
use think\Request;
use think\Session;
use think\View;

class Hospital extends Rest
{
    public function index() {
        $data = tableHospital::count();
        if( $data == null ) {
            $page = [
                'page_num'=>0,
            ];
        }
        else {
            $page = [
                'page_num'=>$data,
            ];
        }

        return (new View())->fetch('hospital/index',$page);
    }

    public function ajax_hos_list(){
        $data = tableHospital::hos_list();
        if( $data == null ) {
            print 0;
        }
        else {
            print $data;
        }

    }

    public function ajax_count() {
        $data = tableHospital::count();
        if( $data == null ) {
            print 0;
        }
        else {
            print $data;
        }
    }

    public function ajax_index() {
        $data = tableHospital::getList(0,50000);
        if( $data == null ) {
            $page = [
                'page_num'=>0,
                'page_data'=>$data
            ];
        }
        else {
            $page = [
                'page_num'=>count($data),
                'page_data'=>$data
            ];
        }

        return $this->response(['data'=>$data],'json',200);
    }

    public function add() {

        return (new View())->fetch('hospital/add');

    }

    public function edit() {
        $hos_no = Request::instance()->param('hos_no');
        $hos_num = Request::instance()->param('hos_num');
        $hos_name = Request::instance()->param('hos_name');
        $hos_zone = Request::instance()->param('hos_zone');
        return (new View())->fetch('hospital/edit',['hos_no'=>$hos_no,'hos_num'=>$hos_num,'hos_name'=>$hos_name,'hos_zone'=>$hos_zone]);

    }

    public function add_one() {
        $zone = Request::instance()->param('zone');
        $hos_name = Request::instance()->param('hospital_name');
        $hos_number = Request::instance()->param('hospital_number');


        $data = ['cmd_id' => 2, 'cmd_flag' => 0, 'cmd_data' => ['attest'=>Session::get("attest"),
            'zone' => $zone, 'hospital_name' => $hos_name,'hospital_number'=>$hos_number]];
        $ret = Cellserver::postData(json_encode($data));

        if ($ret != null) {
            $retData = json_decode($ret, true);
            if ($retData && $retData['retCode'] == 0) {
                print 0;
            }
            else{
                print 'err1'.$retData['retCode'];
            }

        } else {
            print 10000;
        }
    }

    public function edit_one() {
        $hos_no = Request::instance()->param('hos_no');
        $hos_name = Request::instance()->param('hos_name');
        $zone = Request::instance()->param('hos_zone');


        $data = ['cmd_id' => 3, 'cmd_flag' => 0, 'cmd_data' => ['attest'=>Session::get("attest"),
            'zone' => $zone, 'hospital_name' => $hos_name,'hospital_no'=>intval($hos_no) ]];
        $ret = Cellserver::postData(json_encode($data));

        if ($ret != null) {
            $retData = json_decode($ret, true);
            if ($retData && $retData['retCode'] == 0) {
                print 0;
            }
            else{
                print 'err'.$retData['retCode'];
            }

        } else {
            print 10000;
        }
    }

    public function hos_exist() {
        $hos_number = Request::instance()->param('hospital_number');

        $data = ['cmd_id' => 4, 'cmd_flag' => 0, 'cmd_data' => ['attest'=>Session::get("attest"),
            'hospital_number'=>$hos_number]];
        $ret = Cellserver::postData(json_encode($data));

        if ($ret != null) {
            $retData = json_decode($ret, true);
            if ($retData && $retData['retCode'] == 0) {
                print 1;
            }
            else if ($retData && $retData['retCode'] == 0x27) {
                print 0;
            }

        } else {
            print 2;
        }
    }

    public function search_hos(){
        $hos = Request::instance()->param('search_hos');

        $data = tableHospital::search_hos($hos);

        if( $data == null ) {
            $page = [
                'page_num'=>0,
                'page_data'=>$data
            ];

        }
        else {
            $page = [
                'page_num'=>count($data),
                'page_data'=>$data
            ];
        }

        return (new View())->fetch('hospital/search_hos',$page);
    }
}