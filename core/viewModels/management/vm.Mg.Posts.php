<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 23:16
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MgPostsVM
 */
class MgPostsVM extends BaseVM {

    /**
     * @var int 分页号
     */
    private $_page = 1;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int $page
     * @return  static
     */
    public static function getInstance($page = 1) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_page' . $page;
        $instance->_page = $page;
        $instance->_enableCache = false; // TODO Debug // 不使用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $posts_per_page = get_option('posts_per_page', 10);
        $args = array(
            'post_type' => 'post',
            'post_status' => 'draft,pending,publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $this->_page,
//            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'date', // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );

        $query = new WP_Query($args);
        $query->is_home = false;
        $query->is_author = false;
        $GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query) //TODO 缓存时无效

        $manage_posts = array();
        $count = $query->found_posts;
        $max_pages = $query->max_num_pages; //ceil($count / $posts_per_page);
        $pagination_base = tt_url_for('manage_posts') . '/page/%#%';

        while ($query->have_posts()) : $query->the_post();
            $manage_post = array();
            global $post;
            $manage_post['ID'] = $post->ID;
            $manage_post['title'] = get_the_title($post);
            $manage_post['permalink'] = get_permalink($post);
            //$manage_post['comment_count'] = $post->comment_count;
            $manage_post['excerpt'] = get_the_excerpt($post);
            $manage_post['category'] = get_the_category_list(' ', '', $post->ID);
            $manage_post['author'] = get_the_author();
            $manage_post['author_url'] = get_author_posts_url(get_the_author_meta('ID'));
            $manage_post['time'] = get_post_time('Y-m-d H:i:s', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $manage_post['datetime'] = get_the_time(DATE_W3C, $post);
            $manage_post['thumb'] = tt_get_thumb($post, 'medium');
            $manage_post['format'] = get_post_format($post) ? : 'standard';

            $manage_post['edit_link'] = tt_url_for('edit_post', $post->ID);

            $manage_post['post_status'] = $post->post_status;

            $manage_post['status_string'] = '';
            if($post->post_status == 'pending') {
                $manage_post['status_string'] = __('PENDING', 'tt');
            }elseif($post->post_status == 'draft') {
                $manage_post['status_string'] = __('DRAFT', 'tt');
            }

            $actions = array();
            $actions[] = array(
                'class' => 'btn btn-inverse post-act act-edit',
                'url' => $manage_post['edit_link'],
                'text' => __('EDIT', 'tt'),
                'action' => ''
            );

            if($post->post_status == 'publish') {
                $actions[] = array(
                    'class' => 'btn btn-warning post-act act-draft',
                    'url' => 'javascript:;',
                    'text' => __('SAVE DRAFT', 'tt'),
                    'action' => 'draft'
                );
            }elseif($post->post_status == 'draft') {
                $actions[] = array(
                    'class' => 'btn btn-primary post-act act-publish',
                    'url' => 'javascript:;',
                    'text' => __('PUBLISH', 'tt'),
                    'action' => 'publish'
                );
            }elseif($post->post_status == 'pending'){
                $actions[] = array(
                    'class' => 'btn btn-success post-act act-approve',
                    'url' => 'javascript:;',
                    'text' => __('APPROVE', 'tt'),
                    'action' => 'publish'
                );
            }
            $actions[] = array(
                'class' => 'btn btn-danger post-act act-trash',
                'url' => 'javascript:;',
                'text' => __('TRASH', 'tt'),
                'action' => 'trash'
            );
            $manage_post['actions'] = $actions;

            $manage_posts[] = $manage_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'count' => $count,
            'posts' => $manage_posts,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}