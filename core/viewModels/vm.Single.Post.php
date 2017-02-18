<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/07 16:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class SinglePostVM
 */
class SinglePostVM extends BaseVM {
    /**
     * @var int 文章ID
     */
    private $_postId = 0;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $post_id   文章ID
     * @return  static
     */
    public static function getInstance($post_id = 1) {
        $instance = new static(); // 因为不同分页共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_post' . $post_id;
        $instance->_postId = absint($post_id);
        //$instance->_enableCache = false; //Debug关闭缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $the_post = null;
        while(have_posts()) : the_post();
        global $post;
        $the_post = $post ? : get_post($this->_postId);
        endwhile;

        // 基本信息
        $info = array();
        $info['ID'] = $the_post->ID;
        $info['title'] = get_the_title($the_post);
        $info['permalink'] = get_permalink($the_post);
        $info['comment_count'] = $the_post->comment_count;
        $info['comment_status'] = !($the_post->comment_status != 'open');
        $info['excerpt'] = get_the_excerpt($the_post);
        $content = get_the_content();
        $content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $content ) );
        $info['content'] =  $content; //$the_post->post_content;
        $info['category'] = get_the_category_list(' ', '', $the_post->ID);
        $info['tags'] = get_the_tag_list(' ', ' ', '', $the_post->ID);
        $info['author'] = get_the_author();
        $info['author_url'] = get_author_posts_url($the_post->post_author);
        $info['time'] = get_post_time('F j, Y', false, $the_post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
        $info['datetime'] = get_the_time(DATE_W3C, $the_post);
        $info['timediff'] = Utils::getTimeDiffString($info['datetime']);
        $info['thumb'] = tt_get_thumb($the_post, array('width' => 720, 'height' => 400, 'str' => 'medium'));
        $info['format'] = get_post_format($the_post) ? : 'standard';

        // 相关下载
        $free_download = get_post_meta($the_post->ID, 'tt_free_dl', true);
        $sale_download = get_post_meta($the_post->ID, 'tt_sale_dl', true);
        if(!empty($free_download) || !empty($sale_download)) {
            $info['download'] = tt_url_for('download', $the_post->ID);
        }

        // 内嵌商品
        $embed_product_id = (int)get_post_meta($the_post->ID, 'tt_embed_product', true);
        $product = get_post($embed_product_id);

        if($product && $product->post_type == 'product' && $product->post_status == 'publish'){
            $pay_currency = get_post_meta( $product->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';
            $product_price = $pay_currency == 'cash' ? sprintf('%0.2f', get_post_meta($product->ID, 'tt_product_price', true)) : (int)get_post_meta($product->ID, 'tt_product_price', true);
            $rating_raw = get_post_meta($product->ID, 'tt_post_ratings', true);
            $rating_arr = $rating_raw ? (array)maybe_unserialize($rating_raw) : array(); // array(rating value1, rating value2...)
            $rating_count = count($rating_arr);
            $rating_value = !$rating_count ? '0.0' : sprintf('%0.1f', array_sum($rating_arr)/$rating_count);
            $rating_percent = intval($rating_value*100/5);
            $rating = array(
                'count' => $rating_count,
                'value' => $rating_value,
                'percent' => $rating_percent
            );
            $info['embed_product'] = array(
                'product_id' => $product->ID,
                'product_name' => $product->post_title,
                'product_description' => get_the_excerpt($product),
                'product_link' => get_permalink($product),
                'product_thumb' => tt_get_thumb($product, array('width' => 100, 'height' => 100, 'str' => 'thumbnail')),
                'pay_currency' => $pay_currency,
                'product_price' => $product_price,
                'price_unit' => $pay_currency == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt'),
                'price_icon' => !($product_price > 0) ? '' : $pay_currency == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>',
                'product_discount' => tt_get_product_discount_array($product->ID),
                'product_sales' => get_post_meta($product->ID, 'tt_product_sales', true),
                'product_views' => absint(get_post_meta( $product->ID, 'views', true )),
                'product_rating' => $rating
            );
        }


        // 文章来源版权信息
        $cc = get_post_meta( $the_post->ID, 'tt_post_copyright', true );
        $cc = $cc ? maybe_unserialize($cc) : array('source_title' => '', 'source_link' => '');
        $source_title = !empty($cc['source_title']) ? $cc['source_title'] : $info['title'];
        $source_link = !empty($cc['source_link']) ? $cc['source_link'] : null;
        $cc_text = !$source_link ?
            sprintf(__('All the posts are created by <a href="%1$s" title="%2$s" target="_blank">%2$s</a> if without special annotations, please mark the source as <a href="%3$s" title="%4$s">%3$s</a> when reprinting', 'tt'), home_url(), get_bloginfo('name'), $info['permalink'], $info['title'])
            : sprintf(__('The post is from: <a href="%1$s" title="%2$s" target="_blank">%2$s</a>', 'tt'), $source_link, $source_title);

        // 浏览数
        $views = absint(get_post_meta( $the_post->ID, 'views', true ));

        // 点赞
        //$stars = intval(get_post_meta( $the_post->ID, 'tt_post_stars', true )); // 可以直接count $star_users替代

        $star_user_ids = array_unique(get_post_meta( $the_post->ID, 'tt_post_star_users', false)); //TODO 最多显示10个，最新的靠前(待确认)
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

        //$me_stared = in_array(get_current_user_id(), $star_user_ids); //缓存后会发生偏离

        // 上下篇文章
        $prev = get_previous_post_link('%link');
        $next = get_next_post_link('%link');

        // 相关文章
        $tags = wp_get_post_tags($the_post->ID);
        $tagIDs = array();
        foreach ($tags as $tag){
            $tagIDs[] = $tag->term_id;
        }
        $relates_query_args = array(
            'tag__in'=>$tagIDs,
            'post__not_in'=>array($the_post->ID),
            'showposts'=>3,
            'orderby'=>'rand',
            'ignore_sticky_posts'=>1
        );
        $relates_query = null;
        $the_query = new WP_Query($relates_query_args);
        if(count($tagIDs) > 0 && ($the_query->have_posts())) {
            $relates_query = $the_query;
        }else{
            $catIDs = wp_get_post_categories($the_post->ID);
//            $catIDs = array();
//            foreach ($categories as $category){
//                $catIDs[] = $category->term_id;
//            }
            $relates_query_args = array(
                'category__in'=>$catIDs,
                'post__not_in'=>array($the_post->ID),
                'showposts'=>3,
                'orderby'=>'rand',
                'ignore_sticky_posts'=>1
            );
            $relates_query = new WP_Query($relates_query_args);
        }

        $related_posts = array();

        while ($relates_query->have_posts()) : $relates_query->the_post();
            $related_post = array();
            global $post;
            $related_post['title'] = get_the_title($post);
            $related_post['permalink'] = get_permalink($post);
            //$related_post['comment_count'] = $post->comment_count;
            $related_post['category'] = get_the_category_list(' · ', '', $post->ID);
            //$popular_post['author'] = get_the_author(); //TODO add link
            //$popular_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            //$popular_post['datetime'] = get_the_time(DATE_W3C, $post);
            $related_post['thumb'] = tt_get_thumb($post, array(
                'width' => 375,
                'height' => 250,
                'str' => 'medium'
            ));

            $related_posts[] = $related_post;
        endwhile;

        //wp_reset_postdata();

        // 当前用户
        // $uid = get_current_user_id(); 导致登录后这个缓存的uid还是0

        return (object)array_merge(
            $info,
            array(
                'source_title' => $source_title,
                'source_link'  => $source_link,
                'cc_text'      => $cc_text,
                'views'        => $views,
                'stars'        => $stars,
                'star_users'   => $star_users,
                'likes'        => $stars,
                'prev'         => $prev,
                'next'         => $next,
                'relates'      => $related_posts,
                //'uid'          => $uid,
                //'me_stared'   => $me_stared,
                'star_uids'    => $star_user_ids
            )
        );
    }
}