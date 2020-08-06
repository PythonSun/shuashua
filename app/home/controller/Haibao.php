<?php
//decode by http://www.xydai.cn/
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:34
 */

namespace app\home\controller;
use think\Db;
class Haibao extends Base{

	public function __construct(){
		parent::__construct();

		$this->member = $this->checkLogin();
	}

    public function index(){
       
        $money = \app\admin\model\InvitationRebateRecord::getTotalMoney($this->member['uid']);
        $member_check = Db::name("member")->where("uid = {$this->member['uid']}")->find();
        if($member_check['img'] == ''||empty($member_check['img'])){
            $yuming = "http://".$_SERVER['SERVER_NAME'];
            $url = $yuming."/home/auth/register.html?i=".$this->member['uid'];
            $imgs = $this->qrcode($url);
            $datt['img'] = $imgs;
           $res = Db::name("member")->where("uid = {$this->member['uid']}")->update($datt);
        }else{
            $imgs = $member_check['img'];
        }
        
        
        return $this->fetch(__FUNCTION__, [
            'money' => $money,
            'domain' => $this->get_domain(),
            'img' => $imgs
        ]);
    }

    ////二维码生成
    function qrcode($url,$level=3,$size=4){
                $PNG_TEMP_DIR = "./img/";  
              Vendor('phpqrcode.phpqrcode');
              $errorCorrectionLevel =intval($level) ;//容错级别 
              $matrixPointSize = intval($size);//生成图片大小 
            //生成二维码图片 
              $filename = $PNG_TEMP_DIR."tp".time().rand(1,100).".png";

              //echo $_SERVER['REQUEST_URI'];
              $object = new \QRcode();
              ob_end_clean();
              $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);   
            
              return basename($filename);
 }

    public function silver(){
        return $this->fetch(__FUNCTION__);
    }

    public function silver_ajax() {
    //  var_dump($_REQUEST['page']);
      if($_REQUEST['page'] > 1){
        die;
      }
       // $where = ['uid' => $this->member['uid']];
       // $list = \app\admin\model\Invitation::getPagination($where, 15, NULL, "id DESC");
      //一级
      $list = array();
       $onelist = Db::name("member")->where("parent_uid = {$this->member['uid']}")->select();
       if(is_array($onelist)){

            foreach ($onelist as $key => $value) {

                    $value['level_title'] = "一级";
                    $list[] = $value;
                   if($value['parent_uid'] > 0){
                      $twolist = Db::name("member")->where("parent_uid = {$value['uid']}")->select();
                        foreach ($twolist as $k => $v) {
                          $v['level_title'] = "二级";
                          $list[] = $v;
                            if($v['parent_uid'] > 0){
                              $threelist = Db::name("member")->where("parent_uid = {$v['uid']}")->select();
                              foreach ($threelist as $ke => $va) {
                                  $va['level_title'] = "三级";
                                  $list[] = $va;
                              }
                            }
                        }
                   }
               }
       }
       

        to_json(0, '', $list);
    }

    public function gold(){
        return $this->fetch(__FUNCTION__);
    }

    public function gold_ajax() {
        $where = ['uid' => $this->member['uid']];
        $list = \app\admin\model\InvitationRebateRecord::getPagination($where, 15, NULL, "id DESC");

        to_json(0, '', $list);
    }
}