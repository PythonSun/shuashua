<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 12:22
 */

namespace app\admin\controller;
use app\admin\model\Administrator;
use app\admin\model\Shopper;
use think\Cookie;
use think\Db;

class Auth extends Base
{

    /**
     * @return mixed
     * 登录操作
     */
    public function login(){
        if(request()->isAjax()){
            $params = request()->post();
            $result = $this->validate($params,'Administrator.login');
            if($result !== true){
                message($result,'','error');
            }
            if ($params['login_type'] == 0)
            {
                $administrator = Administrator::getInfoByUsername($params['username']);
                if(empty($administrator)){
                    message('管理员信息不存在','','error');
                }
                if(!md5_password_check($params['password'],$administrator['password'],$administrator['salt'])){
                    message('密码输入错误','','error');
                }
                Cookie::set('administrator',base64_encode($administrator));                
                cache('DB_TREE_MENU_' . $administrator['id'], NULL);
                message('登录成功', U('index/index'), 'success');
            }
            else//zzg
            {
                $shopper = Shopper::getInfoByUsername($params['username']);
                if(empty($shopper)){
                    message('商户信息不存在','','error');
                }
                if ($shopper['status'] == 0)
                {
                	message('该商户已被禁用','','error');
                }                
                if(!md5_password_check($params['password'],$shopper['password'],$shopper['salt'])){
                    message('密码输入错误','','error');
                }
                $shopper['is_shopper'] = true;
                Cookie::set('administrator',base64_encode($shopper));                
                cache('DB_TREE_MENU_' . $shopper['id'], NULL);
                message('登录成功', U('index/index'), 'success');
            }
        }
        Cookie::delete('administrator');
        return $this->fetch(__FUNCTION__);
    }

    /**
     * @return mixed
     * 注册操作
     */
    public function register()
    {
        if(request()->isAjax())//注册
        {
            $params = request()->post();
            if (!check_username($params['username']))
            {
            	message('用户名格式不正确','','error');
            }

            if (!check_mobile($params['mobile']))
            {
            	message('联系电话格式不正确','','error');
            }

            if (!check_nickname($params['shopper_name']))
            {
            	message('商户名格式','','error');
            }

            if (!check_email($params['email']))
            {
            	message('邮箱地址格式不正确','','error');
            }
            
            //查询当前用户名是否被占用
            $shopper = Db::table('tb_shopper')
            ->where('username','=',$params['username'])
            ->find();

            if (!empty($shopper))
            {
            	if ($shopper['status'] == 2)
                {
                    message('当前用户名正在审核，无法再次申请','','error');
                }
                else if ($shopper['status'] == 1 || $shopper['status'] == 0)
                {
                    message('当前用户名已被使用','','error');
                }
            }

            $salt = random(8);
            $params['password'] = md5_password($params['password'],$salt);
            $insert_member_id = Shopper::addInfo([
                'username'=>$params['username'],
                'status' => 2,
                'mobile'=> $params['mobile'],
                'shopper_name'=> $params['shopper_name'],
                'email'=> $params['email'],
                'salt' => $salt,
                'password'=> $params['password'],
                ]);
            if(!$insert_member_id){
                message('申请失败','','error');
            }

            message('申请成功，请等待审核通过','/admin/auth/login.html','success');
        }
        
        return $this->fetch(__FUNCTION__);
    }
}