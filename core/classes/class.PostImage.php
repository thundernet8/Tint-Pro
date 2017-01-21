<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/06/22 15:35
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class PostImage
 */
final class PostImage{
    /**
     * 文章缩略图或图片处理操作相关
     */


    /**
     * 关联的文章对象
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     object
     */
    private $_post;


    /**
     * 图片尺寸
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     string | array
     */
    private $_size;


    /**
     * 缓存键
     *
     * @since   2.0.0
     *
     * @access  public
     * @var     string
     */
    public $cache_key;

    /**
     * 构造器,获得该类的一个实例
     *
     * @since   2.0.0
     *
     * @access  public
     * @param   int | object    $post   WP_Post对象或Post_ID
     * @param   string | array  $size   缩略图尺寸
     */
    public function __construct($post, $size = 'thumbnail'){
        $this->_post = get_post($post);
        $this->_size = self::getFormatedSize($size);
        $key = CACHE_PREFIX . '_weekly' . '_thumb_' . md5(strval($this->_post->ID) . strval($this->_size['width'] . 'x' . $this->_size['height']) . Utils::getCurrentDateTimeStr('week'));
        $this->cache_key = $key;
    }


    /**
     * 获取缩略图(自动选择来源)
     *
     * @since   2.0.0
     *
     * @access  public
     * @param   string | array  $size   图片尺寸
     * @return  string
     */
    public function getThumb($size = ''){
        if(!$size){
            $size = $this->_size;
        }else{
            $size = self::getFormatedSize($size);
        }
        $featured = self::getFeaturedImage($size['str']);
        if($featured) return self::getOptimizedImageUrl($featured, $size);

        // 无特色图像则抓取第一张文章内图片
        $first_image = $this->getPostImage('first');
        if($first_image) return self::getOptimizedImageUrl($first_image, $size);

        // 无文章内部图像则采用随机图片
        $random_image = self::getRandomThumb();
        return self::getOptimizedImageUrl($random_image, $size);
    }


    /**
     * 获取随机缩略图链接
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @param   int     $max    最多支持的图片数量
     * @return  string
     */
    private static function getRandomThumb($max = 40){
        return THEME_URI . '/assets/img/thumb/' . mt_rand(1, absint($max)) . '.jpg';
    }


    /**
     * 获取文章内部图片作为缩略图
     *
     * @since   2.0.0
     *
     * @access  private
     * @param   string  $position   选取的图片位置(first|last|random)
     * @return  string | false
     */
    private function getPostImage($position){
        preg_match_all("/<img([^>]*)\s*src=['|\"]([^'\"]+)['|\"]/i", $this->_post->post_content, $matches); //TODO [\s]+

        $img_links = $matches[2];
        if(!count($img_links)) return false;
        switch ($position){
            case 'last':
                return array_pop($img_links);
                break;
            case 'random':
                return $img_links[mt_rand(0, count($img_links)-1)];
                break;
            default:
                return array_shift($img_links);
        }
    }


    /**
     * 获取特色图像
     *
     * @since   2.0.0
     *
     * @access  private
     * @param   string  $size   图像尺寸(thumbnail|medium|large)
     * @return  string | false
     */
    private function getFeaturedImage($size = 'thumbnail'){
        if (!has_post_thumbnail($this->_post)) return false;
        $img_info = wp_get_attachment_image_src(get_post_thumbnail_id($this->_post->ID), $size);
        return $img_info[0];
    }


    /**
     * 获取Timthumb裁剪的图片链接
     *
     * @since   2.0.0
     *
     * @static
     * @access  public
     * @param   string  $url   原始图片链接
     * @param   string | array $size    图片尺寸
     * @return  string
     */
    public static function getTimthumbImage($url, $size = 'thumbnail'){
        $timthumb = THEME_URI . '/core/library/timthumb/Timthumb.php';

        // 不裁剪Gif，因为生成黑色无效图片
        $imgtype = strtolower(substr($url, strrpos($url, '.')));
        if($imgtype === 'gif') return $url;

        $size = self::getFormatedSize($size);

        return $timthumb . '?src=' . $url . '&q=90&w=' . $size['width'] . '&h=' . $size['height'] . '&zc=1';
    }


    /**
     * 获取用于CDN平台处理的图片链接
     *
     * @since   2.0.0
     *
     * @static
     * @access  public
     * @param   string  $url  原始图片链接
     * @param   string | array $size    图片尺寸
     * @return  string
     */
    public static function getCdnPreparedImage($url, $size = 'thumbnail'){

        $size = self::getFormatedSize($size);

        return $url . '?imageView2/1/w/' . $size['width'] .'/h/' . $size['height'] . '/q/100';
    }


    /**
     * 根据用户设置选择合适的图片链接处理方式(timthumb|cdn)
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @param   string  $url    待处理的图片链接
     * @param   string | array  $size   图片尺寸
     * @return  string
     */
    private static function getOptimizedImageUrl($url, $size){
        if(tt_get_option('tt_enable_timthumb', false)){
            return self::getTimthumbImage($url, $size);
        }
        return self::getCdnPreparedImage($url, $size);
    }


    /**
     * 转换尺寸
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @param   string | array  $size   原始尺寸信息
     * @return  array
     */
    private static function getFormatedSize($size){
        if(is_array($size)){
            $width = array_key_exists('width', $size) ? $size['width'] : 225;
            $height = array_key_exists('height', $size) ? $size['height'] : 150;
            $str = array_key_exists('str', $size) ? $size['str'] : 'thumbnail';
        }else{
            switch ($size){
                case 'medium':
                    $width = 375;
                    $height = 250;
                    $str = 'medium';
                    break;
                case 'large':
                    $width = 960;
                    $height = 640;
                    $str = 'large';
                    break;
                default:
                    $width = 225;
                    $height = 150;
                    $str = 'thumbnail';
            }
        }

        return array(
            'width'   =>  $width,
            'height'  =>  $height,
            'str'     =>  $str
        );
    }

}
