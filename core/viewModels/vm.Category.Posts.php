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
 * Class CategoryPostsVM
 */
class CategoryPostsVM extends BaseVM {

    /**
     * @var int 分页号
     */
    private $_page = 1;

    /**
     * @var int 分类ID
     */
    private $_catID;

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
        $cat_ID = absint(get_queried_object_id());
        $instance = new static(); // 因为不同分页不同分类共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_cat' . $cat_ID . '_page' . $page;
        $instance->_page = max(1, $page);
        $instance->_catID = $cat_ID;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $category_ID = $this->_catID;
        $category = get_category($category_ID);
        $category_link = get_category_link($category_ID);
        $category->category_link = $category_link;
        //$category_description = $category->description;//category_description();

//        $args = array(
//            'post_type' => 'post',
//            'post_status' => 'publish',
//            'posts_per_page' => get_option('posts_per_page', 10),
//            'paged' => $this->_page,
//            'category_in' => $category_ID,
//            'has_password' => false,
//            'ignore_sticky_posts' => true,
//            'orderby' => 'date', // modified - 如果按最新编辑时间排序
//            'order' => 'DESC'
//        );

        //$query = new WP_Query($args); // 如果需要自定义循环，如改变排序行为，可取消该注释
        global $wp_query;
        $query = $wp_query;
        //$GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query)

        $category_posts = array();

        $big_page_link = get_pagenum_link(999999999);
        $pagination = array(
            'max_num_pages' => $query->max_num_pages,
            'current_page' => $this->_page,
            'base' => str_replace('999999999', '%#%', $big_page_link),
            'next' => str_replace('999999999', $this->_page+1, $big_page_link)
        );

        while ($query->have_posts()) : $query->the_post();
            $category_post = array();
            global $post;
            $category_post['ID'] = $post->ID;
            $category_post['title'] = get_the_title($post);
            $category_post['permalink'] = get_permalink($post);
            $category_post['comment_count'] = $post->comment_count;
            $category_post['excerpt'] = get_the_excerpt($post);
            $category_post['category'] = sprintf('<a class="category" href="%1$s" rel="bookmark">%2$s</a>', $category_link, $category->cat_name);
            $category_post['author'] = get_the_author();
            $category_post['author_url'] = home_url('/@' . $category_post['author']); //TODO the link
            $category_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $category_post['timediff'] = Utils::getTimeDiffString(get_post_time('Y-m-d G:i:s', true));
            $category_post['datetime'] = get_the_time(DATE_W3C, $post);
            $category_post['thumb'] = tt_get_thumb($post, 'medium');
            $category_post['format'] = get_post_format($post) ? : 'standard';

            $star_user_ids = array_unique(get_post_meta( $post->ID, 'tt_post_star_users', false)); //TODO 最多显示10个，最新的靠前(待确认)
            $stars = count($star_user_ids);
            $category_post['star_count'] = $stars;

            $category_posts[] = $category_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'category' => (array)$category,
            'pagination' => $pagination,
            'category_posts' => $category_posts
        );
    }
}
