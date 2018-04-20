<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 18:31
 * 点选验证码封装的库
 *
 */
namespace app\lib\VerifyImg;

use atphp\Config;

class VerifyImg
{
    private $data = "23456789ABCDEFGHIJKLMNPQRSTUVW";
    private $imgHandle = null;
    private $codes = [];//生成的字符坐标数组
    private $fontType = '';//字体
    private $thresholds = 7;//容错值
    private $angle = 10; //字体旋转角度

    private $width = 250;//图片的宽度
    private $height = 100;//图片的高度
    private $fontSize = 30;//字体大小
    private $lines = 15; //划线的条数
    private $dots = 30;//画点
    private $codes_num = 5;//有多少字符可供选择
    private $sessionName = "verifyimg";//存session名称
    private $font_space = 10;//给每个字符一些间隔
    private $selNums = 3;//可以选择3个
    private $circleSize = 30;//选择字符时候,的高度和宽度


    public function __construct()
    {
        //开启session
        session_start();

        //加载配置文件
        $config = Config::get("verifyImg");

        //随机选择字体
        $this->fontType = __DIR__ . "/ttfs/1.ttf";
        $this->width = isset($config['verify_width']) ?$config['verify_width']: $this->width;
        $this->height = isset($config['verify_height']) ?$config['verify_height']: $this->height;//图片的高度
        $this->fontSize =isset( $config['verify_fontsize']) ? $config['verify_fontsize']: $this->fontSize;//字体大小
        $this->lines = isset($config['verify_lines']) ?$config['verify_lines']: $this->lines;//划线的条数
        $this->dots =isset( $config['verify_dots']) ? $config['verify_dots']: $this->dots;//画点
        $this->codes_num =isset($config['verify_codes_num'])  ?$config['verify_codes_num']: $this->codes_num;//有多少字符可供选择
        $this->circleSize =isset($config['verify_circleSize'])  ?$config['verify_circleSize']: $this->circleSize;//选择字符时候,的高度和宽度
        $this->selNums = isset($config['verify_selNums']) ?$config['verify_selNums']: $this->selNums;//可以选择3个
        $this->font_space =isset($config['verify_font_space'])  ?$config['verify_font_space']: $this->font_space;//给每个字符一些间隔
        $this->font_space = isset($config['sessionName']) ?$config['sessionName']: $this->sessionName;//存session名称
    }

    public function getImg()
    {
        //获取验证码
        $this->codes = $this->getCode();

        // 画图像
        $this->imgHandle = imagecreatetruecolor($this->width, $this->height);
        // 定义要用到的颜色
        $back_color = imagecolorallocate($this->imgHandle, 243, 251, 254);

        // 画背景RGB(243,251,254)
        imagefilledrectangle($this->imgHandle, 0, 0, $this->width, $this->height, $back_color);

        // $this->_background();
        //画干扰线
        $this->makeLine();

        //画干扰点
        $this->makeDot();

        //画字符杂点
        $this->_writeNoise();

        //写字符
        $this->makeText();

        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/png;charset=gb2312");
        imagepng($this->imgHandle);
        imagedestroy($this->imgHandle);

    }


    /**
     * 画杂点
     * 往图片上写不同颜色的字母或数字
     */
    private function _writeNoise()
    {
        $codeSet = '2345678abcdefhijkmnpqrstuvwxyz';
        for ($i = 0; $i < 10; $i++) {
            //杂点颜色
            $noiseColor = imagecolorallocate($this->imgHandle, mt_rand(150, 225), mt_rand(150, 225), mt_rand(150, 225));
            for ($j = 0; $j < 5; $j++) {
                // 绘杂点
                imagestring($this->imgHandle, 5, mt_rand(-10, $this->width), mt_rand(-10, $this->height), $codeSet[mt_rand(0, 29)], $noiseColor);
            }
        }
    }

    /**
     * 绘制背景图片
     * 注：如果验证码输出图片比较大，将占用比较多的系统资源
     */
    private function _background()
    {
        $path = __DIR__ . '/bgs/';
        $dir = dir($path);

        $bgs = array();
        while (false !== ($file = $dir->read())) {
            if ($file[0] != '.' && substr($file, -4) == '.jpg') {
                $bgs[] = $path . $file;
            }
        }
        $dir->close();

        $gb = $bgs[array_rand($bgs)];

        list($width, $height) = @getimagesize($gb);
        // Resample
        $bgImage = @imagecreatefromjpeg($gb);
        @imagecopyresampled($this->imgHandle, $bgImage, 0, 0, 0, 0, $this->width, $this->height, $width, $height);
        @imagedestroy($bgImage);
    }


    //获取验证码
    private function getCode()
    {
        $numbers = $this->codes_num;
        $codes = [];
        for ($i = 0; $i < $numbers; $i++) {
            $codes[$i] = $this->data[mt_rand(0, strlen($this->data) - 1)];

            $this->data = str_replace($codes[$i], '', $this->data);
        }
        return $codes;

    }

