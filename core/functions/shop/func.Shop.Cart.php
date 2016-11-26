<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/22 22:37
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 获取购物车内容
 *
 * @since 2.0.0
 * @param int $user_id
 * @param bool $rest
 * @return array|bool|WP_Error
 */
function tt_get_cart($user_id = 0, $rest = false) {
    if(!$user_id) {
        $user_id = get_current_user_id();
    }
    if(!$user_id) {
        return $rest ? new WP_Error('forbidden_anonymous_request', __('You need sign in to view the shopping cart', 'tt'), 401) : false;
    }
    $meta = get_user_meta($user_id, 'tt_shopping_cart', true);
    if(!$meta) {
        return array();
    }
    $cart_items = maybe_unserialize($meta); // $cart_item{id:xxx,name:xxx,price:xxx,quantity:xxx,date:xxx}
    return $cart_items;
}


/**
 * 新增内容更新购物车
 *
 * @param $product_id
 * @param int $quantity
 * @param bool $rest
 * @return array|bool|mixed|WP_Error
 */
function tt_add_cart($product_id, $quantity = 1, $rest = false) {
    $user_id = get_current_user_id();
    if(!$user_id) {
        return $rest ? new WP_Error('forbidden_anonymous_request', __('You need sign in to update shopping cart', 'tt'), 401) : false;
    }
    $meta = get_user_meta($user_id, 'tt_shopping_cart', true);
    $items = $meta ? maybe_unserialize($meta) : array();
    $old_quantity = 0;
    foreach ($items as $key=>$item) {
        if($item['id'] == $product_id) {
            $old_quantity = intval($item['quantity']);
            array_splice($items, $key, 1);
        }
    }

    $product = get_post($product_id);
    if(!$product || intval(get_post_meta($product_id, 'tt_product_quantity', true)) < $quantity){ //TODO
        return $rest ? new WP_Error('product_not_found', __('The product you are adding to cart is not found or available', 'tt'), 404) : false;
    }

    $add = array(
        'id' => $product->ID,
        'name' => $product->post_title,
        'price' => sprintf('%0.2f', get_post_meta($product->ID, 'tt_product_price', true)),
        'quantity' => $old_quantity + $quantity,
        'thumb' => tt_get_thumb($product, array(
            'width' => 100,
            'height' => 100,
            'str' => 'thumbnail'
        )),
        'permalink' => get_permalink($product),
        'time' => time()
    );

    array_push($items, $add);

    $update = update_user_meta($user_id, 'tt_shopping_cart', maybe_serialize($items));
    return $items;
}


/**
 * 删除购物车内容
 *
 * @since 2.0.0
 * @param $product_id
 * @param int $minus_quantity
 * @param bool $rest
 * @return array|bool|mixed|WP_Error
 */
function tt_delete_cart($product_id, $minus_quantity = 1, $rest = false) {
    $user_id = get_current_user_id();
    if(!$user_id) {
        return $rest ? new WP_Error('forbidden_anonymous_request', __('You need sign in to update shopping cart', 'tt'), 401) : false;
    }
    $meta = get_user_meta($user_id, 'tt_shopping_cart', true);
    $items = $meta ? maybe_unserialize($meta) : array();
    $new_quantity = 0;
    foreach ($items as $key=>$item) {
        if($item['id'] == $product_id) {
            $old_quantity = intval($item['quantity']);
            $new_quantity = $old_quantity - $minus_quantity;
            array_splice($items, $key, 1);
        }
    }

    if($new_quantity > 0){
        $product = get_post($product_id);
        if(!$product){
            return $rest ? new WP_Error('product_not_found', __('The product you are adding to cart is not found or available', 'tt'), 404) : false;
        }
        $add = array(
            'id' => $product->ID,
            'name' => $product->post_title,
            'price' => sprintf('%0.2f', get_post_meta($product->ID, 'tt_product_price', true)),
            'quantity' => $new_quantity,
            'thumb' => tt_get_thumb($product, array(
                'width' => 100,
                'height' => 100,
                'str' => 'thumbnail'
            )),
            'permalink' => get_permalink($product),
            'time' => time()
        );
        array_push($items, $add);
    }

    $update = update_user_meta($user_id, 'tt_shopping_cart', maybe_serialize($items));

    return $items;
}


/**
 * 清空购物车
 *
 * @since 2.0.0
 * @param bool $rest
 * @return array|bool|WP_Error
 */
function tt_clear_cart($rest = false) {
    $user_id = get_current_user_id();
    if(!$user_id) {
        return $rest ? new WP_Error('forbidden_anonymous_request', __('You need sign in to update shopping cart', 'tt'), 401) : false;
    }

    $update = update_user_meta($user_id, 'tt_shopping_cart', '');

    return $rest ? array() : true;
}