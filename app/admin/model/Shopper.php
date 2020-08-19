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
            return $result > 0;
        }
        catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    public static function updateStatusByUsername($username = '', $status){
        if(!check_username($username)){
            return null;
        }
        try{
            return self::update(['status'=> $status, 'update_time'=>TIMESTAMP],['username' =>['=', $username]]);
        }
        catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    public static function getTotal(){
        try{
            return self::count();
        }
        catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    public static function getCredit2Total(){
        try{
            return self::sum("credit2");
        }
        catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    public static function getList($params = []){
        try{
            $where = [];
            //if(check_array($params)){
            //    if(!empty($params['keyword'])){
            //        $where['uid|username|mobile'] = ['like',"%{$params['keyword']}%"];
            //    }
            //    if(!empty($params['is_check'])){
            //        $where['is_check'] = ['in',$params['is_check']];
            //    }
            //}
            $psize = parent::paginateSize(15);
            return self::where($where)->order('update_time desc')->paginate($psize, false, parent::paginateParam());
        }
        catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}