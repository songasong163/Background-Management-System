<?php
namespace app\admin\model;
use think\Model;
class LoginModel extends Model{
    protected  $connection = 'mysql';
    protected $table='adminuser';
}