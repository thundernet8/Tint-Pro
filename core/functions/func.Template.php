<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 *
 * @author Zhiyan
 * @date 2016/08/22 20:42
 * @license GPL v3 LICENSE
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

    $template = THEME_TPL . '/tpl.Author.php';
    $role_template = locate_template( array( 'core/templates/tpl.Author.' . ucfirst($role) . '.php' ) );
    if(!empty($role_template)) return $role_template;

    return $template;
}
add_filter('author_template', 'tt_get_author_template', 10, 1);


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
    return THEME_TPL . '/tpl.Page.php';
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
    return THEME_TPL . '/tpl.Search.php';
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
    return THEME_TPL . '/tpl.Attachment.php';
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
    return array(THEME_TPL . '/tpl.MIMEText.php', THEME_TPL . '/tpl.Attachment.php');
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