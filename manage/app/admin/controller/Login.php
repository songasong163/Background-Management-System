<?php
namespace app\admin\controller;
use app\admin\model\LoginModel;
use think\facade\View;
class Login{
    public function index(){
        return View::fetch('login/login');
    }
    /**
     * 登录验证字段:
     * username
     * password
     * verification
     * 
     * 状态码:
     * 101:成功！201：用户名错误！202：密码错误！203：验证码错误！
     */
    public function check(){
        $data = input('post.');
        $userRes = LoginModel::where('userName', $data['username'])->find();
        $passRes = LoginModel::where('passWord', md5($data['password']))->find();
        if($userRes){
            if($passRes){
                if(captcha_check($data['verification'])){
                    session('name', $data['username']);
                    $time=date('Y-m-d H:i:s',time());
                    LoginModel::update(['lastlogintime' => $time], ['userName' => $data['username']]);
                    
                    return json_encode(['code'=>101,'msg'=>'欢迎登录！']);
                }
                return json_encode(['code'=>203,'msg'=>'验证码错误！']);
            }
            return json_encode(['code'=>202,'msg'=>'密码错误！']);
        }
        return json_encode(['code'=>201,'msg'=>'用户名错误！']);
    }
}