<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/16 20:24
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 在主查询生成前过滤参数(因为使用了原生paged分页参数, 导致作者页文章以外其他tab的分页不能超过文章分页数量, 否则404)
 *
 * @since 2.0.0
 * @param WP_Query $q
 * @return void
 */
function tt_reset_uc_pre_get_posts( $q ) { //TODO 分页不存在时返回404
    if(get_post_type() == 'product'){
        $q->set( 'posts_per_page', 12 ); //商品archive页默认12篇每页
    }elseif(is_search() && isset($_GET['in_shop']) && $_GET['in_shop'] == 1){
        $q->set( 'posts_per_page', 12 ); //商品搜索页默认12篇每页
    }elseif($uctab = get_query_var('uctab') && $q->is_main_query()) {
        if(in_array($uctab, array('comments', 'stars', 'followers', 'following', 'chat'))) {
            $q->set( 'posts_per_page', 1 );
            $q->set( 'offset', 0 ); //此时paged参数不起作用
        }
    }elseif($manage = get_query_var('manage_child_route') && $q->is_main_query()){
        if(in_array($manage, array('orders', 'users', 'members', 'coupons'))) {
            $q->set( 'posts_per_page', 1 );
            $q->set( 'offset', 0 ); //此时paged参数不起作用
        }
    }elseif($me = get_query_var('me_child_route') && $q->is_main_query()){
        if(in_array($me, array('orders', 'users', 'credits', 'messages', 'following', 'followers'))) {
            $q->set( 'posts_per_page', 1 );
            $q->set( 'offset', 0 ); //此时paged参数不起作用
        }
    }
}
add_action( 'pre_get_posts', 'tt_reset_uc_pre_get_posts' );