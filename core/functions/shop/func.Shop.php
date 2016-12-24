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
            if ( $theme_file = locate_template( array ( 'core/templates/shop/tpl.Product.php' ) ) ) {
                $template_path = $theme_file;
            }
        }elseif(tt_is_product_category()){
            //指定商品分类模板
            if ( $theme_file = locate_template( array ( 'core/templates/shop/tpl.Product.Category.php' ) ) ) {
                $template_path = $theme_file;
            }
        }elseif(tt_is_product_tag()){
            //指定商品标签模板
            if ( $theme_file = locate_template( array ( 'core/templates/shop/tpl.Product.Tag.php' ) ) ) {
                $template_path = $theme_file;
            }
        }elseif(is_archive()){
            //指定商店首页模板
            if ( $theme_file = locate_template( array ( 'core/templates/shop/tpl.Product.Archive.php' ) ) ) {
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
    $product_category_labels = array(
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
    register_taxonomy( 'product_category', 'product', array(
        'hierarchical'  => true,
        'labels'        => $product_category_labels,
        'show_ui'       => true,
        'query_var'     => true,
        'rewrite'       => array(
            'slug'          => $shop_slug . '/category',
            'with_front'    => false,
        ),
    ) );
    // Tags
    $product_tag_labels = array(
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

    register_taxonomy('product_tag', 'product', array(
        'hierarchical'  => false,
        'labels'        => $product_tag_labels,
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
        return home_url( $shop_slug . '/' . $product_slug . '.html' );
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


/**
 * 统计不同支付和价格类型的商品数量
 *
 * @since 2.0.0
 * @param $type
 * @return int
 */
function tt_count_products_by_price_type($type = 'free') {
    switch ($type) {
        case 'free':
            $query = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'tt_product_price',
                        'value' => 0.01,
                        'compare' => '<'
                    )
                )
            ));
            return absint($query->found_posts);
            break;
        case 'credit':
            $query = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'tt_pay_currency',
                        'value' => 0,
                        'compare' => '='
                    ),
                    array(
                        'key' => 'tt_product_price',
                        'value' => '0.00',
                        'compare' => '>'
                    )
                )
            ));
            return absint($query->found_posts);
            break;
        case 'cash':
            $query = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'tt_pay_currency',
                        'value' => 1,
                        'compare' => '='
                    ),

                    array(
                        'key' => 'tt_product_price',
                        'value' => '0.00',
                        'compare' => '>'
                    )
                )
            ));
            return absint($query->found_posts);
            break;
        default:
            return 1;
    }
}


/**
 * 判断当前页面是否为商品分类
 *
 * @since 2.0.0
 * @return bool
 */
function tt_is_product_category() {
    $object =get_queried_object();
    if($object instanceof WP_Term && $object->taxonomy == 'product_category') {
        return true;
    }
    return false;
}


/**
 * 判断当前页面是否为商品标签
 *
 * @since 2.0.0
 * @return bool
 */
function tt_is_product_tag() {
    $object =get_queried_object();
    if($object instanceof WP_Term && $object->taxonomy == 'product_tag') {
        return true;
    }
    return false;
}


/**
 * 检查用户是否购买了某产品(只考虑交易成功的)
 *
 * @since 2.0.0
 * @param $product_id
 * @param int $user_id
 * @return bool
 */
function tt_check_user_has_buy_product($product_id, $user_id = 0) {
    $the_orders = tt_get_specified_user_and_product_orders($product_id, $user_id);
    if($the_orders){
        return false;
    }
    foreach ($the_orders as $the_order){
        if($the_order->order_status == OrderStatus::TRADE_SUCCESS){
            return true;
        }
    }
    return false;
}


/**
 * 检查某商品对某用户的实际价格(会员优惠、全站折扣等)
 *
 * @since 2.0.0
 * @param $product_id
 * @param int $user_id
 * @return double|int
 */
function tt_get_specified_user_product_price($product_id, $user_id = 0) {
    $currency = get_post_meta( $product_id, 'tt_pay_currency', true) ? 'cash' : 'credit';
    $price = $currency == 'cash' ? sprintf('%0.2f', get_post_meta($product_id, 'tt_product_price', true)) : (int)get_post_meta($product_id, 'tt_product_price', true);

    $discount = tt_get_product_discount_array($product_id); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣

    $user_id = $user_id ? : get_current_user_id();
    if(!$user_id) {
        return $currency == 'cash' ? sprintf('%0.2f', $price * absint($discount[0]) / 100) : intval($price * absint($discount[0]) / 100);
    }

    // 会员等级价格
    $member = new Member($user_id);
    if($member->is_permanent_vip()) {
        return $currency == 'cash' ? sprintf('%0.2f', $price * absint($discount[3]) / 100) : intval($price * absint($discount[3]) / 100);
    }elseif($member->is_annual_vip()){
        return $currency == 'cash' ? sprintf('%0.2f', $price * absint($discount[2]) / 100) : intval($price * absint($discount[2]) / 100);
    }elseif($member->is_monthly_vip()){
        return $currency == 'cash' ? sprintf('%0.2f', $price * absint($discount[1]) / 100) : intval($price * absint($discount[1]) / 100);
    }
    return $currency == 'cash' ? sprintf('%0.2f', $price * absint($discount[0]) / 100) : intval($price * absint($discount[0]) / 100);
}


