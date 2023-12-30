<?php
namespace app\admin\controller;
use app\admin\controller\LoginControl;
class Upimg extends LoginControl{
    public function upimg(){
        $file = request()->file('file');

        $savename = \think\facade\Filesystem::putFile( 'topic', $file);

        $savename=str_replace("\\","/",$savename);

        $res = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER["SERVER_PORT"].'/runtime/storage/'.$savename;
        
        return json_encode($res);
    }
    public function editimg(){
        $file = request()->file('file');

        $savename = \think\facade\Filesystem::putFile( 'topic', $file);

        $savename=str_replace("\\","/",$savename);

        $res = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER["SERVER_PORT"].'/runtime/storage/'.$savename;

        return json_encode(['code'=>0,'msg'=>'','data'=>['src'=>$res]]);
    }
}