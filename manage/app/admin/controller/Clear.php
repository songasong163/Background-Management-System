<?php
namespace app\admin\controller;
use app\admin\controller\LoginControl;
class Clear extends LoginControl{

    //删除缓存文件
    public static function delDir($dirName) {
        $dh = opendir($dirName);
        //循环读取文件
        while ($file = readdir($dh)) {
            if($file != '.' && $file != '..') {
                $fullpath = $dirName . '/' . $file;
                //判断是否为目录
                if(!is_dir($fullpath)) {
                    //如果不是,删除该文件
                    if(!unlink($fullpath)) {
                        echo $fullpath . '无法删除,可能是没有权限!<br>';
                        return 0;
                    }
                    return 1;
                } else {
                    //如果是目录,递归本身删除下级目录
                    self::delDir($fullpath);
                }
            }
        }
        //关闭目录
        closedir($dh);
        //删除目录
        //if(!rmdir($dirName)) {
        //     R('Public/errjson',array($dirName.'__目录删除失败'));
        //}
    }
}