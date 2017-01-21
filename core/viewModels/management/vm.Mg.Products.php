<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/09 21:00
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MgProductsVM
 */
class MgProductsVM extends BaseVM {

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
        $instance->_enableCache = false; // TODO Debug  // 不使用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $posts_per_page = get_option('posts_per_page', 10);
        $args = array(
            'post_type' => 'product',
            'post_status' => 'draft,pending,publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $this->_page,
//            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'modified', // modified - 如果按最新编辑时间排序 or date
            'order' => 'DESC'
        );

        $query = new WP_Query($args);
        $query->is_home = false;
        $query->is_author = false;
        $GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query) //TODO 缓存时无效

        $products = array();
        $count = $query->found_posts;
        $max_pages = $query->max_num_pages; //ceil($count / $posts_per_page);
        $pagination_base = tt_url_for('manage_posts') . '/page/%#%';

        while ($query->have_posts()) : $query->the_post();
            $product = array();
            global $post;
            $product['ID'] = $post->ID;
            $product['title'] = get_the_title($post);
            $product['permalink'] = get_permalink($post);
            //$product['comment_count'] = $post->comment_count;
            $product['excerpt'] = get_the_excerpt($post);
            $product['category'] = get_the_term_list($post->ID, 'product_category', ' ', '');
            //$product['author'] = get_the_author();
            //$product['author_url'] = get_author_posts_url(get_the_author_meta('ID'));
            $product['time'] = get_post_time('Y-m-d H:i:s', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $product['datetime'] = get_the_time(DATE_W3C, $post);
            $product['modified_time'] = get_post_modified_time('Y-m-d H:i:s', false, $post);
            $product['thumb'] = tt_get_thumb($post, 'medium');
            //$product['format'] = get_post_format($post) ? : 'standard';

            // 支付类型
            $product['currency'] = get_post_meta( $post->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';

            // 价格
            $product['price'] = $product['currency'] == 'cash' ? sprintf('%0.2f', get_post_meta($post->ID, 'tt_product_price', true)) : (int)get_post_meta($post->ID, 'tt_product_price', true);

            // 单位
            $product['price_unit'] = $product['currency'] == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt');

            // 价格图标
            $product['price_icon'] = !($product['price'] > 0) ? '' : $product['currency'] == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>';

            // 折扣
            //$product['discount'] = maybe_unserialize(get_post_meta($post->ID, 'tt_product_discount', true)); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣

            // 库存
            $product['amount'] = (int)get_post_meta($post->ID, 'tt_product_quantity', true);

            // 销量
            $product['sales'] = absint(get_post_meta($post->ID, 'tt_product_sales', true));

            $product['edit_link'] = get_edit_post_link($post->ID);//tt_url_for('edit_post', $post->ID);

            $product['post_status'] = $post->post_status;

            $product['status_string'] = __('On Sell', 'tt');
            if($post->post_status == 'pending') {
                $product['status_string'] = __('Await Sell', 'tt');
            }elseif($post->post_status == 'draft') {
                $product['status_string'] = __('Await Edit', 'tt');
            }

            $actions = array();
            $actions[] = array(
                'class' => 'btn btn-inverse product-act act-edit',
                'url' => $product['edit_link'],
                'text' => __('EDIT', 'tt'),
                'action' => ''
            );

            if($post->post_status == 'publish') {
                $actions[] = array(
                    'class' => 'btn btn-warning product-act act-draft',
                    'url' => 'javascript:;',
                    'text' => __('PULL DOWN', 'tt'),
                    'action' => 'draft'
                );
            }elseif($post->post_status == 'draft') {
                $actions[] = array(
                    'class' => 'btn btn-primary product-act act-publish',
                    'url' => 'javascript:;',
                    'text' => __('PUSH SELL', 'tt'),
                    'action' => 'publish'
                );
            }elseif($post->post_status == 'pending'){
                $actions[] = array(
                    'class' => 'btn btn-success product-act act-approve',
                    'url' => 'javascript:;',
                    'text' => __('PUSH SELL', 'tt'),
                    'action' => 'publish'
                );
            }
            $actions[] = array(
                'class' => 'btn btn-danger product-act act-trash',
                'url' => 'javascript:;',
                'text' => __('DELETE', 'tt'),
                'action' => 'trash'
            );
            $product['actions'] = $actions;

            $products[] = $product;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'count' => $count,
            'products' => $products,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}