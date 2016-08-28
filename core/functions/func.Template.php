<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/22 20:42
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

/**
 * 重新定义文章、页面(非自定义模板页面)、分类、作者、归档、404等模板位置
 * https://developer.wordpress.org/themes/basics/template-hierarchy/
 * https://developer.wordpress.org/files/2014/10/template-hierarchy.png 了解WordPress模板系统
 */

/**
 * 自定义Index模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_index_template($template){
    //TODO: if(tt_get_option('layout')=='xxx') -> index-xxx.php
    unset($template);
    return THEME_TPL . '/tpl.Index.php';
}
add_filter('index_template', 'tt_get_index_template', 10, 1);


/**
 * 自定义Home文章列表模板，优先级高于Index
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_home_template($template){
    unset($template);
    return THEME_TPL . '/tpl.Home.php';
}
add_filter('home_template', 'tt_get_home_template', 10, 1);


/**
 * 自定义首页静态页面模板，基于后台选项首页展示方式，与Index同级
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_front_page_template($template){
    unset($template);
    return locate_template(array('core/templates/tpl.FrontPage.php', 'core/templates/tpl.Home.php', 'core/templates/tpl.Index.php'));
}
add_filter('front_page_template', 'tt_get_front_page_template', 10, 1);


/**
 * 自定义404模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_404_template($template){
    unset($template);
    return THEME_TPL . '/tpl.404.php';
}
add_filter('404_template', 'tt_get_404_template', 10, 1);


/**
 * 自定义归档模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_archive_template($template){
    unset($template);
    return THEME_TPL . '/tpl.Archive.php';
}
add_filter('archive_template', 'tt_get_archive_template', 10, 1);


/**
 * 自定义作者模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  array               自定义模板路径数组
 */
function tt_get_author_template($template){
    unset($template);

    // 为不同角色用户定义不同模板
    // https://developer.wordpress.org/themes/basics/template-hierarchy/#example
    $author = get_queried_object();
    $role = $author->roles[0];

    // 判断是否用户中心页(因为用户中心页和默认的作者页采用了相同的wp_query_object)
    if(get_query_var('uc') && intval(get_query_var('uc'))===1){
        $template = apply_filters('user_template', $author);
        if($template === 'header-404') return '';
        if($template) return $template;
    }

    $template = 'core/templates/tpl.Author.php'; // TODO: 是否废弃 tpl.Author类似模板，Author已合并至UC
    return locate_template( array( 'core/templates/tpl.Author.' . ucfirst($role) . '.php', $template ) );
}
add_filter('author_template', 'tt_get_author_template', 10, 1);


/**
 * 获取用户页模板
 * // 主题将用户与作者相区分，作者页沿用默认的WP设计，展示作者的文章列表，用户页重新设计为用户的各种信息以及前台用户中心
 *
 * @since   2.0.0
 *
 * @param   object  $user   WP_User对象
 * @return  string
 */
function tt_get_user_template($user) {
    $templates = array();

    if ( $user instanceof WP_User ) {
        if($uc_tab = get_query_var('uctab')){
            // 由于profile tab是默认tab，直接使用/@nickname主路由，对于/@nickname/profile的链接会重定向处理，因此不放至允许的tabs中
            $allow_tabs = json_decode(ALLOWED_UC_TABS);
            if(!in_array($uc_tab, $allow_tabs)) return 'header-404';
             $templates[] = 'core/templates/tpl.UC.' . strtolower($uc_tab) . '.php';
        }else{
            //$role = $user->roles[0];
            $templates[] = 'core/templates/tpl.UC.Profile.php';
            //
            //
            // Maybe dropped
            // TODO: maybe add membership templates
        }
    }
    $templates[] = 'core/templates/tpl.UC.php';

    return locate_template($templates);
}
add_filter('user_template', 'tt_get_user_template', 10, 1);


/**
 * 自定义分类模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_category_template($template){
    unset($template);
    return locate_template(array('core/templates/tpl.Category.php', 'core/templates/tpl.Archive.php'));
}
add_filter('category_template', 'tt_get_category_template', 10, 1);


/**
 * 自定义标签模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径数组
 */
function tt_get_tag_template($template){
    unset($template);
    return locate_template(array('core/templates/tpl.Tag.php', 'core/templates/tpl.Archive.php'));
}
add_filter('tag_template', 'tt_get_tag_template', 10, 1);


/**
 * 自定义Taxonomy模板，Category/Tag均属于Taxonomy，可做备选模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_taxonomy_template($template){
    unset($template);
    return locate_template(array('core/templates/tpl.Taxonomy.php', 'core/templates/tpl.Archive.php'));
}
add_filter('taxonomy_template', 'tt_get_taxonomy_template', 10, 1);


/**
 * 自定义时间归档模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_date_template($template){
    unset($template);
    return locate_template(array('core/templates/tpl.Date.php', 'core/templates/tpl.Archive.php'));
}
add_filter('date_template', 'tt_get_date_template', 10, 1);


/**
 * 自定义默认页面模板(区别于自定义页面模板)
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_page_template($template){
    if(!empty($template)) return $template;
    unset($template);
    return locate_template(array('core/templates/tpl.Page.php'));
}
add_filter('page_template', 'tt_get_page_template', 10, 1);


/**
 * 自定义搜素结果页模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_search_template($template){
    unset($template);
    return locate_template(array('core/templates/tpl.Search.php'));
}
add_filter('search_template', 'tt_get_search_template', 10, 1);


/**
 * 自定义文章页模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_single_template($template){
    unset($template);
    $single = get_queried_object();
    return locate_template(array('core/templates/tpl.Single.' . $single->slug . '.php', 'core/templates/tpl.Single.' . $single->ID . '.php', 'core/templates/tpl.Single.php'));
}
add_filter('single_template', 'tt_get_single_template', 10, 1);


/**
 * 自定义附件页模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_attachment_template($template){
    unset($template);
    return locate_template(array('core/templates/tpl.Attachment.php'));
}
add_filter('attachment_template', 'tt_get_attachment_template', 10, 1);


/**
 * 自定义[Plain] Text附件模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  array               自定义模板路径数组
 */
function tt_get_text_template($template){
    //TODO: other MIME types, e.g `video`
    unset($template);
    return locate_template(array('core/templates/tpl.MIMEText.php', 'core/templates/tpl.Attachment.php'));
}
add_filter('text_template', 'tt_get_text_template', 10, 1);
add_filter('plain_template', 'tt_get_text_template', 10, 1);
add_filter('text_plain_template', 'tt_get_text_template', 10, 1);


/**
 * 自定义弹出评论模板
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_comments_popup_template($template){
    unset($template);
    return THEME_TPL . '/tpl.CommentPopup.php';
}
add_filter('comments_popup', 'tt_get_comments_popup_template', 10, 1);


/**
 * 自定义嵌入式文章模板
 * WordPress 4.4新功能
 * https://make.wordpress.org/core/2015/10/28/new-embeds-feature-in-wordpress-4-4/
 *
 * @since   2.0.0
 *
 * @param   string  $template   默认模板路径
 * @return  string              自定义模板路径
 */
function tt_get_embed_template($template){
    unset($template);
    return THEME_TPL . '/tpl.Embed.php';
}
add_filter('embed_template', 'tt_get_embed_template', 10, 1);


