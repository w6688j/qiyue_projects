<?php
/**
 * For: 新老用户回馈活动
 * User: caostian
 * Date: 2017/10/18
 * Time: 15:51
 */

namespace app\module\activity\controller;

use app\module\activity\service\GiftService;
use app\service\VCUserLoginService;
use atphp\Controller;
use atphp\Request;
use atphp\util\SessionUtil;



class GiftController extends Controller
{

    public $user_id; //用户ID,PK


    //检测用户有没有登录
    public function __construct()
    {
        //线上用这===上来就检出用户的合法性
        $return = VCUserLoginService::checkUser();
        if ($return["status"] == false) {
            $this->errorJson($return);
        }
        $this->user_id = SessionUtil::get("user_id");

        //测试---
        //echo "测试用户:121457";
//        $this->user_id = 121457;
        //获取配置信息
        GiftService::getConfig();
    }

    public function index()
    {
        //初始化奖励记录
        GiftService::oldPlayer($this->user_id);
        GiftService::newPlayer($this->user_id);
        //dump(GiftService::$config);
        $old_time_flag = GiftService::checkTime(GiftService::TYPE_OldPlayer);//在活动范围,true ,否则false
        $new_time_flag = GiftService::checkTime(GiftService::TYPE_NewPlayer);//在活动范围,true ,否则false

        $this->assign([
            "config" => GiftService::$config,
            "old_time_flag" => $old_time_flag,
            "new_time_flag" => $new_time_flag,
            "time"=>time(),
            "old_start_time"=>date("m.d",GiftService::$config["old_start_time"]),
            "old_end_time"=>date("m.d",GiftService::$config["old_end_time"]),
            "new_start_time"=>date("m.d",GiftService::$config["new_start_time"]),
            "new_end_time"=>date("m.d",GiftService::$config["new_end_time"]),
        ]);
        $this->display("Gift/index");
    }

    public function getGrade()
    {
        $grade_type = Request::getInteger("grade_type");
        $grade_level = Request::getInteger("grade_level");

        $data["info"] = "领取失败";

        //判断是否在时间范围内
        if (!GiftService::checkTime($grade_type)) {
            $data["info"] = "不在活动时间范围内";
            $this->errorJson($data);
        }
        $result = false;


        switch ($grade_type) {
            case GiftService::TYPE_OldPlayer:
                //老用户领取奖励
                $result = GiftService::getOldPlayerGrade($this->user_id, $grade_level);
                break;
            case GiftService::TYPE_NewPlayer:
                //新用户领取奖励
                $result_data = GiftService::getNewPlayerGrade($this->user_id);
                if ($result_data["status"]) {
                    $this->successJson($result_data);
                }
                break;
            default:
                $data["info"] = "类型不匹配";
                $this->errorJson($data);
        }


        if ($result) {
            $data["info"] = "领取成功";
            $this->successJson($data);
        }
        $this->errorJson($data);


    }


}