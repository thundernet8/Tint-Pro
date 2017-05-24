<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/24 09:19
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class FeaturedCategoryVM
 */
class FeaturedCategoryVM extends BaseVM {

    /**
     * @var int 置顶分类的序号(从1开始)
     */
    private $_categorySequence = 1;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $sequence   置顶分类的序号(从1开始)
     * @return  static
     */
    public static function getInstance($sequence = 1) {
        $instance = new static(); // 因为首页置顶三个分类公用该模型，因此不采用单例模式，覆盖父类方法
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . $sequence;
        $instance->_categorySequence = in_array($sequence, array(1, 2, 3)) ? $sequence : 1;
        //$instance->_enableCache = false; // TODO debug use
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {

        switch ($this->_categorySequence) {
            case 2:
                $cat_Id = tt_get_option('tt_home_featured_category_two');
                break;
            case 3:
                $cat_Id = tt_get_option('tt_home_featured_category_three');
                break;
            default:
                $cat_Id = tt_get_option('tt_home_featured_category_one');
        }

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'showposts'	=> 3,
            'cat' => $cat_Id,
            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'date',
            'order' => 'DESC'   //TODO order customize
        );

        $query = new WP_Query($args);

        $cat_posts = array();
        $i = 0; // 区别分类的第一篇和其他文章，第一篇缩略图采用中等尺寸
        $cat = get_category($cat_Id);
        $cat_link = get_category_link($cat_Id);
        while ($query->have_posts()) : $query->the_post();
            $i += 1;
            $thumb_size = $i===1 ? 'medium' : 'thumbnail';
            $cat_post = array();
            global $post;
            $cat_post['title'] = get_the_title($post);
            $cat_post['permalink'] = get_permalink($post);
            $cat_post['comment_count'] = $post->comment_count;
            $cat_post['excerpt'] = get_the_excerpt($post);
            //$cat_post['category'] = get_the_category_list(' · ', '', $post->ID);
//            $cat_post['cat_name'] = $cat->cat_name;
//            $cat_post['cat_link'] = $cat_link;
            $cat_post['author'] = get_the_author();
            $cat_post['author_url'] = get_author_posts_url($post->post_author);
            $cat_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $cat_post['datetime'] = get_the_time(DATE_W3C, $post);
            $cat_post['thumb'] = tt_get_thumb($post, $thumb_size);

            $cat_posts[] = $cat_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'cat' => array(
                'cat_name' => $cat->cat_name,
                'cat_link' => $cat_link
            ),
            'cat_posts' => $cat_posts
        );
    }
}