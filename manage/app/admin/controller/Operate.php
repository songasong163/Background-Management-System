<?php
namespace app\admin\controller;
use think\db\exception\DbException;
use think\facade\Db;
use app\admin\controller\Clear;
use app\admin\controller\LoginControl;
use app\admin\controller\SysteminfoM;
class Operate extends LoginControl{
    public function getSysinfo(){
        $sys = new SysteminfoM();
        $cpuUsage = $sys->getCpuUsage();
        $memUsage = $sys->getMemoryUsage();
        $webinfo = ['os'=>PHP_OS,'environment'=>$_SERVER["SERVER_SOFTWARE"]];
        return json_encode(['cpu'=>$cpuUsage,'mem'=>$memUsage,'webinfo'=>$webinfo]);
    }


    public function upWebinfo(){
        $data = input('put.');
        Db::name('systeminfo')->where('id',1)->data($data)->update();
    }


    public function upBanner(){
        $data = input('post.');
        $res = Db::name('banner')->insert($data);
        
        return $res;
    }
    /**
     * 字段：
     * id 编号
     * contentTitle 内容标题
     * head_imgurl 封面
     * privile 权限
     * occumbo 是否置顶（0/1）
     * content 内容
     */
    public function createCol(){
        $data = input('post.');
        //return json_encode($data);
        $intoRes = Db::name('column')->insert($data);//1.新建栏目加入栏目表
        //2.创建新栏目内容表
        $newColname = $data['columnName'];
        $sql = "CREATE TABLE $newColname
        (
                id int(255) primary key NOT NULL AUTO_INCREMENT,
                attrContent varchar(255),
                contentTitle varchar(255),
                head_imgurl varchar(255),
                privile tinyint(6),
                occumbo tinyint(2),
                content longtext,
                readcount int(255),
                create_time datetime
        );";
        try{
            Db::query($sql);
        }catch(DbException $e){
            return json_encode($e->getMessage());
        }
        if($intoRes)
            return 101;//操作成功
        return 201;//栏目表插入失败
    }


    public function publishContent(){//发布内容
        $data = input('post.');
        $res = Db::name($data['attrContent'])->insert($data);
        return json_encode($res);
    }


    public function getColList(){
        $data = input('post.');
        $res = Db::table($data['columnName'])->select();
        $resu = Db::table($data['columnName'])->where('occumbo',1)->count();
        
        return json_encode(['data'=>$res,'oc_count'=>$resu]);
    }


    public function addAdmin(){//管理员和用户的添加
        $classify = input('server.')['HTTP_TYPE'];
        $data = input('post.');
        $data['passWord']=md5($data['passWord']);
        $res=Db::table($classify)->insert($data);
        return $res;
    }
    /**
     * input获取字段:
     * classify  数据表名
     * id 表内字段
     */
    public function getUserinfo(){//获取管理员或用户信息(修改用)
        $data = input('post.');
        $res = Db::table($data['classify'])->where('id',$data['id'])->select();
        return json_encode($res);
    }


    public function editUser(){//修改管理员信息或用户信息
        $classify = input('server.')['HTTP_TYPE'];
        $data = input('post.');
        $data['passWord'] = md5($data['passWord']);
        $res = Db::table($classify)->save($data);
        return $res;
    }


    public function addfrienlink(){
        $data = input('post.');
        $res = Db::table('friendlinks')->insert($data);
        return $res;
    }


    public function del(){//毁灭永远比建造轻松的多，你说呢？
        $data = input('put.');
        $res = Db::table($data['classify'])->delete($data['id']);
        return $res;
    }


    public function delCol(){//删除栏目
        $data = input('put.');
        $colName = Db::table('column')->where('id',$data['id'])->value('columnName');
        $sql ="DROP TABLE $colName";
        try{
            Db::query($sql);
        }catch(DbException $e){
            return json_encode($e->getMessage());
        }
        $colRes = Db::table('column')->delete($data['id']);
        return $colRes;
    }


    public function ucpaas(){//云之讯短信接入
        $data = input('post.');
        $res = Db::name('ucpaas')->where('id',1)->update($data);
        if($res)
            return 1;
        return 0;
    }


    public function getucpaas(){//获取短信接入配置信息
        $res = Db::table('ucpaas')->where('id',1)->find();
        return json_encode($res);
    }


    public function editContent(){//修改发布内容
        $data = input('post.');
        $res = Db::table($data['attrContent'])->save($data);
        return json_encode($data);
    }


    public function searchContent(){//包含字段的模糊查询
        $data = input('post.');//
        $word = $data['word'];
        $res = Db::table($data['classify'])->where('contentTitle','like','%'.$word.'%')->whereOr('content','like','%'.$word.'%')->select();
        return json_encode($res);
    }


    public function clear() {//清除缓存
        $clearCache = Clear::delDir(LOG_PATH);
        $clearTemp = Clear::delDir(TEMP_PATH);
        return json_encode(['log'=>$clearCache,'temp'=>$clearTemp]);
        return json_encode(['log'=>TEMP_PATH]);
        if ($clearCache || $clearTemp) 
          return 1;
        return 0; 
    }


    public function quit(){
        session(null);
        return 1;
    }
}