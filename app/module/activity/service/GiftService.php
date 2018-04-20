<?php

/**
 * For: .....
 * User: caostian
 * Date: 2017/10/18
 * Time: 9:36
 */

namespace app\module\activity\service;

use app\model\mysql\ActivityGiftGradeModel;
use app\model\oracle\LogLoginOracle;
use app\model\oracle\PayDetailOracle;
use app\model\oracle\UserDetailOracle;
use app\model\oracle\UserMoneyOrale;
use app\model\socket\MsgSocket;
use atphp\db\Model;
use atphp\exception\ExceptionExt;
use atphp\util\DateUtil;
use atphp\util\LogUtil;
use atphp\util\SessionUtil;

class GiftService
{
    public static $config = [];

    /**未达到**/
    const GIFT_NO_TOUCH = 0;//未达到
    /***可领取***/
    const GIFT_GETING = 1;//可领取
    /***已领取***/
    const GIFT_GETED = 2; //已领取
    /***不在时间范围内***/
    const GIFT_INVALID = 3;//3


    //新/老用户类型 1老用户,2新用户
    const TYPE_OldPlayer = 1;
    const TYPE_NewPlayer = 2;


    //删除记录
    public static function deleteGiftRecord($grade_id)
    {
        $activityGift = new ActivityGiftGradeModel();
        $ret = $activityGift->where(["grade_id" => $grade_id])->delete();
        if (!$ret) {
            self::logWrite("删除grade_id : {$grade_id} 失败!");
        }
    }

    public static function logWrite($msg)
    {
        LogUtil::write($msg, LogUtil::INFO, LogUtil::FILE, 'activity/' . DateUtil::format(time(), "Ymd") . "/info.log");
    }


    //给用户加VIP 和 VIP天数
    public static function addVip($user_id, $vip_level, $vip_days)
    {
        $msg_model = new MsgSocket();
        $ret = $msg_model->vipChange($user_id, $vip_level, $vip_days * 86400);
        $msg_model->close();
        return $ret;
    }


    //查询老用户是否可以领取奖励
    public static function oldPlayer($user_id)
    {
        //查询累计充值金额
        $pay_detail_Model = new PayDetailOracle();
        $total_money = $pay_detail_Model->p_t_paydetail_stat($user_id, '', -1, -1, 0, self::$config['old_start_time'], '', 4, -1)["v_money_total"];
        $gift_grade_model = new ActivityGiftGradeModel();
        foreach (self::$config["old_player"] as $key => $value) {
            if ($value["money"] <= $total_money) {
                $get_info = $gift_grade_model->where(array("grade_type" => self::TYPE_OldPlayer, "grade_level" => ($key + 1), "user_id" => $user_id))->field("grade_id")->find();

                //判断用户是否已经领取了这个奖励 grade_type, grade_level, user_id

                if ($get_info) {
                    self::$config["old_player"][$key]["is_get"] = self::GIFT_GETED;
                } else {
                    self::$config["old_player"][$key]["is_get"] = self::GIFT_GETING;
                }
            } else {
                self::$config["old_player"][$key]["is_get"] = self::GIFT_NO_TOUCH;
            }

            //测试用的
            //self::$config["old_player"][$key]["is_get"] = 1;
        }
    }


    //查询新用户是否可以领取奖励
    public static function newPlayer($user_id)
    {

        self::$config["new_player"]["user_login_num"] = 0;

        //判断是否结束
        if (!self::checkTime(self::TYPE_NewPlayer)) {
            self::$config["new_player"]["is_get"] = self::GIFT_INVALID;
            return false;
        }

        $new_player = self::$config["new_player"];

        $log_login_model = new LogLoginOracle();
        $gift_grade_model = (new Model())->table("cb_activity_gift_grade");

        //这里要查询是不是新用户---在活动之后注册的用户
        $userDetailOracle = new UserDetailOracle();
        $user_info = $userDetailOracle->p_user_detail_list1($user_id);
        if (empty($user_info)) {
            self::$config["new_player"]["is_get"] = self::GIFT_NO_TOUCH;
            return false;
        }
        $user_info = $user_info[0];
        if (!($user_info["regtime"] >= self::$config["new_start_time"] && $user_info["regtime"] <= self::$config["new_end_time"])) {
            self::$config["new_player"]["is_get"] = self::GIFT_NO_TOUCH;
            return false;
        }
        $user_login_num = 0;//能进入这里,表示今天肯定登录了

        for ($i = 0; $i < $new_player["login_day"]; $i++) {
            $deal_start_time = mktime(0, 0, 0, date("m"), date("d") - $i, date("Y"));
            $deal_end_time = $deal_start_time + 86400 - 1;

            $login_num = $log_login_model->p_log_login_stat($user_id, 0, $deal_start_time, $deal_end_time, 0, 0, -1, 4)["use_count"];

            if ($login_num <= 0) {
                break;
            }
            $user_login_num++;
        }

        self::$config["new_player"]["user_login_num"] = $user_login_num;

        if ($new_player["login_day"] <= $user_login_num) {
            //这里说明可以领取奖励
            //首先判断用户是否已经领取了奖励
            $get_info = $gift_grade_model->where(array("grade_type" => self::TYPE_NewPlayer, "user_id" => $user_id))->field("grade_id")->find();
            if ($get_info) {
                self::$config["new_player"]["is_get"] = self::GIFT_GETED;
            } else {
                self::$config["new_player"]["is_get"] = self::GIFT_GETING;
            }
        } else {
            self::$config["new_player"]["is_get"] = self::GIFT_NO_TOUCH;
        }

        return true;

    }

