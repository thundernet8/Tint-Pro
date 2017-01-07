<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/11 00:31
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 根据页面输出相应标题
 *
 * @since   2.0.0
 * @return  string
 */
function tt_get_page_title() {
    $title = '';
    if($action = get_query_var('action')) {
        switch ($action) {
            case 'signin':
                $title = __('Sign In', 'tt');
                break;
            case 'signup':
                $title = __('Sign Up', 'tt');
                break;
            case 'activate':
                $title = __('Activate Registration', 'tt');
                break;
            case 'signout':
                $title = __('Sign Out', 'tt');
                break;
            case 'findpass':
                $title = __('Find Password', 'tt');
                break;
            case 'resetpass':
                $title = __('Reset Password', 'tt');
                break;
        }
        return $title . ' - ' . get_bloginfo('name');
    }
    if($me_route = get_query_var('me_child_route')) {
        switch ($me_route) {
            case 'settings':
                $title = __('My Settings', 'tt');
                break;
            case 'notifications':
                $title = __('My Notifications', 'tt');
                break;
            case 'messages': //TODO grandchild route in/out msgbox
                $title = __('My Messages', 'tt');
                break;
            case 'stars':
                $title = __('My Stars', 'tt');
                break;
            case 'credits':
                $title = __('My Credits', 'tt');
                break;
            case 'orders':
                $title = __('My Orders', 'tt');
                break;
            case 'order':
                $title = __('My Order', 'tt');
                break;
            case 'drafts':
                $title = __('My Drafts', 'tt');
                break;
            case 'newpost':
                $title = __('New Post', 'tt');
                break;
            case 'editpost':
                $title = __('Edit Post', 'tt');
                break;
            case 'membership':
                $title = __('My Membership', 'tt');
                break;
        }
        return $title . ' - ' . get_bloginfo('name');
    }
    if($site_util = get_query_var('site_util')) {
        switch ($site_util){
            case 'checkout':
                $title = __('Check Out Orders', 'tt');
                break;
            case 'payresult':
                $title = __('Payment Result', 'tt');
                break;
            case 'qrpay':
                $title = __('Do Payment', 'tt');
                break;
            case 'download':
                global $origin_post;
                $title = __('Resources Download:', 'tt') . $origin_post->post_title;
                break;
            case 'privacy-policies-and-terms':
                $title = __('Privacy Policies and Terms', 'tt');
                break;
            // TODO more
        }
        return $title . ' - ' . get_bloginfo('name');
    }
    if($oauth = get_query_var('oauth') && get_query_var('oauth_last')) {
        switch ($oauth) {
            case 'qq':
                $title = __('Complete Account Info - QQ Connect', 'tt');
                break;
            case 'weibo':
                $title = __('Complete Account Info - Weibo Connect', 'tt');
                break;
            case 'weixin':
                $title = __('Complete Account Info - Weixin Connect', 'tt');
                break;
        }
        return $title . ' - ' . get_bloginfo('name');
    }
    if($site_manage = get_query_var('manage_child_route')) {
        switch ($site_manage) {
            case 'status':
                $title = __('Site Statistic', 'tt');
                break;
            case 'posts':
                $title = __('Posts Management', 'tt');
                break;
            case 'comments':
                $title = __('Comments Management', 'tt');
                break;
            case 'users':
                $title = __('Users Management', 'tt');
                break;
            case 'orders':
                $title = __('Orders Management', 'tt');
                break;
            case 'coupons':
                $title = __('Coupons Management', 'tt');
                break;
            case 'members':
                $title = __('Members Management', 'tt');
                break;
        }
        return $title . ' - ' . get_bloginfo('name');
    }
    if(is_home() || is_front_page()) {
        $title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
    }elseif(is_single()&&get_post_type() != 'product') {
        $title = trim(wp_title('', false));
        if($page = get_query_var('page') && get_query_var('page') > 1){
            $title .= sprintf(__(' - Page %d','tt'), $page);
        }
        $title .= ' - ' . get_bloginfo('name');
    }elseif(is_page()){
        $title = trim(wp_title('', false)) . ' - ' . get_bloginfo('name');
    }elseif(is_category()) {
        $title = get_queried_object()->cat_name . ' - ' . get_bloginfo('name');
    }elseif(is_author()){
        // TODO more tab titles
        $author = get_queried_object();
        $name = $author->data->display_name;
        $title = sprintf(__('%s\'s Home Page', 'tt'), $name) . ' - ' . get_bloginfo('name');
    }elseif(get_post_type() == 'product'){
        if(is_archive()){
            if(tt_is_product_category()) {
                $title = get_queried_object()->name . ' - ' . __('Product Category', 'tt');
            }elseif(tt_is_product_tag()){
                $title = get_queried_object()->name . ' - ' . __('Product Tag', 'tt');
            }else{
                $title = __('Market', 'tt') . ' - ' . get_bloginfo('name');
            }
        }else{
            $title = trim(wp_title('', false)) . ' - ' . __('Market', 'tt');
        }
    }elseif(is_search()){
        $title = __('Search', 'tt') . get_search_query() . ' - ' . get_bloginfo('name');
    }elseif(is_year()){
        $title = get_the_time(__('Y','tt')) . __('posts archive', 'tt') . ' - ' . get_bloginfo('name');
    }elseif(is_month()){
        $title = get_the_time(__('Y.n','tt')) . __('posts archive', 'tt') . ' - ' . get_bloginfo('name');
    }elseif(is_day()){
        $title = get_the_time(__('Y.n.j','tt')) . __('posts archive', 'tt') . ' - ' . get_bloginfo('name');
    }elseif(is_tag()){
        $title = __('Tag: ', 'tt') . get_queried_object()->tag_name . ' - ' . get_bloginfo('name');
    }elseif(is_404()){
        $title = __('Page Not Found', 'tt') . ' - ' . get_bloginfo('name');
    }

    // paged
    if($paged = get_query_var('paged') && get_query_var('paged') > 1){
        $title .= sprintf(__(' - Page %d ','tt'), $paged + 1);
    }

    return $title;
}


