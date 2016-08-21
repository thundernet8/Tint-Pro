<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @author Zhiyan
 * @date 2016/08/21 23:19
 * @license GPL v3 LICENSE
 */
?>

<?php

/**
 * 获取页面模板，由于Tint的模板文件存放目录不位于主题根目录，需要重写`get_page_templates`方法以获取
 *
 * @since   2.0.0
 *
 * @param   WP_Post|null    $post   当前编辑的页面实例，用于提供上下文环境
 * @return  array                   页面模板数组
 */
function tt_get_page_templates( $post = null ) {
    $theme = wp_get_theme();

    if ( $theme->errors() && $theme->errors()->get_error_codes() !== array( 'theme_parent_invalid' ) )
        return array();

    $page_templates = wp_cache_get( 'page_templates-' . md5('Tint'), 'themes' );

    if ( ! is_array( $page_templates ) ) {
        $page_templates = array();
        $files = (array) Utils::scandir( THEME_TPL, 'php', 0 );
        foreach ( $files as $file => $full_path ) {
            if ( ! preg_match( '|Template Name:(.*)$|mi', file_get_contents( $full_path ), $header ) )
                continue;
            $page_templates[ $file ] = _cleanup_header_comment( $header[1] );
        }
        wp_cache_add( 'page_templates-' . md5('Tint'), $page_templates, 'themes', 1800 );
    }

    if ( $theme->load_textdomain() ) {
        foreach ( $page_templates as &$page_template ) {
            $page_template = translate( $page_template, 'tt' );
        }
    }

    $templates = (array) apply_filters( 'theme_page_templates', $page_templates, $theme, $post );

    return array_flip( $templates );
}


/**
 * Page编辑页面的页面属性meta_box内容回调，重写了`page_attributes_meta_box`，以支持自定义页面模板的路径和可用模板选项
 *
 * @since   2.0.0
 *
 * @param   WP_Post   $post   页面实例
 * @return  string
 */
function tt_page_attributes_meta_box($post) {
    $post_type_object = get_post_type_object($post->post_type);
    if ( $post_type_object->hierarchical ) {
        $dropdown_args = array(
            'post_type'        => $post->post_type,
            'exclude_tree'     => $post->ID,
            'selected'         => $post->post_parent,
            'name'             => 'parent_id',
            'show_option_none' => __('(no parent)'),
            'sort_column'      => 'menu_order, post_title',
            'echo'             => 0,
        );

        $dropdown_args = apply_filters( 'page_attributes_dropdown_pages_args', $dropdown_args, $post );
        $pages = wp_dropdown_pages( $dropdown_args );
        if ( ! empty($pages) ) {
            ?>
            <p><strong><?php _e('Parent', 'tt') ?></strong></p>
            <label class="screen-reader-text" for="parent_id"><?php _e('Parent', 'tt') ?></label>
            <?php echo $pages; ?>
            <?php
        }
    }

    if ( 'page' == $post->post_type && 0 != count( tt_get_page_templates( $post ) ) && get_option( 'page_for_posts' ) != $post->ID ) {
        $template = !empty($post->page_template) ? $post->page_template : false;
        ?>
        <p><strong><?php _e('Template', 'tt') ?></strong><?php
            do_action( 'page_attributes_meta_box_template', $template, $post );
            ?></p>
        <label class="screen-reader-text" for="page_template"><?php _e('Page Template', 'tt') ?></label><select name="tt_page_template" id="page_template">
            <?php
            $default_title = apply_filters( 'default_page_template_title',  __( 'Default Template', 'tt' ), 'meta-box' );
            ?>
            <option value="default"><?php echo esc_html( $default_title ); ?></option>
            <?php tt_page_template_dropdown($template); ?>
        </select>
        <?php
    } ?>
    <p><strong><?php _e('Order', 'tt') ?></strong></p>
    <p><label class="screen-reader-text" for="menu_order"><?php _e('Order', 'tt') ?></label><input name="menu_order" type="text" size="4" id="menu_order" value="<?php echo esc_attr($post->menu_order) ?>" /></p>
    <?php if ( 'page' == $post->post_type && get_current_screen()->get_help_tabs() ) { ?>
        <p><?php _e( 'Need help? Use the Help tab in the upper right of your screen.', 'tt' ); ?></p>
        <?php
    }
}


/**
 * 移除默认并添加改写的Page编辑页面的页面属性meta_box，以支持自定义页面模板的路径和可用模板选项
 *
 * @since   2.0.0
 *
 *
 */
function tt_replace_page_attributes_meta_box(){
    remove_meta_box('pageparentdiv', 'page', 'side');
    add_meta_box('tt_pageparentdiv', __('Page Attributes', 'tt'), 'tt_page_attributes_meta_box', 'page', 'side', 'low');
}
add_action('admin_init', 'tt_replace_page_attributes_meta_box');


/**
 * Page编辑页面的页面属性meta_box内页面模板下拉选项内容
 *
 * @since   2.0.0
 *
 * @param   string  $default    模板文件名
 * @return  string              Html代码
 */
function tt_page_template_dropdown( $default = '' ) {
    $templates = tt_get_page_templates( get_post() );
    ksort( $templates );
    foreach ( array_keys( $templates ) as $template ) {
        $full_path = 'core/templates/' . $templates[ $template ];
        $selected = selected( $default, $full_path, false );
        echo "\n\t<option value='" . $full_path . "' $selected>$template</option>";
    }
}


/**
 * 保存页面时，保存模板的选择值
 *
 * @since   2.0.0
 * @param   int     $post_id    即将保存的文章ID
 * @return  void
 */
function tt_save_meta_box_page_template_data( $post_id ) {
    $post_id = intval($post_id);
    // 检查是否自动保存，自动保存则跳出
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // 检查用户权限
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }
    // 是否页面
    if ( !isset( $_POST['post_type'] ) || 'page' != $_POST['post_type'] ) {
        return;
    }

    if ( ! empty( $_POST['tt_page_template'] )) {
        $template = sanitize_text_field($_POST['tt_page_template']);
        $post = get_post($post_id);
        $post->page_template = $template;
        $page_templates = array_flip(tt_get_page_templates( $post ));
        if ( 'default' != $template && ! isset( $page_templates[ basename($template) ] ) ) {
            if ( tt_get_option('tt_theme_debug', false) ) {
                wp_die(__('The page template is invalid', 'tt'), __('Invalid Page Template', 'tt'));
            }
            update_post_meta( $post_id, '_wp_page_template', 'default' );
        } else {
            update_post_meta( $post_id, '_wp_page_template', $template );
        }
    }
}
add_action( 'save_post', 'tt_save_meta_box_page_template_data' );