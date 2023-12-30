<?php
namespace app\admin\controller;
use app\BaseController;
use app\admin\controller\Login;

class LoginControl extends BaseController{
    public $adminUser = null;

    public function initialize()
    {
        parent::initialize();

        //判断是否登录
        if (!session('name')){  //没登录
            //$login = new Login();
            //return $login->index();
            //redirect('http://localhost:88/public/admin/login')->send();
            redirect('http://localhost:88/public/admin/login')->send();
            //header('Location:http://localhost:88/public');
            exit;
        }
    }
    // public function redirect(...$args)
    // {
    //     throw new HttpResponseException(redirect(...$args));
    // }
     
}