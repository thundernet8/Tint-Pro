<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/13 20:10
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 验证码
 * Class Captcha
 */
class Captcha{

    /**
     * 验证码宽度
     *
     * @since   2.0.0
     * @access  private
     * @var int
     */
    private $_width ;

    /**
     * 验证码高度
     *
     * @since   2.0.0
     * @access  private
     * @var int
     */
    private $_height;

    /**
     * 验证码字符个数
     *
     * @since   2.0.0
     * @access  private
     * @var int
     */
    private $_counts;

    /**
     * 允许的字符范围
     *
     * @since   2.0.0
     * @access  private
     * @var string
     */
    private $_distrubcode;

    /**
     * 验证码字体
     *
     * @since   2.0.0
     * @access  private
     * @var string
     */
    private $_font;

    /**
     * Session
     *
     * @since   2.0.0
     * @access  private
     * @var string
     */
    private $_session;

    function __construct($width = 120, $height = 30, $counts = 5, $distrubcode, $font){
        // Check for GD library
        if( !function_exists('gd_info') ) {
            throw new Exception('Required GD library is missing', 'tt');
        }

        $this->_width = $width;
        $this->_height = $height;
        $this->_counts = $counts;
        $this->_distrubcode = empty($distrubcode) ? "1235467890qwertyuipkjhgfdaszxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM" : $distrubcode;
        $this->_font = empty($font) ? THEME_ASSET . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'TitilliumWeb-Regular.ttf' : $font;
        $this->_session = $this->sessioncode();

        session_start();
        $_SESSION['tt_captcha'] = $this->_session;
    }

    /**
     * 产生随机的字符用于验证码
     *
     * @since   2.0.0
     * @access  private
     * @return string
     */
    private function sessioncode(){
        $originalcode = $this->_distrubcode;
        $countdistrub = strlen($originalcode);
        $_dscode = "";
        $counts=$this->_counts;
        for($j=0; $j<$counts; $j++){
            $dscode = $originalcode[rand(0, $countdistrub-1)];
            $_dscode.=$dscode;
        }

        return $_dscode;
    }

    /**
     * 创建画布资源
     *
     * @since   2.0.0
     * @access  private
     * @return resource
     */
    private function create_imagesource(){
        return imagecreate($this->_width,$this->_height);
    }

    /**
     * 设置背景色
     *
     * @since   2.0.0
     * @access  private
     * @param resource  $im 画布资源
     * @return void
     */
    private function set_backgroundcolor($im){
        $bgcolor = imagecolorallocate($im, rand(200,255), rand(200,255), rand(200,255));
        imagefill($im, 0, 0, $bgcolor);
    }

    /**
     * 添加验证码字符
     *
     * @since   2.0.0
     * @access  private
     * @param resource  $im 画布资源
     * @return  void
     */
    private function set_code($im){
        $width=$this->_width;
        $counts=$this->_counts;
        $height=$this->_height;
        $scode=$this->_session;
        $y=floor($height/2) + floor($height/4);
        $fontsize=rand(20,25);
        $font=$this->_font;

        for($i=0; $i<$counts; $i++){
            $char=$scode[$i];
            $x=floor($width/$counts)*$i+8;
            $angle=rand(-20,30);
            $color = imagecolorallocate($im, rand(0,50), rand(50,100), rand(100,140));
            imagettftext($im, $fontsize, $angle, $x, $y, $color, $font, $char);
        }
    }

    /**
     * 添加验证码干扰字符
     *
     * @since   2.0.0
     * @access  private
     * @param resource  $im 画布资源
     * @return  void
     */
    private function set_distrubecode($im){
        $count_h=$this->_height;
        $cou=floor($count_h*2);

        for($i=0; $i<$cou; $i++){
            $x=rand(0,$this->_width);
            $y=rand(0,$this->_height);
            $angle=rand(0,360);
            $fontsize=rand(4,6);
            $font=$this->_font;
            $originalcode = $this->_distrubcode;
            $countdistrub = strlen($originalcode);
            $dscode = $originalcode[rand(0, $countdistrub-1)];
            $color = imagecolorallocate($im, rand(40,140), rand(40,140), rand(40,140));
            imagettftext($im, $fontsize, $angle, $x, $y, $color, $font, $dscode);
        }
    }

    /**
     * 输出图像
     *
     * @since   2.0.0
     * @access  private
     * @return  void
     */
    function imageout(){
        $im = $this->create_imagesource();
        $this->set_backgroundcolor($im);
        $this->set_code($im);
        $this->set_distrubecode($im);

        imagepng($im);
        imagedestroy($im);
    }
}