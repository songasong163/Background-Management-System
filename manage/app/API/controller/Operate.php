<?php
namespace app\API\controller;
use app\API\controller\LoginContro;
use think\facade\Db;
use ucpaas\Ucpaas;
use think\captcha\facade\Captcha;
class Operate extends LoginContro{
    public function login(){//登录控制
        $data = input('get.');
        if(captcha_check($data['verifyCode'])){
            $userRes = Db::table('user')->where('userName', $data['userName'])->find();
            if($userRes){
                $passRes = Db::table('user')->where('passWord', md5($data['passWord']))->find();
                if($passRes){
                    session('username', $data['userName']);
                    //$token = create_token();*******************
                    return json_encode(['code'=>200,'msg'=>'欢迎登录！']);
                }
            }
            return json_encode(['code'=>404,'msg'=>'没有该用户！']);    
        }
        return json_encode(['code'=>401,'msg'=>'验证码有误！']);
    }


    public function register(){//注册控制
        $data = input('post.');
        return json_encode($data);
        $data['passWord']=md5($data['passWord']);
        $verify = input('server.')['HTTP_VERIFY'];
        $checkVerify = session('sms_verify')==$verify;//验证短信验证码******************
        if($checkVerify){
            $res = Db::name('user')->save($data);
            if($res){
                return json_encode(['code'=>200,'msg'=>'注册成功！']);
            }
            return json_encode(['code'=>403,'msg'=>'用户名已被使用！']);
        }
        return json_encode(['code'=>401,'msg'=>'短信验证码错误！']);
    }


    public function resetPwd(){//重置密码
        $data = input('post.');
        $data['newPwd'] = md5($data['newPwd']);
        $checkVerify = ($data['verifyCode']==session('sms_verify'));//验证短信验证码*******************
        if($checkVerify){
            $res = Db::name('user')->where('userName',$data['userName'])->updata(['passWord'=>$data['newPwd']]);
            if($res){
                return json_encode(['code'=>200,'msg'=>'密码重置成功！']);
            }
            return json_encode(['code'=>403,'msg'=>'用户名填写错误！']);
        }
        return json_encode(['code'=>401,'msg'=>'短信验证码错误！']);
    }


    public function getUsualinfo(){//获取个人初始资料
        $data = input('get.');
        $res = Db::table('user')->where('userName',$data['userName'])->find();
        if($res)
            return json_encode([
                'code'=>200,
                'msg'=>'访问正常',
                'data'=>[
                    'userName'=>$res['userName'],
                    'phone'=>$res['phone'],
                    'member'=>$res['member'],
                    'integralis'=>$res['integralis']
                ]
            ]);

        return json_encode(['code'=>404,'msg'=>'没有该用户信息！']);
    }


    public function editUsualinfo(){//提交个人已修改资料
        $data = input('post.');
        $res = Db::name('user')->where('userName', $data['userName'])->update(['phone' => $data['phone']]);
        if($res)
            return json_encode(['code'=>200,'msg'=>'修改成功！']);
        return json_encode(['code'=>404,'msg'=>'目标用户不存在！']);
    }


    public function upMember(){//升级为会员
        $data = input('post.');
        return json_encode('该功能暂未开放！');
    }


    public function getBanner(){//获取轮播图
        $res = Db::table('banner')->select();
        if(!empty($res))
            return json_encode(['code'=>200,'msg'=>'获取成功！','data'=>$res]);
        return json_encode(['code'=>403,'msg'=>'数据库繁忙！']);
    }


    public function getColumn(){//获取栏目信息
        $res = Db::table('column')->where('isShow',1)->select();
        if(!empty($res))
            return json_encode(['code'=>200,'msg'=>'获取成功！','data'=>$res]);
        return json_encode(['code'=>403,'msg'=>'数据库繁忙！']);
    }


    public function getColcontent(){//获取对应栏目内容
        $data = input('get.');
        $res = Db::table($data['columnName'])->select();
        if(!empty($res))
            return json_encode(['code'=>200,'msg'=>'获取成功！','data'=>$res]);
        return json_encode(['code'=>403,'msg'=>'数据库繁忙！']);
    }


    public function getFriendlinks(){//获取友情链接
        $res = Db::table('friendlinks')->select();
        if(!empty($res))
            return json_encode(['code'=>200,'msg'=>'获取成功！','data'=>$res]);
        return json_encode(['code'=>403,'msg'=>'数据库繁忙！']);
    }


    public function putRelin(){//发布留言
        $data = input('put.');
        if(empty($data))
            return json_encode(['code'=>400,'msg'=>'请检查内容']);
        $res = Db::table('relinquere')->insert($data);
        if($res)
            return json_encode(['code'=>200,'msg'=>'发布成功！']);
        return json_encode(['code'=>403,'msg'=>'数据库繁忙！']);
    }


    public function verify(){//获取图形验证码
        return Captcha::create();
    }

    public function sms_verify(){//获取短信验证码
        $data = input('put.');
        @$mobile = $data['phone'];
        if(empty($data))
            return json_encode(['code'=>400,'msg'=>'参数缺失']);
        $module = Db::table('ucpaas')->where('id',1)->find();
        $options['accountsid']=$module['sid'];//用户的账号唯一标识“Account Sid”，在开发者控制台获取
        $options['token']=$module['token'];//填写在开发者控制台首页上的Auth Token
        $appid = $module['appid'];	//应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = $module['templateid'];    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $param = rand(1000,9999); //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $uid = "";//选填;用户透传ID，随状态报告返回
        
        $ucpass = new Ucpaas($options);//初始化 $options必填       
        $sendRes = $ucpass->SendSms($appid,$templateid,$param,$mobile,$uid);
        $res=json_decode($sendRes,true);
        return json($res);
        if($res['msg'] == 'OK'){
            Session('sms_verify',$param);
            return json_encode(['code'=>200,'msg'=>'短信已成功发送，请注意查收！']);
        }
        return json_encode(['code'=>403,'msg'=>'短信发送失败，请重新发送！']); 
    }
}