    //写字符
    private function makeText()
    {
        //x,y坐标的范围
        $min_x = 0;
        $max_x = $this->width - $this->fontSize;

        $min_y = $this->fontSize;
        $max_y = $this->height - $this->fontSize / 2;

        //分割多少份
        $division_x = floor(($max_x - $min_x) / ($this->fontSize + $this->font_space)); //多给5个像素

        //每份的的起始坐标数组
        $positin_start_arr = [];
        for ($i = 0; $i < $division_x; $i++) {
            if ($i == 0) {
                $positin_start_arr[$i] = ($min_x + $this->fontSize) * ($i + 1);
            } else {
                $positin_start_arr[$i] = ($min_x + $this->fontSize + $this->font_space + 3) * ($i + 1);
            }

        }

        $position = [];
        for ($i = 0; $i < count($this->codes); $i++) {
            $y = rand($min_y, $max_y);
            $index_x = rand(0, count($positin_start_arr) - 1);
            $x = $positin_start_arr[$index_x];
            unset($positin_start_arr[$index_x]);
            $positin_start_arr = array_values($positin_start_arr);

            //  $color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

            $position[$i] = array($x, $y);

            //角度
            $angle = rand(-$this->angle, $this->angle);
            $rect = @imageTTFBbox($this->fontSize, 0, $this->fontType, $this->codes[$i]);

            $minX = min(array($rect[0], $rect[2], $rect[4], $rect[6]));
            $maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6]));
            $minY = min(array($rect[1], $rect[3], $rect[5], $rect[7]));
            $maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7]));

//        print_r(array(
//            "left"   => abs($minX) - 1,
//            "top"    => abs($minY) - 1,
//            "width"  => $maxX - $minX,
//            "height" => $maxY - $minY,
//            "box"    => $rect
//        ));
            $font_x_width = $maxX - $minX;
            $font_y_height = $maxY - $minY;

            $position[$i] = array("x" => $x, "y" => $y, "fontWidth" => $font_x_width, "fontHeight" => $font_y_height);
            $stringColor = imagecolorallocate($this->imgHandle, mt_rand(1, 150), mt_rand(1, 150), mt_rand(1, 150));
            // $stringColor = imagecolorallocate($this->imgHandle, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
            imagettftext($this->imgHandle, $this->fontSize, $angle, $x, $y, $stringColor, $this->fontType, $this->codes[$i]);
        }


        $data_index_arr = array_rand($position, $this->selNums);

        //选中字符的位置
        $check_codes_postion = []; //需要选中的文本位置信息
        $check_codes = []; //需要选中的文本
        foreach ($data_index_arr as $val) {
            $check_codes_postion[] = $position[$val];
            $check_codes[] = $this->codes[$val];
        }

        $_SESSION[$this->sessionName] = array(
            "codes" => $this->codes,
            "index" => $data_index_arr,
            "check_codes_postion" => $check_codes_postion,
            "check_codes" => $check_codes,
        );
    }


    //获取生成的验证码信息
    public function getData()
    {
        return isset($_SESSION[$this->sessionName])?$_SESSION[$this->sessionName]:null;
    }


    /**
     * 检测验证码
     * @param array $sel_position 选择的坐标地址
     */
    public function checkImg(Array $sel_position)
    {
        $data = $this->getData();


        $count = 0;
        foreach ($data['check_codes_postion'] as $key => $val) {
            $fontWidth = $val['fontWidth'];
            $fontHeight = $val['fontHeight'];

            $x1 = $val["x"];//x 最小值
            $x2 = $x1 + $fontWidth; //x 最大值

            $y1 = $val["y"] - $fontHeight;
            $y2 = $val["y"];

            $a1 = $sel_position[$key][0] - $this->thresholds; //用户输入最小值, x
            $a2 = $sel_position[$key][0] + $this->thresholds + $this->circleSize; //用户输入最大值 ,x

            $b1 = $sel_position[$key][1] - $this->thresholds;
            $b2 = $sel_position[$key][1] + $this->thresholds + $this->circleSize;

            if ($x1 >= $a1 && $x2 <= $a2 && $y1 >= $b1 && $y2 <= $b2) {
                $count++;
            }
        }
        if ($count == 3) {
            return true;
        }
        return false;
    }


    //画点
    private function makeDot()
    {
        for ($i = 0; $i < $this->dots; $i++) {
            $font_color = imagecolorallocate($this->imgHandle, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($this->imgHandle, mt_rand(0, $this->width), mt_rand(0, $this->height), $font_color);
        }
    }


    //画干扰线
    private function makeLine()
    {
        for ($i = 0; $i < $this->lines; $i++) {
            $font_color = imagecolorallocate($this->imgHandle, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagearc($this->imgHandle, mt_rand(-$this->width, $this->width), mt_rand(-$this->height, $this->height), mt_rand(30, $this->width * 2), mt_rand(20, $this->height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);

        }
    }


}