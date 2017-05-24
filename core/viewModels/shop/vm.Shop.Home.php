<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/16 20:02
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class ShopHomeVM
 */
class ShopHomeVM extends BaseVM {

    /**
     * @var int 分页号
     */
    private $_page = 1;

    /**
     * @var string  排序类型
     */
    private $_sort = 'latest';

    /*
     * @var string 货币和价格类型
     */
    private $_priceType = 'all';

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $page   分页号
     * @param   string $sort   排序类型
     * @param   string $price_type 价格类型
     * @return  static
     */
    public static function getInstance($page = 1, $sort = 'latest', $price_type = 'all') {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_page' . $page . '_sort' . $sort . '_type' . $price_type;
        $instance->_page = max(1, $page);
        $instance->_sort = in_array($sort, array('latest', 'popular')) ? $sort : 'latest';
        $instance->_priceType = in_array($price_type, array('all', 'free', 'cash', 'credit')) ? $price_type : 'all';
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12, //get_option('posts_per_page', 10),
            'paged' => $this->_page,
            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'modified', //date // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );

        if($this->_sort == 'popular') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'tt_product_sales';
        }

        if($this->_priceType == 'free') {
            $args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key' => 'tt_product_price',
                    'value' => 0.01,
                    'compare' => '<'
                )
            );
        }elseif($this->_priceType == 'credit') {
            $args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key' => 'tt_pay_currency',
                    'value' => 0,
                    'compare' => '='
                ),
                array(
                    'key' => 'tt_product_price',
                    'value' => '0.00',
                    'compare' => '>'
                )
            );
        }elseif($this->_priceType == 'cash') {
            $args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key' => 'tt_pay_currency',
                    'value' => 1,
                    'compare' => '='
                ),
                array(
                    'key' => 'tt_product_price',
                    'value' => '0.00',
                    'compare' => '>'
                )
            );
        }

        $query = new WP_Query($args);
        $GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query) //TODO 缓存时无效

        $products = array();
        $pagination = array(
            'max_num_pages' => $query->max_num_pages,
            'current_page' => $this->_page,
            'base' => str_replace('999999999', '%#%', get_pagenum_link(999999999))
        );

        while ($query->have_posts()) : $query->the_post();
            $product = array();
            global $post;
            $product['ID'] = $post->ID;
            $product['title'] = get_the_title($post);
            $product['permalink'] = get_permalink($post);
            $product['comment_count'] = $post->comment_count;
            $product['excerpt'] = get_the_excerpt($post);
            $product['category'] = get_the_category_list(' ', '', $post->ID);
            $product['author'] = get_the_author();
            $product['author_url'] = get_author_posts_url($post->post_author);
            $product['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $product['datetime'] = get_the_time(DATE_W3C, $post);
            $product['thumb'] = tt_get_thumb($post, array('width' => 350, 'height' => 250, 'str' => 'medium'));
            //$product['format'] = get_post_format($post) ? : 'standard';

            $product['views'] = (int)get_post_meta($post->ID, 'views', true);

            // 销量
            $product['sales'] = (int)get_post_meta($post->ID, 'tt_product_sales', true);

            // 支付类型
            $product['currency'] = get_post_meta( $post->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';

            // 价格
            $product['price'] = $product['currency'] == 'cash' ? sprintf('%0.2f', get_post_meta($post->ID, 'tt_product_price', true)) : (int)get_post_meta($post->ID, 'tt_product_price', true);

            // 单位
            $product['price_unit'] = $product['currency'] == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt');

            // 价格图标
            $product['price_icon'] = !($product['price'] > 0) ? '' : $product['currency'] == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>';

            // 点赞
            $star_user_ids = array_unique(get_post_meta( $post->ID, 'tt_post_star_users', false));
            $product['stars'] = count($star_user_ids);

            $products[] = $product;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'pagination' => $pagination,
            'products' => $products
        );
    }
}