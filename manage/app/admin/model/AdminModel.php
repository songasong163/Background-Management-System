<?php
namespace app\admin\model;
use think\Model;
class AdminModel extends Model{
    protected  $connection = 'mysql';
    protected $table='systeminfo';
}