    public static function getConfig()
    {
        if (empty(self::$config)) {
            $config_model = (new Model())->table("cb_activity_gift_config");
            $config = $config_model->find();
            if (!$config) {
                return false;
            }
            $config["old_player"] = json_decode($config["old_player"], true);
            $config["new_player"] = json_decode($config["new_player"], true);
            self::$config = $config;
        }
        return self::$config;
    }

    /**
     * @param $type 1 老用户 2 新用户
     * @return bool
     */
    public static function checkTime($type)
    {
        $now_time = time();
        $start_time = $end_time = 0;

        if ($type == 1) {
            $start_time = self::$config["old_start_time"];
            $end_time = self::$config["old_end_time"];
        } else if ($type == 2) {
            $start_time = self::$config["new_start_time"];
            $end_time = self::$config["new_end_time"];
        }
        if ($start_time < $now_time && $end_time > $now_time) {
            return true;
        }
        return false;
    }


    public static function getOldPlayerGrade($user_id, $grade_level)
    {

        self::oldPlayer($user_id);

        $gift_grade = new ActivityGiftGradeModel();

        $key = $grade_level - 1;
        $old_player_item = self::$config["old_player"][$key];

        if ($old_player_item && $old_player_item["is_get"] === self::GIFT_GETING) {
            try {
                $gift_grade->begin();
                $money = $old_player_item["jinbi"];

                $data = [
                    "grade_type" => self::TYPE_OldPlayer,
                    "grade_level" => $grade_level,
                    "grade_jinbi" => $money,
                    "create_time" => time(),
                    "user_id" => $user_id,
                ];
                $grade_id = $gift_grade->insert($data);

                if ($grade_id) {
                    //请求加金币
                    if (self::addMontyAndTicket($user_id, 1000 + $key, $money, 0)) {
                        $gift_grade->commit();
                        return true; //领取奖励成功
                    }
                }
            } catch (ExceptionExt $e) {
                $gift_grade->rollBack();
            }
            $gift_grade->rollBack();
        }

        return false;


    }

    public static function getNewPlayerGrade($user_id)
    {
        self::newPlayer($user_id);
        $new_player_item = self::$config["new_player"];

        $return_data = [
            "status" => false,
            "info" => "领取失败",
        ];

        if ($new_player_item["is_get"] == self::GIFT_GETING) {

            try {

                //可领取
                $gift_grade = new ActivityGiftGradeModel();
                $gift_grade->begin();
                $money = $new_player_item["jinbi"];
                $vip = $new_player_item["vip"];
                $vip_days = $new_player_item["vip_days"];
                $qb = $new_player_item["qb"];
                $qb_rate = $new_player_item["qb_rate"];

                $data = [
                    "grade_type" => self::TYPE_NewPlayer,
                    "grade_jinbi" => $money,
                    "create_time" => time(),
                    "user_id" => $user_id,
                    "grade_vip_level" => $vip,
                    "grade_vip_days" => $vip_days,
                    "grade_qb" => 0,
                ];
                $grade_id = $gift_grade->insert($data);

                if ($grade_id) {
                    $grade_info = "金币x" . ($money / 10000) . "W , VIP{$vip}x{$vip_days}天";
                    //这里派发奖励
                    $rate = mt_rand(1, $qb_rate["range_num"]);
                    if ($rate <= $qb_rate["hit_num"]) {
                        //你中了QB
                        $ticket = $qb * 100; //QB 和奖券得比率为100
                        $ret = self::addMontyAndTicket($user_id, 1005, $money, $ticket);
                        $gift_grade->where(["grade_id" => $grade_id])->update(["grade_qb" => $qb]);
                        $grade_info .= " , Q币x{$qb}";
                    } else {
                        //没有中QB
                        $ret = self::addMontyAndTicket($user_id, 1005, $money, 0);
                    }
                    if ($ret) {

                        //如果加钱成功---在设置VIP
                        $vip_ret = self::addVip($user_id, $vip, $vip_days);
                        if (!$vip_ret) {
                            $gift_grade->where(["grade_id" => $grade_id])->update(["grade_vip_status" => 1]);
                            //如果更新失败,记录日志
                            self::logWrite("grade_id = {$grade_id} 更新vip状态grade_vip_status = 1 失败");
                        }


                        //这里面是发送奖励成功
                        $gift_grade->commit();

                        $return_data["status"] = true;
                        $return_data["info"] = "领取了奖励:<span style='color: red;font-weight: bold;'> {$grade_info}</span>";
                        return $return_data;
                    }

                }
            } catch (ExceptionExt $e) {
                $gift_grade->rollBack();
            }
            $gift_grade->rollBack();

        }
        return $return_data;

    }


    public static function addMontyAndTicket($user_id, $task_type, $money, $ticket)
    {
        $userMoneyOracleModel = new UserMoneyOrale("write");

        $task_data = $userMoneyOracleModel->p_settask($user_id, $task_type);

        //这里设置任务失败
        if (!$task_data["status"]) {
            return false;
        }
        $ret = $userMoneyOracleModel->p_task_reward($user_id, $money, $ticket, 0, $task_data["task_id"]);

        if ($ret) {
            if ($money) {
                self::sendClientMoneyNotice($user_id, $money);
            }
            if ($ticket) {
                $sid = SessionUtil::get("sid");
                self::sendClientTicket($user_id, $ticket, 40, $sid);
            }

        }

        return $ret;


    }


    //这里我通知一下用户加了金币?
    public static function sendClientMoneyNotice($user_id, $money)
    {
        $msg = new MsgSocket();
        $msg->goldChange($user_id, $money);
        $msg->close();
    }

    public static function sendClientTicket($user_id, $number, $pid, $sid)
    {
        $msg = new MsgSocket();
        $msg->bagChange($user_id, $number, $pid, $sid);
        $msg->close();
    }
}