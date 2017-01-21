<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/19 16:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class ShopProductVM
 */
class ShopProductVM extends BaseVM {
    /**
     * @var int 商品ID
     */
    private $_productId = 0;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $product_id   商品ID
     * @return  static
     */
    public static function getInstance($product_id = 1) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_product' . $product_id . '_user' . get_current_user_id();
        $instance->_productId = absint($product_id);
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $the_product = null;
        while(have_posts()) : the_post();
            global $post;
            $the_product = $post ? : get_post($this->_productId);
        endwhile;

        // 基本信息
        $info = array();
        $info['ID'] = $the_product->ID;
        $info['title'] = get_the_title($the_product);
        $info['permalink'] = get_permalink($the_product);
        $info['comment_count'] = $the_product->comment_count;
        $info['comment_status'] = !($the_product->comment_status != 'open');
        $info['excerpt'] = get_the_excerpt($the_product);
        $content = get_the_content();
        $content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $content ) );
        $info['content'] =  $content; //$the_product->post_content;
        //$info['category'] = get_the_category_list(' ', '', $the_product->ID);
        //$info['author'] = get_the_author();
        //$info['author_url'] = home_url('/@' . $info['author']); //TODO the link
        $info['time'] = get_post_time('F j, Y', false, $the_product, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
        $info['datetime'] = get_the_time(DATE_W3C, $the_product);
        $info['timediff'] = Utils::getTimeDiffString($info['datetime']);
        $info['thumb'] = tt_get_thumb($the_product, array('width' => 720, 'height' => 600, 'str' => 'medium'));
        //$info['format'] = get_post_format($the_product) ? : 'standard';

        // 标签
        $tag_terms = get_the_terms($the_product, 'product_tag');
        $tagIDs = array();
        $tags = array();
        if($tag_terms) {
            foreach ($tag_terms as $tag_term){
                $tagIDs[] = $tag_term->term_id;

                $tag = array();
                $tag['ID'] = $tag_term->term_id;
                $tag['slug'] = $tag_term->slug;
                $tag['name'] = $tag_term->name;
                $tag['description'] = $tag_term->description;
                $tag['parent'] = $tag_term->parent;
                $tag['count'] = $tag_term->count;
                $tag['permalink'] = get_term_link($tag_term, 'product_tag');

                $tags[] = $tag;
            }
        }

        // 分类
        $cat_terms = get_the_terms($the_product, 'product_category');
        $catIDs = array();
        $cats = array();
        foreach ($cat_terms as $cat_term){
            $catIDs[] = $cat_term->term_id;

            $cat = array();
            $cat['ID'] = $cat_term->term_id;
            $cat['slug'] = $cat_term->slug;
            $cat['name'] = $cat_term->name;
            $cat['description'] = $cat_term->description;
            $cat['parent'] = $cat_term->parent;
            $cat['count'] = $cat_term->count;
            $cat['permalink'] = get_term_link($cat_term, 'product_category');

            $cats[] = $cat;
        }

        // 支付类型
        $info['currency'] = get_post_meta( $the_product->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';

        // 价格
        $info['price'] = $info['currency'] == 'cash' ? sprintf('%0.2f', get_post_meta($the_product->ID, 'tt_product_price', true)) : (int)get_post_meta($the_product->ID, 'tt_product_price', true);

        // 单位
        $info['price_unit'] = $info['currency'] == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt');

        // 价格图标
        $info['price_icon'] = !($info['price'] > 0) ? '' : $info['currency'] == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>';

        // 折扣
        $info['discount'] = maybe_unserialize(get_post_meta($the_product->ID, 'tt_product_discount', true)); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣

        // 总量
        $info['amount'] = (int)get_post_meta($the_product->ID, 'tt_product_quantity', true);

        // 销量
        $info['sales'] = get_post_meta($the_product->ID, 'tt_product_sales', true);

        // 浏览数
        $views = absint(get_post_meta( $the_product->ID, 'views', true ));

        // 点赞
        $star_user_ids = array_unique(get_post_meta( $the_product->ID, 'tt_post_star_users', false));
        $stars = count($star_user_ids);
        $star_users = array();
        $limit = min(count($star_user_ids), 10);
        for ($i = 0; $i < $limit; $i++) {
            $uid = $star_user_ids[$i];
            $star_users[] = (object)array(
                'uid' => $uid,
                'name' => get_userdata($uid)->display_name,
                'avatar' => tt_get_avatar($uid, 'small')
            );
        }

        // 打分
        $rating_raw = get_post_meta($the_product->ID, 'tt_post_ratings', true);
        $rating_arr = $rating_raw ? (array)maybe_unserialize($rating_raw) : array(); // array(rating value1, rating value2...)
        $rating_count = count($rating_arr);
        $rating_value = !$rating_count ? '0.0' : sprintf('%0.1f', array_sum($rating_arr)/$rating_count);
        $rating_percent = intval($rating_value*100/5);
        $rating = array(
            'count' => $rating_count,
            'value' => $rating_value,
            'percent' => $rating_percent
        );
        //$me_stared = in_array(get_current_user_id(), $star_user_ids); //缓存后会发生偏离

        // 购买渠道
        $channel_raw = strval(get_post_meta($the_product->ID, 'tt_buy_channel', true));
        $channel = in_array($channel_raw, array('instation', 'taobao')) ? $channel_raw : 'instation';
        $taobao = $channel == 'instation' ? '' : esc_url(get_post_meta($the_product->ID, 'tt_taobao_link', true));

        // 上下篇
        $prev = get_previous_post_link('%link');
        $next = get_next_post_link('%link');

        // 相关商品
        $relates_query_args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_tag',
                    'field' => 'term_id',
                    'terms' => $tagIDs
                )
            ),
            'post__not_in'=>array($the_product->ID),
            'showposts'=>4,
            'orderby'=>'rand',
            'ignore_sticky_posts'=>1
        );
        $relates_query = null;
        $the_query = new WP_Query($relates_query_args);
        if(count($tagIDs) > 0 && $the_query->have_posts()) {
            $relates_query = $the_query;
        }else{
            $r_cats = get_the_terms($the_product, 'product_category');
            $r_catIDs = array();
            foreach ($r_cats as $r_cat){
                $r_catIDs[] = $r_cat->term_id;
            }
            $relates_query_args = array(
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_category',
                        'field' => 'term_id',
                        'terms' => $r_catIDs
                    )
                ),
                'post__not_in'=>array($the_product->ID),
                'showposts'=>4,
                'orderby'=>'rand',
                'ignore_sticky_posts'=>1
            );
            $relates_query = new WP_Query($relates_query_args);
        }

        $related_products = array();

        while ($relates_query->have_posts()) : $relates_query->the_post();
            $related_product = array();
            global $post;
            $related_product['title'] = get_the_title($post);
            $related_product['permalink'] = get_permalink($post);
            //$related_post['comment_count'] = $post->comment_count;
            $related_product['category'] = get_the_term_list($post->ID, 'product_category', '', ' · ', ''); //get_the_category_list(' · ', '', $post->ID);
            //$popular_post['author'] = get_the_author(); //TODO add link
            //$popular_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            //$popular_post['datetime'] = get_the_time(DATE_W3C, $post);
            $related_product['thumb'] = tt_get_thumb($post, array(
                'width' => 375,
                'height' => 250,
                'str' => 'medium'
            ));
            $related_product['sales'] = get_post_meta($post->ID, 'tt_product_sales', true);
            $related_product['currency'] = get_post_meta( $post->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';
            $related_product['price'] = $related_product['currency'] == 'cash' ? sprintf('%0.2f', get_post_meta($post->ID, 'tt_product_price', true)) : (int)get_post_meta($post->ID, 'tt_product_price', true);
            $related_product['price_unit'] = $related_product['currency'] == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt');
            $related_product['price_icon'] = !($related_product['price'] > 0) ? '' : $related_product['currency'] == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>';
            $related_product['discount'] = tt_get_product_discount_array($post->ID); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣
            // 打分
            $related_rating_raw = get_post_meta($post->ID, 'tt_post_ratings', true);
            $related_rating_arr = $related_rating_raw ? (array)maybe_unserialize($related_rating_raw) : array(); // array(rating value1, rating value2...)
            $related_rating_count = count($related_rating_arr);
            $related_rating_value = !$related_rating_count ? '0.0' : sprintf('%0.1f', array_sum($related_rating_arr)/$related_rating_count);
            $related_rating_percent = intval($related_rating_value*100/5);
            $related_product['rating'] = array(
                'count' => $related_rating_count,
                'value' => $related_rating_value,
                'percent' => $related_rating_percent
            );

            $related_products[] = $related_product;
        endwhile;

        //wp_reset_postdata();

        // 边栏 - 随机商品
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'showposts'=>5,
            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'rand', //date // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );

        $rand_query = new WP_Query($args);
        $rand_products = array();
        while ($rand_query->have_posts()) : $rand_query->the_post();
            $rand_product = array();
            global $post;
            $rand_product['title'] = get_the_title($post);
            $rand_product['permalink'] = get_permalink($post);
            $rand_product['category'] = get_the_term_list($post->ID, 'product_category', '', ' · ', ''); //get_the_category_list(' · ', '', $post->ID);
            $rand_product['thumb'] = tt_get_thumb($post, array(
                'width' => 100,
                'height' => 100,
                'str' => 'thumbnail'
            ));
            $rand_product['sales'] = get_post_meta($post->ID, 'tt_product_sales', true);
            $rand_product['currency'] = get_post_meta( $post->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';
            $rand_product['price'] = $rand_product['currency'] == 'cash' ? sprintf('%0.2f', get_post_meta($post->ID, 'tt_product_price', true)) : (int)get_post_meta($post->ID, 'tt_product_price', true);
            $rand_product['price_unit'] = $rand_product['currency'] == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt');
            $rand_product['price_icon'] = !($rand_product['price'] > 0) ? '' : $rand_product['currency'] == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>';
            $rand_product['discount'] = tt_get_product_discount_array($post->ID); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣
            $rand_products[] = $rand_product;
        endwhile;


        // reset
        //wp_reset_postdata();

        // return
        return (object)array_merge(
            $info,
            array(
                'views'        => $views,
                'stars'        => $stars,
                'star_users'   => $star_users,
                'likes'        => $stars,
                'rating'       => $rating,
                'channel'      => $channel,
                'taobao'       => $taobao,
                'prev'         => $prev,
                'next'         => $next,
                'relates'      => $related_products,
                'tags'         => $tags,
                'cats'         => $cats,
                'catIDs'       => $catIDs,
                'rands'        => $rand_products,
                'star_uids'    => $star_user_ids
            )
        );
    }
}