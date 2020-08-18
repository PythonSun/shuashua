<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:46
 */
namespace app\admin\model;
use think\Exception;
use think\Log;
use think\Db;

class Shopper extends Base{

    //当前操作表
    protected $table = 'tb_shopper';

    /**
     * @param string $username
     * @return null|static
     * 根据username获取用户信息
     */
    public static function getInfoByUsername($username = ''){
        if(!check_username($username)){
            return null;
        }
        try{
            return self::get(['username' => $username]);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $id
     * @param $credit2
     * @return $this|bool|null
     * 根据ID修改余额
     */
    public static function updateCreditById($id, $credit2){
        if(!check_id($id)){
            return false;
        }
        try{
            $where = [
                'id' => $id
            ];

            if ($credit2 < 0) {
                $where['credit2'] = ['>=', abs($credit2)];
            }

            $result = Db::table('tb_shopper')
            ->where($where)
            ->inc('credit2', $credit2)
            ->exp('update_time', TIMESTAMP)
            ->update();
            //echo Db::getLastSql();exit;
            return $result > 0;
        }
        catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}