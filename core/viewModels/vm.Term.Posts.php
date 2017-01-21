<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/20 22:56
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class TermPostsVM
 */
class TermPostsVM extends BaseVM {

    /**
     * @var int 分页号
     */
    private $_page = 1;

    /**
     * @var int 归类ID
     */
    private $_termID;

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
        $term_ID = absint(get_queried_object_id());
        $instance = new static(); // 因为不同分页不同分类共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_term' . $term_ID . '_page' . $page;
        $instance->_page = max(1, $page);
        $instance->_termID = $term_ID;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $term_ID = $this->_termID;
        $term = get_term($term_ID);
        $term_link = get_term_link($term_ID);
        $term->term_link = $term_link;

        global $wp_query;
        $query = $wp_query;

        $term_posts = array();

        $big_page_link = get_pagenum_link(999999999);
        $pagination = array(
            'max_num_pages' => $query->max_num_pages,
            'current_page' => $this->_page,
            'base' => str_replace('999999999', '%#%', $big_page_link),
            'next' => str_replace('999999999', $this->_page+1, $big_page_link)
        );

        while ($query->have_posts()) : $query->the_post();
            $term_post = array();
            global $post;
            $term_post['ID'] = $post->ID;
            $term_post['title'] = get_the_title($post);
            $term_post['permalink'] = get_permalink($post);
            $term_post['comment_count'] = $post->comment_count;
            $term_post['excerpt'] = get_the_excerpt($post);
            $term_post['term'] = sprintf('<a class="term" href="%1$s" rel="bookmark">%2$s</a>', $term_link, $term->cat_name);
            $term_post['author'] = get_the_author();
            $term_post['author_url'] = home_url('/@' . $term_post['author']); //TODO the link
            $term_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $term_post['timediff'] = Utils::getTimeDiffString(get_post_time('Y-m-d G:i:s', true));
            $term_post['datetime'] = get_the_time(DATE_W3C, $post);
            $term_post['thumb'] = tt_get_thumb($post, 'medium');
            $term_post['format'] = get_post_format($post) ? : 'standard';

            $star_user_ids = array_unique(get_post_meta( $post->ID, 'tt_post_star_users', false)); //TODO 最多显示10个，最新的靠前(待确认)
            $stars = count($star_user_ids);
            $term_post['star_count'] = $stars;

            $term_posts[] = $term_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'term' => (array)$term,
            'pagination' => $pagination,
            'term_posts' => $term_posts
        );
    }
}