/**
 * 获取关键词和描述
 *
 * @since 2.0.0
 * @return array
 */
function tt_get_keywords_and_description() {
    $keywords = '';
    $description = '';
    if($action = get_query_var('action')) {
        switch ($action) {
            case 'signin':
                $keywords = __('Sign In', 'tt');
                break;
            case 'signup':
                $keywords = __('Sign Up', 'tt');
                break;
            case 'activate':
                $keywords = __('Activate Registration', 'tt');
                break;
            case 'signout':
                $keywords = __('Sign Out', 'tt');
                break;
            case 'findpass':
                $keywords = __('Find Password', 'tt');
                break;
        }
        $description = __('Powered by Tint(Tinection 2)', 'tt');
        return array(
            'keywords' => $keywords,
            'description' => $description
        );
    }
    if(is_home() || is_front_page()) {
        $keywords = tt_get_option('tt_home_keywords');
        $description = tt_get_option('tt_home_description');
    }elseif(is_single() && get_post_type() != 'product') {
        $tags = get_the_tags();
        $tag_names = array();
        if($tags){
            foreach ($tags as $tag){
                $tag_names[] = $tag->name;
            }
            $keywords = implode(',', $tag_names);
        }
        $description = strip_tags(get_the_excerpt());
    }elseif(is_page()){
        global $post;
        if($post->ID){
            $keywords = get_post_meta($post->ID, 'tt_keywords', true);
            $description = get_post_meta($post->ID, 'tt_description', true);
        }
    }elseif(is_category()) {
        $category = get_queried_object();
        $keywords = $category->name;
        $description = strip_tags($category->description);
    }elseif(is_author()){
        // TODO more tab titles
        $author = get_queried_object();
        $name = $author->data->display_name;
        $keywords = $name . ',' . __('Ucenter', 'tt') . __('Tint Ucenter and Market System', 'tt');
        $description = sprintf(__('%s\'s Home Page', 'tt'), $name) . __('Powered by Tint(Tinection 2)', 'tt');
    }elseif(get_post_type() == 'product'){
        if(is_archive()){
            if(tt_is_product_category()) {
                $category = get_queried_object();
                $keywords = $category->name;
                $description = strip_tags($category->description);
            }elseif(tt_is_product_tag()){
                $tag = get_queried_object();
                $keywords = $tag->name;
                $description = strip_tags($tag->description);
            }else{
                $keywords = tt_get_option('tt_shop_keywords', __('Market', 'tt')) . ',' . __('Tint Ucenter and Market System', 'tt');
                $banner_title = tt_get_option('tt_shop_title', 'Shop Quality Products');
                $banner_subtitle = tt_get_option('tt_shop_sub_title', 'Themes - Plugins - Services');
                $description = $banner_title . ', ' . $banner_subtitle . ', ' . __('Powered by Tint(Tinection 2, a powerful wordpress theme with ucenter and shop system integrated)', 'tt');
            }
        }else{
            global $post;
            $tags = array();
            if($post->ID){
                $tags = wp_get_post_terms($post->ID, 'product_tag');
            }
            $tag_names = array();
            foreach ($tags as $tag){
                $tag_names[] = $tag->name;
            }
            $keywords = implode(',', $tag_names);
            $description = strip_tags(get_the_excerpt());
        }
    }elseif(is_search()){
        //TODO
    }elseif(is_year()){
        //TODO
    }elseif(is_month()){
        //TODO
    }elseif(is_day()){
        //TODO
    }elseif(is_tag()){
        $tag = get_queried_object();
        $keywords = $tag->name;
        $description = strip_tags($tag->description);
    }elseif(is_404()){
        //TODO
    }

    return array(
        'keywords' => $keywords,
        'description' => $description
    );
}