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
        }
        return $title . ' - ' . get_bloginfo('name');
    }
    if(is_home() || is_front_page()) {
        $title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
    }elseif(is_single()) {
        $title = wp_title('', false);
        if($page = get_query_var('page') && get_query_var('page') > 1){
            $title .= sprintf(__(' - Page %d','tt'), $page);
        }
        $title .= ' - ' . get_bloginfo('name');
    }elseif(is_category()) {
        $title = get_queried_object()->cat_name . ' - ' . get_bloginfo('name');
    }elseif(is_author()){
        // TODO more tab titles
        $author = get_queried_object();
        $name = $author->data->display_name;
        $title = sprintf(__('%s\'s Home Page', 'tt'), $name) . ' - ' . get_bloginfo('name');
    }elseif(is_search()){
        $title = get_search_query() . ' - ' . get_bloginfo('name');
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
        $title .= sprintf(__(' - Page %d ','tt'), $paged);
    }

    return $title;
}
