<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 14:47
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class HomeBulletinsVM
 */
class HomeBulletinsVM extends BaseVM {

    /**
     * @var string 排序
     */
    private $_orderBy = 'modified';

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*12; // 缓存保留半天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   $order_by
     * @return  static
     */
    public static function getInstance($order_by = 'modified') {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_orderBy_' . $order_by;
        $instance->_orderBy = $order_by;
        // $instance->_enableCache = false; // TODO debug use
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {

        $args = array(
            'post_type' => 'bulletin',
            'post_status' => 'publish',
            'posts_per_page' => 10, //tt_get_option('tt_bulletins_limit', 10),
            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => $this->_orderBy, //date // modified - 如果按最新编辑时间排序
            'order' => 'DESC',
            'date_query' => array(
                array(
                    'column' => 'post_modified_gmt',
                    'after'     => sprintf('midnight %d days ago', tt_get_option('tt_bulletin_effect_days', 10)),
                    'inclusive' => true,
                ),
            ),
        );

        $query = new WP_Query($args);

        $bulletins = array();

        while ($query->have_posts()) : $query->the_post();
            $bulletin = array();
            global $post;
            $bulletin['ID'] = $post->ID;
            $bulletin['title'] = get_the_title($post);
            $bulletin['permalink'] = get_permalink($post);
            $bulletin['excerpt'] = get_the_excerpt($post);
            $bulletin['author'] = get_the_author();
            $bulletin['author_url'] = get_author_posts_url($post->post_author);
//            $bulletin['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $bulletin['datetime'] = get_the_time('m-d', $post);
            $bulletin['modified'] = get_post_modified_time('m-d', false, $post);
            $bulletin['modified'] = !empty($bulletin['modified']) ? $bulletin['modified'] : $bulletin['datetime'];

            //$bulletin['views'] = (int)get_post_meta($post->ID, 'views', true);

            $bulletins[] = $bulletin;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'count' => count($bulletins),
            'bulletins' => $bulletins
        );
    }
}