/**
 * 获取商品中的下载链接
 *
 * @since 2.0.0
 * @param $product_id
 * @param $html
 * @return string
 */
function tt_get_product_download_content($product_id, $html = true){
    $content = '';
    $array_content = array();
    $dl_links = get_post_meta($product_id, 'tt_product_download_links', true);
    if(!empty($dl_links)):
        $dl_links = explode(PHP_EOL, $dl_links);
        foreach($dl_links as $dl_link){
            $dl_info = explode('|', $dl_link);
            $dl_info[0] = isset($dl_info[0]) ? $dl_info[0] : '';
            $dl_info[1] = isset($dl_info[1]) ? $dl_info[1] : '';
            $dl_info[2] = isset($dl_info[2]) ? $dl_info[2] : __('None', 'tt');
            $content .= sprintf(__('<li><p>%1$s</p><p>下载链接：<a href="%2$s" title="%1$s" target="_blank">%2$s</a>下载密码：%3$s</p></li>', 'tt'), $dl_info[0], $dl_info[1], $dl_info[2]);
            $array_content[] = array(
                'name' => $dl_info[0],
                'link' => $dl_info[1],
                'password' => $dl_info[2]
            );
        }
    endif;
    return $html ? $content : $array_content;
}


/**
 * 获取商品付费内容(包含下载链接和付费可见内容)
 *
 * @since 2.0.0
 * @param $product_id
 * @param bool $html 是否输出HTML
 * @return string
 */
function tt_get_product_pay_content($product_id, $html = true){
    $user_id = get_current_user_id();

    $price = tt_get_specified_user_product_price($product_id, $user_id);
    $show = $price < 0.01 && tt_check_user_has_buy_product($product_id, $user_id);
    if(!$show) {
        return $html ? __('<div class="contextual-bg bg-paycontent"><span><i class="tico tico-paypal">&nbsp;</i>付费内容</span><p>你只有购买支付后才能查看该内容！</p></div>', 'tt') : null;
    }

    $pay_content = get_post_meta($product_id, 'tt_product_pay_content', true);
    $download_content = tt_get_product_download_content($product_id, $html);

    return $html ? sprintf(__('<div class="contextual-bg bg-paycontent"><span><i class="tico tico-paypal">&nbsp;</i>付费内容</span><p>%1$s</p><p>%2$s</p></div>', 'tt'), $download_content, $pay_content) : array('download_content' => $download_content, 'pay_content' => $pay_content);
}


/**
 * 获取商品折扣数组
 *
 * @since 2.0.0
 * @param $product_id
 * @return array|mixed
 */
function tt_get_product_discount_array($product_id){
    $discount = maybe_unserialize(get_post_meta($product_id, 'tt_product_discount', true));
    if(!is_array($discount)) {
        return array(100, 100, 100, 100);
    }
    $discount[0] = isset($discount[0]) ? min(100, absint($discount[0])) : 100;
    $discount[1] = isset($discount[1]) ? min(100, absint($discount[1])) : $discount[0];
    $discount[2] = isset($discount[2]) ? min(100, absint($discount[2])) : $discount[0];
    $discount[3] = isset($discount[3]) ? min(100, absint($discount[3])) : $discount[0];
    return $discount;
}


/**
 * 获取现金支付的方式
 *
 * @since 2.0.0
 * @return string
 */
function tt_get_cash_pay_method() {
    $pay_method = tt_get_option('tt_pay_channel', 'alipay')=='alipay' && tt_get_option('tt_alipay_email') && tt_get_option('tt_alipay_partner') ? 'alipay' : 'qrcode';
    return $pay_method;
}


/**
 * 获取支付宝支付网关
 *
 * @since 2.0.0
 * @param $order_id
 * @return string
 */
function tt_get_alipay_gateway($order_id) {
    return add_query_arg(array('oid' => $order_id, 'spm' => wp_create_nonce('pay_gateway'), 'channel' => 'alipay'), tt_url_for('paygateway'));
}


/**
 * 获取扫码转账支付网关
 *
 * @since 2.0.0
 * @param $order_id
 * @return string
 */
function tt_get_qrpay_gateway($order_id) {
    return add_query_arg(array('oid' => $order_id), tt_url_for('qrpay'));
}