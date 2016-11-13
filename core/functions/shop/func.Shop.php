<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/13 18:10
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 创建商品自定义文章类型
 *
 * @since 2.0.0
 * @return void
 */
function tt_create_product_post_type() {
    $shop_slug = tt_get_option('tt_product_archives_slug', 'shop');
    register_post_type( 'product',
        array(
            'labels' => array(
                'name' => _x( 'Products', 'taxonomy general name', 'tt' ),
                'singular_name' => _x( 'Product', 'taxonomy singular name', 'tt' ),
                'add_new' => __( 'Add New', 'tt' ),
                'add_new_item' => __( 'Add New Product', 'tt' ),
                'edit' => __( 'Edit', 'tt' ),
                'edit_item' => __( 'Edit Product', 'tt' ),
                'new_item' => __( 'Add Product', 'tt' ),
                'view' => __( 'View', 'tt' ),
                'all_items' => __( 'All Products', 'tt' ),
                'view_item' => __( 'View Product', 'tt' ),
                'search_items' => __( 'Search Product', 'tt' ),
                'not_found' => __( 'Product not found', 'tt' ),
                'not_found_in_trash' => __( 'Product not found in trash', 'tt' ),
                'parent' => __( 'Parent Product', 'tt' ),
                'menu_name' => __( 'Shop and Products', 'tt' ),
            ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'author', 'editor', 'comments', 'excerpt', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-cart',
            'has_archive' => true,
            'rewrite'	=> array('slug'=>$shop_slug)
        )
    );
}
add_action( 'init', 'tt_create_product_post_type' );


/**
 * 为商品启用单独模板
 *
 * @since 2.0.0
 * @param $template_path
 * @return string
 */
function tt_include_shop_template_function( $template_path ) {
    if ( get_post_type() == 'product' ) {
        if ( is_single() ) {
            //指定单个商品模板
            if ( $theme_file = locate_template( array ( 'core/templates/shop/product.php' ) ) ) {
                $template_path = $theme_file;
            }
        }elseif(is_archive()){
            //指定商品分类模板
            if ( $theme_file = locate_template( array ( 'core/templates/shop/product-archives.php' ) ) ) {
                $template_path = $theme_file;
            }
        }
    }
    return $template_path;
}
add_filter( 'template_include', 'tt_include_shop_template_function', 1 );


/**
 * 为商品启用分类和标签
 *
 * @since 2.0.0
 * @return void
 */
function tt_create_product_taxonomies() {
    $shop_slug = tt_get_option('tt_product_archives_slug', 'shop');
    // Categories
    $products_category_labels = array(
        'name' => _x( 'Products Categories', 'taxonomy general name', 'tt' ),
        'singular_name' => _x( 'Products Category', 'taxonomy singular name', 'tt' ),
        'search_items' => __( 'Search Products Categories', 'tt' ),
        'all_items' => __( 'All Products Categories', 'tt' ),
        'parent_item' => __( 'Parent Products Category', 'tt' ),
        'parent_item_colon' => __( 'Parent Products Category:', 'tt' ),
        'edit_item' => __( 'Edit Products Category', 'tt' ),
        'update_item' => __( 'Update Products Category', 'tt' ),
        'add_new_item' => __( 'Add New Products Category', 'tt' ),
        'new_item_name' => __( 'Name of New Products Category', 'tt' ),
        'menu_name' => __( 'Products Categories', 'tt' ),
    );
    register_taxonomy( 'products_category', 'product', array(
        'hierarchical'  => true,
        'labels'        => $products_category_labels,
        'show_ui'       => true,
        'query_var'     => true,
        'rewrite'       => array(
            'slug'          => $shop_slug . '/category',
            'with_front'    => false,
        ),
    ) );
    // Tags
    $products_tag_labels = array(
        'name' => _x( 'Product Tags', 'taxonomy general name', 'tt' ),
        'singular_name' => _x( 'Product Tag', 'taxonomy singular name', 'tt' ),
        'search_items' => __( 'Search Product Tags', 'tt' ),
        'popular_items' => __( 'Popular Product Tags', 'tt' ),
        'all_items' => __( 'All Product Tags', 'tt' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Product Tag', 'tt' ),
        'update_item' => __( 'Update Product Tag', 'tt' ),
        'add_new_item' => __( 'Add New Product Tag', 'tt' ),
        'new_item_name' => __( 'Name of New Product Tag', 'tt' ),
        'separate_items_with_commas' => __( 'Separate Product Tags with Commas', 'tt' ),
        'add_or_remove_items' => __( 'Add or Remove Product Tag', 'tt' ),
        'choose_from_most_used' => __( 'Choose from Most Used Product Tags', 'tt' ),
        'menu_name' => __( 'Product Tags', 'tt' ),
    );

    register_taxonomy('products_tag', 'product', array(
        'hierarchical'  => false,
        'labels'        => $products_tag_labels,
        'show_ui'       => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'     => true,
        'rewrite'       => array(
            'slug' => $shop_slug . '/tag',
            'with_front'    => false,
        ),
    ) );
}
add_action( 'init', 'tt_create_product_taxonomies', 0 );

/**
 * 自定义产品的链接
 *
 * @since 2.0.0
 * @param $link
 * @param object $post
 * @return string|void
 */
function tt_custom_product_link( $link, $post = null ){
    $shop_slug = tt_get_option('tt_product_archives_slug', 'shop');
    $product_slug = tt_get_option('tt_product_link_mode')=='post_name' ? $post->post_name : $post->ID;
    if ( $post->post_type == 'product' ){
        return home_url( $shop_slug . $product_slug . '.html' );
    } else {
        return $link;
    }
}
add_filter('post_type_link', 'tt_custom_product_link', 1, 2);


/**
 * 处理商品自定义链接Rewrite规则
 *
 * @since 2.0.0
 * @return void
 */
function tt_handle_custom_product_rewrite_rules(){
    $shop_slug = tt_get_option('tt_product_archives_slug', 'shop');
    if(tt_get_option('tt_product_link_mode') == 'post_name'):
        add_rewrite_rule(
            $shop_slug . '/([一-龥a-zA-Z0-9_-]+)?.html([\s\S]*)?$',
            'index.php?post_type=product&name=$matches[1]',
            'top' );
    else:
        add_rewrite_rule(
            $shop_slug . '/([0-9]+)?.html([\s\S]*)?$',
            'index.php?post_type=product&p=$matches[1]',
            'top' );
    endif;
}
add_action( 'init', 'tt_handle_custom_product_rewrite_rules' );


/**
 * 后台商品列表信息列
 *
 * @since 2.0.0
 * @param $columns
 * @return array
 */
function tt_product_columns( $columns ) {
    $columns['product_ID'] = __('Product ID', 'tt');
    $columns['product_price'] = __('Price', 'tt');
    $columns['product_quantity'] = __('Quantities', 'tt');
    $columns['product_sales'] = __('Sales', 'tt');
    unset( $columns['comments'] );
    if(isset($columns['title'])) {
        $columns['title'] = __('Product Name', 'tt');
    }
    if(isset($columns['author'])) {
        $columns['author'] = __('Publisher', 'tt');
    }
    if(isset($columns['views'])) {
        $columns['views'] = __('Hot Hits', 'tt');
    }

    // TODO thumbnail(qiniu plugin)
    return $columns;
}
add_filter( 'manage_edit-product_columns', 'tt_product_columns' );
function tt_populate_product_columns( $column ) {
    if ( 'product_ID' == $column ) {
        $product_ID = esc_html( get_the_ID() );
        echo $product_ID;
    }
    elseif ( 'product_price' == $column ) {
        $product_price = get_post_meta( get_the_ID(), 'tt_product_price', true ) ? : '0.00';
        $currency = get_post_meta( get_the_ID(), 'tt_pay_currency', true );
        if($currency==0){
            $text= __('Credit', 'tt');
        }else{
            $text = __('RMB YUAN', 'tt');
        }
        $price = $product_price . ' ' . $text;
        echo $price;
    }elseif( 'product_quantity' == $column ){
        $product_quantity = get_post_meta( get_the_ID(), 'tt_product_amount', true ) ? : 0;
        echo $product_quantity . ' ' . __('pieces', 'tt');
    }elseif( 'product_sales' == $column ){
        $product_sales = get_post_meta( get_the_ID(), 'tt_product_sales', true ) ? : 0;
        echo $product_sales . ' ' . __('pieces', 'tt');
    }
}
add_action( 'manage_posts_custom_column', 'tt_populate_product_columns' );


/**
 * 后台商品列表信息列排序
 *
 * @param $columns
 * @return mixed
 */
function tt_sort_product_columns($columns){
    $columns['product_ID'] = __('Product ID', 'tt');
    $columns['product_price'] = __('Price', 'tt');
    $columns['product_quantity'] = __('Quantities', 'tt');
    $columns['product_sales'] = __('Sales', 'tt');
    return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'tt_sort_product_columns');
function tt_product_column_orderby($vars){
    if(!is_admin())
        return $vars;
    if(isset($vars['orderby'])&&'product_price'==$vars['orderby']){
        $vars = array_merge($vars, array('meta_key'=>'tt_product_price', 'orderby'=>'meta_value'));
    }elseif(isset($vars['orderby'])&&'product_quantity'==$vars['orderby']){
        $vars = array_merge($vars, array('meta_key'=>'tt_product_quantity', 'orderby'=>'meta_value')); //Note v1中postmeta 中使用的是product_amount, 而筛选使用的是product_quantity, 导致筛选无效
    }elseif(isset($vars['orderby'])&&'product_sales'==$vars['orderby']){
        $vars = array_merge($vars, array('meta_key'=>'tt_product_sales', 'orderby'=>'meta_value'));
    }
    return $vars;
}
add_filter('request','tt_product_column_orderby');


/**
 * 后台商品列表分类筛选
 *
 * @since 2.0.0
 * @return void
 */
function tt_filter_products_list() {
    $screen = get_current_screen();
    global $wp_query;
    if ( $screen->post_type == 'store' ) {
        wp_dropdown_categories( array(
            'show_option_all' => __('Show all categories', 'tt'),
            'taxonomy' => 'products_category',
            'name' => __('Product Category'),
            'id' => 'filter-by-products_category',
            'orderby' => 'name',
            'selected' => ( isset( $wp_query->query['products_category'] ) ? $wp_query->query['products_category'] : '' ),
            'hierarchical' => false,
            'depth' => 3,
            'show_count' => false,
            'hide_empty' => true,
        ) );
    }
}
add_action( 'restrict_manage_posts', 'tt_filter_products_list' );
function tt_perform_products_filtering( $query ) {
    $qv = &$query->query_vars;
    if ( isset( $qv['products_category'] ) && is_numeric( $qv['products_category'] ) ) {
        $term = get_term_by( 'id', $qv['products_category'], 'products_category' );
        $qv['products_category'] = $term->slug;
    }
    return $query;
}
add_filter( 'parse_query','tt_perform_products_filtering' );


