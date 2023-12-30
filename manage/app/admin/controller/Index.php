<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use app\admin\controller\LoginControl;
//use app\admin\controller\Systeminfo;
use app\admin\model\AdminModel as Mysql;
use think\facade\Db;

class Index extends LoginControl
{
    public function index()
    {
        //View::assign($sqlres);
        $data = Db::table('column')->select();
        $adminuser = session('name');
        return View::fetch('index',['data'=>$data,'admin'=>$adminuser]);
    }


    public function addcol(){//添加栏目
        return View::fetch('addcol');
    }


    public function systeminfo(){//系统信息
        $res = Db::table('systeminfo')->find(1);
        return View::fetch('systeminfo',['data'=>$res]);
    }


    public function webset(){//系统信息更新
        $res = Db::table('systeminfo')->where('id',1)->find();
        return View::fetch('webset',['data'=>$res]);
    }


    public function hbanner(){
        $res = Db::table('banner')->select();
        return View::fetch('hbanner',['data'=>$res]);
    }


    public function column(){
        $res = Db::table('column')->select();
        return View::fetch('column',['data'=>$res]);
    }


    public function substance(){//内容发布
        $data = Db::table('column')->select();
        return View::fetch('substance',['data'=>$data]);
    }


    public function contentlist(){
        return View::fetch('contentlist');
    }


    public function adminmanage(){
        $res = Db::table('adminuser')->where('id',1)->find();
        $listRes = Db::table('adminuser')->where('id','>',1)->select();
        if(!$listRes)
            $listRes = '';
        return View::fetch('adminmanage',['data'=>$res,'adminList'=>$listRes]);
    }


    public function membermanage(){//会员管理
        $res = Db::table('user')->select();
        return View::fetch('membermanage',['data'=>$res]);
    }


    public function leavemanage(){
        $res = Db::table('relinquere')->select();
        return View::fetch('leavemanage',['data'=>$res]);
    }


    public function friendlinks(){
        $res = Db::table('friendlinks')->select();
        return View::fetch('friendlinks',['data'=>$res]);
    }


    public function yunzhixun(){
        $res = Db::table('ucpaas')->find();
        return View::fetch('yunzhixun',['data'=>$res]);
    }


    public function editContent(){
        $data = Db::table('column')->select();
        return View::fetch('editContent',['data'=>$data]);
    }


    public function addAdmin(){
        return View::fetch('addAdmin');
    }


    public function addUser(){
        return View::fetch('addUser');
    }


    public function editAdminuser(){
        return View::fetch('editAdminuser');
    }


    public function editUser(){
        return View::fetch('editUser');
    }


    public function editBanner(){
        return View::fetch('editBanner');
    }
}
