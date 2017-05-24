<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/22 21:08
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class DateArchivePostsVM
 */
class DateArchivePostsVM extends BaseVM {

    /**
     * @var int 分页号
     */
    private $_page = 1;

    /**
     * @var array
     */
    private $_period = null;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $page   分页号
     * @return  static
     */
    public static function getInstance($page = 1) {
        if(is_day()) {
            $period_type = 'day';
            $period_str = __('DAY ARCHIVE', 'tt');
            $period_num = get_the_time('j');
            $period_des = sprintf(__('All posts which were published in %s', 'tt'), get_the_time('F j, Y'));
        } elseif (is_month()) {
            $period_type = 'month';
            $period_str = __('MONTH ARCHIVE', 'tt');
            $period_num = get_the_time('m');
            $period_des = sprintf(__('All posts which were published in %s', 'tt'), get_the_time('F, Y'));
        } else {
            $period_type = 'year';
            $period_str = __('YEAR ARCHIVE', 'tt');
            $period_num = get_the_time('Y');
            $period_des = sprintf(__('All posts which were published in year %s', 'tt'), get_the_time('Y'));
        }
        $instance = new static(); // 因为不同分页不同标签共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_archive_' . $period_type . $period_num . '_page' . $page;
        $instance->_page = max(1, $page);
        $instance->_period = array(
            'type' => $period_type,
            'str'  => $period_str,
            'des'  => $period_des
        );
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $period = $this->_period;

        global $wp_query;
        $query = $wp_query;

        $date_posts = array();

        $big_page_link = get_pagenum_link(999999999);
        $pagination = array(
            'max_num_pages' => $query->max_num_pages,
            'current_page' => $this->_page,
            'base' => str_replace('999999999', '%#%', $big_page_link),
            'next' => str_replace('999999999', $this->_page+1, $big_page_link)
        );

        while ($query->have_posts()) : $query->the_post();
            $date_post = array();
            global $post;
            $date_post['ID'] = $post->ID;
            $date_post['title'] = get_the_title($post);
            $date_post['permalink'] = get_permalink($post);
            $date_post['comment_count'] = $post->comment_count;
            $date_post['excerpt'] = get_the_excerpt($post);
            $date_post['category'] = get_the_category_list(' ', '', $post->ID);
            $date_post['author'] = get_the_author();
            $date_post['author_url'] = home_url('/@' . $date_post['author']); //TODO the link
            $date_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $date_post['timediff'] = Utils::getTimeDiffString(get_post_time('Y-m-d G:i:s', true));
            $date_post['datetime'] = get_the_time(DATE_W3C, $post);
            $date_post['thumb'] = tt_get_thumb($post, 'medium');
            $date_post['format'] = get_post_format($post) ? : 'standard';

            $star_user_ids = array_unique(get_post_meta( $post->ID, 'tt_post_star_users', false));
            $stars = count($star_user_ids);
            $date_post['star_count'] = $stars;

            $date_posts[] = $date_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'period' => (array)$period,
            'pagination' => $pagination,
            'date_posts' => $date_posts
        );
    }
}