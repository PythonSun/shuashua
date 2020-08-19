<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 12:22
 */

namespace app\admin\controller;
use think\Cookie;

class Shopper extends Base
{
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('��ѡ��Ҫɾ���ļ�¼','','error');
            }
            $status = \app\admin\model\Shopper::deleteByIds($ids);
            if(!$status){
                message('ɾ��ʧ��','','error');
            }
            message('ɾ���ɹ�','reload','success');
        }
        
        $params = request()->get();
        $list = \app\admin\model\Shopper::getList($params);

        $itemsCallback = function ($item, $key) {
            $status = ['disable','normal','apply','deny'];
            //zzg ���Ӣ�����룬�����
            $item['status'] = $status[$item['status']];
            
            return $item;
        };

        $list->each($itemsCallback);


        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => \app\admin\model\Shopper::getTotal(),
            'credit2Total' => \app\admin\model\Shopper::getCredit2Total()
        ]);
    }

    public function post()
    {
        if (request()->isAjax())
        {
        	$params = request()->post();
            if (check_array($params) && isset($params['act']) && isset($params['username']))
            {
            	$acts = ['pass'=>1,'enable'=>1,'deny'=>3,'disable'=>0];
                $status = $acts[$params['act']];
                $ret = \app\admin\model\Shopper::updateStatusByUsername($params['username'], $status);
                if (!$ret)
                {
                    message('����ʧ��','','error');
                }
                message('�����ɹ�','reload','success');
            }  
        }
    }
}