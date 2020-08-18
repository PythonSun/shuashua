<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/23
 * Time: 21:33
 */

namespace app\admin\controller;

use app\admin\model\Config;
use app\home\model\CreditRecord;
use app\home\model\Member;
use app\admin\model\TaskCategory;
use app\home\model\Area;
use think\Db;
use think\Log;
use think\Loader;
use think\Cookie;

class Task extends Base{

    //任务列表
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的任务','','error');
            }
            $status = \app\admin\model\Task::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message('删除成功','reload','success');
        }

        $params = request()->get();
        $where = [];
        if(check_array($params)){
            if(!empty($params['keyword'])){
                $where['id|title'] = ['like', "%{$params['keyword']}%"];
            }
            if(!empty($params['is_display'])){
                $where['is_display'] = ['in',$params['is_display']];
            }
        }

        //zzg
        $administrator = base64_decode(Cookie::get('administrator'));
        if ($administrator == null)
        {
        	message('登录超时', U('auth/login'), 'error');
        }
        if (isset($administrator['is_shopper']))
        {            
        	$where['uid'] = $administrator['id'];
        }
        //zzg
        
        $total = \app\admin\model\Task::getCount($where);
        $list = \app\admin\model\Task::getPagination($where, 15, $total, "update_time DESC");

        $categorys = [];
        $categories = \app\admin\model\TaskCategory::getList();
        foreach ($categories as $key => $value) {
            $categorys[$value['id']] = $value['title'];
        }

        $GLOBALS['categorys'] = $categorys;

        $itemsCallback = function ($item, $key) {
            $item['category_type'] = "";

            if ($item['end_time'] < TIMESTAMP) {
                $item['category_type'] = "past";
            }  else if ($item['is_complete'] == 1) {
                $item['category_type'] = "pass";
            }   else if ($item['is_display'] == 0) {
                $item['category_type'] = "wait";
            } else if ($item['start_time'] < TIMESTAMP && $item['end_time'] > TIMESTAMP) {
                $item['category_type'] = "ing";
            }

            $item['start_time'] = date('Y-m-d H:i:s', $item['start_time']);
            $item['end_time'] = date('Y-m-d H:i:s', $item['end_time']);
            $item['complete_time'] = date('Y-m-d H:i:s', $item['complete_time']);
            $item['category'] = isset($GLOBALS['categorys'][$item['category_id']]) ? $GLOBALS['categorys'][$item['category_id']] : '';
            return $item;
        };

        $list->each($itemsCallback);

        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => $total
        ]);
    }

    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $categorys = [];
            $categories = \app\admin\model\TaskCategory::getList();
            foreach ($categories as $key => $value) {
                $categorys[$value['id']] = $value['title'];
            }

            $item = \app\admin\model\Task::getInfoById($id);
            if(!empty($item['thumbs'])){
                $item['thumbs'] = unserialize($item['thumbs']);
            }

            $item['fee_money'] = $item['give_credit2'] * $item['fee'];

            $operate_steps = \app\home\model\Task::getOperateStepsById($id);

            $member = \app\admin\model\Member::getInfoById($item['uid']);
            $item['username'] = $member['username'];

            $item['category_type'] = "";

            if ($item['end_time'] < TIMESTAMP) {
                $item['category_type'] = "past";
            } else if ($item['is_complete'] == 1) {
                $item['category_type'] = "complete";
            } else if ($item['is_display'] == 0) {
                $item['category_type'] = "wait";
            } else if ($item['is_display'] == -1) {
                $item['category_type'] = "nopass";
            } else if ($item['start_time'] < TIMESTAMP && $item['end_time'] > TIMESTAMP) {
                $item['category_type'] = "ing";
            }

            $item['start_time'] = date('Y-m-d H:i:s', $item['start_time']);
            $item['end_time'] = date('Y-m-d H:i:s', $item['end_time']);
            $item['complete_time'] = date('Y-m-d H:i:s', $item['complete_time']);
            $item['category'] = isset($categorys[$item['category_id']]) ? $categorys[$item['category_id']] : '';
        }
        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'operate_steps' => $operate_steps,
            'origin' => $item->getData()
        ]);
    }

    public function add(){

        $setting = ['push_check' => 0];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['period'])){
                $setting['period'] = explode('#',$setting['period']);
            }
            if(!empty($setting['fee'])){
                $setting['fee'] = round(floatval($setting['fee']*0.01),2);
            }
            if(!empty($setting['push_check'])){
                $setting['push_check'] = intval($setting['push_check']);
            }
        }
    
        $member = (array)json_decode(base64_decode(Cookie::get('administrator')));

        //如果是ajax请求，处理发布
        if(request()->isAjax()){
            if(!check_array($setting)){
                message('平台未进行相关设置','','error');
            }
            $params = array_trim(request()->post());
            $result = $this->validate($params,'Task');
            if($result !== true){
                message($result,'','error');
            }
            $params['fee'] = !empty($setting['fee'])?$setting['fee']:0;

            //处理是否的值
            param_is_or_no(['is_screenshot','is_ip_restriction','is_limit_speed'],$params);
            //处理两位小数的值
            params_round(['unit_price','give_credit2'],$params,2);
            //处理是整数的
            params_floor(['check_period','rate','interval_hour','limit_ticket_num'],$params);

            //$params['give_credit1'] = intval($params['give_credit1']);//zzg暂时屏蔽
            $params['ticket_num'] = intval($params['ticket_num']);
            $params['give_credit2'] = $params['unit_price'] * $params['ticket_num'];
            $params['amount'] = $params['give_credit2'] * (1 + $params['fee']);

            //判断余额或者积分是足够
            if($params['amount'] > $member['credit2']){
                message('账户余额不足','','error');
            }

            //zzg 不需要积分了
            /*if($params['give_credit1'] > $member['credit1']){
                message('积分不足','','error');
            }*/

            $task_operate_steps_contents = $params['process_sm'];
            unset($params['process_sm']);
            unset($params['processFile']);

            if (empty($task_operate_steps_contents)) {
                message('您未写操作说明!','','error');
            }
            foreach ($task_operate_steps_contents as $key => $value) {
                if (empty(trim($value))) {
                    message('您未写操作说明!','','error');
                }
            }

            // 获取表单上传文件
            $thumbs = [];
            $files = request()->file('thumbs');
            if(check_array($files)){
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if(!$info){
                        message($file->getError(),'','error');
                    }

                    $data = array(
                        'state' => 'SUCCESS',
                        'url' => 'public' . DS . 'uploads' . DS . $info->getSaveName(),
                        'title' => $info->getFilename(),
                        'original' => $info->getFilename(),
                        'type' => '.' . $info->getExtension(),
                        'size' => $info->getSize(),
                    );

                    $imgresource = ROOT_PATH . $data['url'];

                    $image = \think\Image::open($imgresource);

                    $water = [
                        'is_mark' => 1,
                        'mark_type' => 'text',
                        'mark_txt' => '样图请勿上传',
                        'mark_img' => '',
                        'mark_width' => 0,
                        'mark_height' => 0,
                        'mark_degree' => ''
                    ];
                    if($water['is_mark']==1 && $image->width() > $water['mark_width'] && $image->height() > $water['mark_height']) {
                        if($water['mark_type'] == 'text'){
                            $image->text($water['mark_txt'], './hgzb.ttf', 50, '#ff0000', 9)->save($imgresource);
                        }else{
                            $image->water(".".$water['mark_img'], 9, $water['mark_degree'])->save($imgresource);
                        }
                    }

                    $record = [
                        'uid' => $this->member['uid'],
                        'extension' => $info->getExtension(),
                        'save_name' => str_replace('\\','/',$info->getSaveName()),
                        'filename' => $info->getFilename(),
                        'md5' => $info->hash('md5'),
                        'sha1' => $info->hash('sha1'),
                        'size' => $info->getSize(),
                        'create_time' => TIMESTAMP
                    ];
                    //记录文件信息
                    array_push($thumbs,$record['save_name']);
                    //数据库存入失败记录日志
                    if(!Uploads::addInfo($record)){
                        Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
                    }
                }
            }
            $params['thumbs'] = check_array($thumbs)?serialize($thumbs):'';
            $params['start_time'] = strtotime($params['start_time']);
            $params['end_time'] = strtotime($params['end_time']);
            $params['check_period_time'] = intval($params['check_period']) * 3600 + TIMESTAMP;
            $params['uid'] = $this->member['uid'];
            $params['update_time'] = TIMESTAMP;

            // 上传操作说明配图
            $task_operate_steps_images = [];
            $task_operate_steps_files = request()->file('processFile');
            if(check_array($task_operate_steps_files)){
                foreach($task_operate_steps_files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if(!$info){
                        message($file->getError(),'','error');
                    }
                    $record = [
                        'uid' => $this->member['uid'],
                        'extension' => $info->getExtension(),
                        'save_name' => str_replace('\\','/',$info->getSaveName()),
                        'filename' => $info->getFilename(),
                        'md5' => $info->hash('md5'),
                        'sha1' => $info->hash('sha1'),
                        'size' => $info->getSize(),
                        'create_time' => TIMESTAMP
                    ];
                    //记录文件信息
                    array_push($task_operate_steps_images, $record['save_name']);
                    //数据库存入失败记录日志
                    if(!Uploads::addInfo($record)){
                        Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
                    }
                }
            }

            //读取后台任务是否需要审核配置
            $params['is_display'] = $setting['push_check'] ? 0 : 1;

            Db::startTrans();
            $insert_task_id = \app\home\model\Task::addInfo($params);
            if(!$insert_task_id){
                Db::rollback();
                message('发布失败:-1','','error');
            }

            foreach ($task_operate_steps_contents as $key => $value) {
                $task_operate_steps_params = array(
                    'task_id' => $insert_task_id,
                    'uid' => $this->member['uid'],
                    'content' => $value,
                    'image' => isset($task_operate_steps_images[$key]) ? $task_operate_steps_images[$key] : '',
                    'sort' => $key,
                );

                $insert_task_operate_step_id = \app\home\model\TaskOperateSteps::addInfo($task_operate_steps_params);
                if(!$insert_task_operate_step_id){
                    Db::rollback();
                    message('发布失败:-2','','error');
                }
            }

            if($params['give_credit1']>0 || $params['amount']>0){
                $status1 = Member::updateCreditById($member['uid'], -$params['give_credit1'], -$params['amount']);
                if(!$status1){
                    Db::rollback();
                    message('发布失败:-3','','error');
                }
                //分别记录积分和余额记录
                if($params['give_credit1']>0){
                    $status2 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit1',
                        'num' => -$params['give_credit1'],
                        'title' => '发布任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "发布成功，扣除{$params['give_credit1']}积分。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status2){
                        Db::rollback();
                        message('发布失败:-4','','error');
                    }
                }
                if($params['amount']>0){
                    $status3 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit2',
                        'num' => -$params['amount'],
                        'title' => '发布任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "发布成功，扣除{$params['amount']}余额。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status3){
                        Db::rollback();
                        message('发布失败:-5','','error');
                    }
                }
            }
            Db::commit();
            message('发布成功','/home/mytask.html','success');
        }
        
        if (Cookie::has('administrator'))
        {        	
            $categories = TaskCategory::getList();
            return $this->fetch(__FUNCTION__,[
                'item' => ['category_id' => 0],
                'member' => $member,
                'categories' => $categories,
                'setting' => $setting,
                'provinces' => Area::$provinces,
                'operate_steps' => null
            ]);
        }
    }

    //添加任务
    /*public function add(){
        $member = $this->checkLogin();
        $setting = ['push_check' => 0];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['period'])){
                $setting['period'] = explode('#',$setting['period']);
            }
            if(!empty($setting['fee'])){
                $setting['fee'] = round(floatval($setting['fee']*0.01),2);
            }
            if(!empty($setting['push_check'])){
                $setting['push_check'] = intval($setting['push_check']);
            }
        }

        
        $categories = TaskCategory::getList();
        return $this->fetch(__FUNCTION__,[
            'item' => ['category_id' => 0],
            'member' => $member,
            'categories' => $categories,
            'setting' => $setting,
            'provinces' => Area::$provinces,
            'operate_steps' => null
        ]);
    }*/

    public function save(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Task::getInfoById($id);
        }

        if (empty($item) || is_null($item)) {
            message("审核失败",'','error');
        }

        $origin = $item->getData();
        if ($origin['admin_id'] > 0) {
            message("审核失败",'','error');
        }

        $params = array_trim(request()->post());

        Db::startTrans();

        $update = [];
        $update['title'] = $params['title'];
        $update['is_display'] = intval($params['is_display']);
        $update['audit_remarks'] = $params['audit_remarks'];
        $update['admin_id'] = $this->administrator['id'];
        $update['update_time'] = TIMESTAMP;
        $status = \app\admin\model\Task::updateInfoById($id, $update);
        if(!$status){
            Db::rollback();
            message("审核失败",'','error');
        }

        //审核未通过时需要退回金额给用户
        if ($update['is_display'] == -1) {
            $credit2 = $item['amount'];
            $status1 = Member::updateCreditById($item['uid'], 0, $credit2);
            if(!$status1){
                Db::rollback();
                message('审核失败：-1','','error');
            }
            $status2 = CreditRecord::addInfo([
                'uid' => $item['uid'],
                'type' => 'credit2',
                'num' => $credit2,
                'title' => '任务发布审核',
                'remark' => "任务[" . $item['id'] . "]-" . $item['title'] . "发布审核未通过，退回{$credit2}余额。",
                'create_time' => TIMESTAMP
            ]);
            if(!$status2){
                Db::rollback();
                message('审核失败：-2','','error');
            }
        }

        Db::commit();

        message("审核成功",'reload','success');
    }
}