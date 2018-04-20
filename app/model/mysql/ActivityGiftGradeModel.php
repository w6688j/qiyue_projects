<?php

/**
 * For: .....
 * User: caostian
 * Date: 2017/10/19
 * Time: 16:51
 */
namespace app\model\mysql;

use atphp\db\Model;

class ActivityGiftGradeModel extends Model
{
    public function __construct($data_config = null)
    {
        parent::__construct($data_config);
        $this->table("cb_activity_gift_grade");
    }



}