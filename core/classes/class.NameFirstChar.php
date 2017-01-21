<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/18 21:52
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

class NameFirstChar {

    /**
     * 获取字符串的第一位ASCII码，限数字英文字母，其他以`#`代替，主要用于生成对应用户名字符头像
     */


    /**
     * 构造器
     *
     * @since   2.0.0
     *
     * @access  public
     * @param   string  $name       待查找首字符的名字
     * @param   bool    $convertNum 是否转换数字为字母
     * @param   string  $default    缺省返回值
     */
    public function __construct($name, $convertNum=true, $default="#"){
        $this->_name = $name;
        $this->_convertNum = $convertNum;
        $this->_default = $default;
        $this->firstChar = $this->getFirstChar();
    }


    /**
     * 待查找首字符的名字
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     string
     */
    private $_name;


    /**
     * 是否转换数字为字母
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     bool
     */
    private $_convertNum;


    /**
     * 缺省返回值
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     string
     */
    private $_default;


    /**
     * 查找结果值
     *
     * @since   2.0.0
     *
     * @access  public
     * @var     string
     */
    public $firstChar;


    /**
     * 对应字母的GB2312中文起始计算码
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     array
     */
    private $_pinyinLetters = array(
        176161 => 'A',
        176197 => 'B',
        178193 => 'C',
        180238 => 'D',
        182234 => 'E',
        183162 => 'F',
        184193 => 'G',
        185254 => 'H',
        187247 => 'J',
        191166 => 'K',
        192172 => 'L',
        194232 => 'M',
        196195 => 'N',
        197182 => 'O',
        197190 => 'P',
        198218 => 'Q',
        200187 => 'R',
        200246 => 'S',
        203250 => 'T',
        205218 => 'W',
        206244 => 'X',
        209185 => 'Y',
        212209 => 'Z',
    );


    /**
     * 0-9 对应字母，取数字的英文首字母
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     array
     */
    private $_numLetters = array(
        0 => 'Z',
        1 => 'O',
        2 => 'T',
        3 => 'T',
        4 => 'F',
        5 => 'F',
        6 => 'S',
        7 => 'S',
        8 => 'E',
        9 => 'N'
    );


    /**
     * 二分搜索法查找GB2312计算码对应字母
     *
     * @since   2.0.0
     *
     * @access  private
     * @param   integer   $code
     * @return  integer
     */
    private function dichotomyLetterSearch($code){
        $keys = array_keys($this->_pinyinLetters);
        $lower = 0;
        $upper = sizeof($this->_pinyinLetters)-1;
        $middle = (int) round(($lower + $upper) / 2);
        if ( $code < $keys[0] ) return -1;
        for (;;) {
            if ( $lower > $upper ){
                return $keys[$lower-1];
            }
            $tmp = (int) round(($lower + $upper) / 2);
            if ( !isset($keys[$tmp]) ){
                return $keys[$middle];
            }else{
                $middle = $tmp;
            }
            if ( $keys[$middle] < $code ){
                $lower = (int)$middle + 1;
            }else if ( $keys[$middle] == $code ) {
                return $keys[$middle];
            }else{
                $upper = (int)$middle - 1;
            }
        }
        return -1;
    }


    /**
     * 获取字符串首字母或数字字符
     *
     * @since   2.0.0
     *
     * @access  private
     * @return  string  查找的首字符结果
     */
    private function getFirstChar(){
        if(preg_match('/^[a-zA-Z]/', $this->_name)){
            //TODO $this->prefixType = "Letter"
            return $this->_name[0];
        }elseif(preg_match('/^[0-9]/', $this->_name)){
            //TODO $this->prefixType = "Number"
            return $this->_convertNum ? $this->_numLetters[$this->_name[0]] : $this->_name[0];
        }elseif (preg_match('/^[一-龥]/', $this->_name)) {
            //TODO $this->prefixType = "Chn"
            if(!$str = iconv( 'utf-8', 'gb2312', $this->_name )){
                return $this->_default;
            }
            $code = ord( substr($str, 0, 1) ) * 1000 + ord( substr($str, 1, 1) );
            if(($i=$this->dichotomyLetterSearch($code)) != -1){
                return $this->_pinyinLetters[$i];
            }
            return $this->_default;
        }
        return $this->_default;
        //ascii 0-9(48-57) A-Z(65-90) a-z(97-122)
    }


    /**
     * 转换首字符为大写
     *
     * @since   2.0.0
     *
     * @access  public
     * @return  string
     */
    public function toUpperCase(){
        //return  strtoupper($this->firstChar);
        return ucfirst($this->firstChar); // Sharp
    }


    /**
     * 转换首字符为小写
     *
     * @since   2.0.0
     *
     * @access  public
     * @return  string
     */
    public function toLowerCase(){
        return  strtolower($this->firstChar);
    }
}