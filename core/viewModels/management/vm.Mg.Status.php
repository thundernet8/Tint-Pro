<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 21:40
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MgStatusVM
 */
class MgStatusVM extends BaseVM {

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @return  static
     */
    public static function getInstance() {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $statistic = array();

        global $wpdb;
        // 用户总数
        $statistic['user_count'] = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
        // 会员总数
        $statistic['monthly_member_count'] = tt_count_vip_members(Member::MONTHLY_VIP);
        $statistic['annual_member_count'] = tt_count_vip_members(Member::ANNUAL_VIP);
        $statistic['permanent_member_count'] = tt_count_vip_members(Member::PERMANENT_VIP);
        $statistic['member_count'] = $statistic['monthly_member_count'] + $statistic['annual_member_count'] + $statistic['permanent_member_count'];
        // 文章总数
        $count_posts = wp_count_posts();
        $statistic['publish_post_count'] = $count_posts->publish;
        $statistic['pending_post_count'] = $count_posts->pending;
        $statistic['draft_post_count'] = $count_posts->draft;
        $statistic['post_count'] = $statistic['publish_post_count'] + $statistic['pending_post_count'] + $statistic['draft_post_count'];
        // 页面总数
        $count_pages = wp_count_posts('page');
        $statistic['page_count'] = $count_pages->publish;
        // 商品总数
        $count_products = wp_count_posts('product');
        $statistic['product_count'] = $count_products->publish;
        // 评论总数
        $statistic['comment_count'] = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved=1 AND comment_type=''");
        // 分类总数
        $statistic['category_count'] = wp_count_terms('category');
        // 标签总数
        $statistic['tag_count'] = wp_count_terms('post_tag');
        // 商品分类总数
        $statistic['product_category_count'] = wp_count_terms('product_category');
        // 商品标签总数
        $statistic['product_tag_count'] = wp_count_terms('product_tag');

        // 友情链接数量
        $statistic['links_count'] =  $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->links WHERE link_visible = 'Y'");

        // 建站日期
        $statistic['site_open_date'] = tt_get_option('tt_site_open_date');
        // 运营天数
        $statistic['site_open_days'] = floor((time() - strtotime($statistic['site_open_date'])) / 86400);

        // 最后更新
        $modified_post_dates = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page' OR post_type = 'product') AND (post_status = 'publish' OR post_status = 'private')");
        $statistic['last_modified'] = date('Y年n月j日', strtotime($modified_post_dates[0]->MAX_m));

        return (object)$statistic;
    }
}