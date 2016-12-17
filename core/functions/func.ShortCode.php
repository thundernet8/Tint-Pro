<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/08 21:53
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

// Toggle content
function tt_sc_toggle_content($atts, $content = null){
    $content = do_shortcode($content);
    extract(shortcode_atts(array('hide'=>'no','title'=>'','color'=>''), $atts));
    if($hide=='no'){
        return '<div class="toggle-wrap"><div class="toggle-click-btn ' . $hide . '" style="color:' . $color . '">' . $title . '</div><div class="toggle-content">' . $content . '</div></div>';
    }else{
        return '<div class="toggle-wrap"><div class="toggle-click-btn ' . $hide . '" style="color:' . $color . '">' . $title . '</div><div class="toggle-content" style="display:none;">' . $content . '</div></div>';
    }
}
add_shortcode('toggle', 'tt_sc_toggle_content');

// 插入商品短代码
function tt_sc_product($atts, $content = null){
    extract(shortcode_atts(array('id'=>''), $atts));
    if(!empty($id)) {
        $vm = EmbedProductVM::getInstance(intval($id));
        $data = $vm->modelData;
        if(!isset($data->product_id)) return $id;
        $templates = new League\Plates\Engine(THEME_TPL . '/plates');
        $args = array(
            'thumb' => $data->product_thumb,
            'link' => $data->product_link,
            'name' => $data->product_name,
            'price' => $data->product_price,
            'currency' => $data->product_currency,
            'rating_value' => ($data->product_rating)['value'],
            'rating_count' => ($data->product_rating)['count'],

        );
        return $templates->render('embed-product', $args);
    }
    return '';
}
add_shortcode('product', 'tt_sc_product');

// Button
function tt_sc_button($atts, $content = null){
    extract(shortcode_atts(array('class'=>'default','size'=>'default','href'=>'','title'=>''), $atts));
    if(!empty($href)) {
        return '<a class="btnhref" href="' . $href . '" title="' . $title . '" target="_blank"><button type="button" class="btn btn-' . $class .' btn-' . $size . '">' . $content . '</button></a>';
    }else{
        return '<button type="button" class="btn btn-' . $class . ' btn-' . $size . '">' . $content . '</button>';
    }
}
add_shortcode('button', 'tt_sc_button');

// Call out
function tt_sc_infoblock($atts, $content = null){
    $content = do_shortcode($content);
    extract(shortcode_atts(array('class'=>'info','title'=>''), $atts));
    return '<div class="contextual-callout callout-' . $class . '"><h4>' . $title . '</h4><p>' . $content . '</p></div>';
}
add_shortcode('callout', 'tt_sc_infoblock');

// Info bg
function tt_sc_infobg($atts, $content = null){
    $content = do_shortcode($content);
    extract(shortcode_atts(array('class'=>'info','closebtn'=>'no','bgcolor'=>'','color'=>'','showicon'=>'yes','title'=>''), $atts));
    $close_btn = $closebtn=='yes' ? '<span class="infobg-close"><i class="tico tico-close"></i></span>' : '';
    $div_class = $showicon!='no' ? 'contextual-bg bg-' . $class . ' showicon' : 'bg-' . $class . ' contextual';
    $content = $title ? '<h4>' . $title . '</h4><p>' . $content . '</p>' : '<p>' . $content . '</p>';
    return '<div class="' . $div_class . '">' . $close_btn . $content . '</div>';
}
add_shortcode('infobg', 'tt_sc_infobg');

// Login to visual
function tt_sc_l2v( $atts, $content ){
    if( !is_null( $content ) && !is_user_logged_in() ) $content = '<div class="bg-lr2v contextual-bg bg-warning"><i class="fa fa-exclamation"></i>' . __(' 此处内容需要 <span class="user-login">登录</span> 才可见', 'tt') . '</div>';
    return $content;
}
add_shortcode( 'ttl2v', 'tt_sc_l2v' );

// Review to visual
function tt_sc_r2v( $atts, $content ){
    if( !is_null( $content ) ) :
        if(!is_user_logged_in()){
            $content = '<div class="bg-lr2v contextual-bg bg-info"><i class="tico tico-comment"></i>' . __('此处内容需要登录并 <span class="user-login">发表评论</span> 才可见', 'tt') . '</div>';
        }else{
            global $post;
            $user_id = get_current_user_id();
            if( $user_id != $post->post_author && !user_can($user_id,'edit_others_posts') ){
                $comments = get_comments( array('status' => 'approve', 'user_id' => $user_id, 'post_id' => $post->ID, 'count' => true ) );
                if(!$comments) {
                    $content = '<div class="bg-lr2v contextual"><i class="tico tico-comment"></i>' . __('此处内容需要登录并 <a href="#respond">发表评论</a> 才可见' , 'tt'). '</div>';
                }
            }
        }
    endif;
    return $content;
}
add_shortcode( 'ttr2v', 'tt_sc_r2v' );

// Pre tag
function tt_to_pre_tag( $atts, $content ){
    return '<div class="precode clearfix"><pre class="lang:default decode:true " >'.str_replace('#038;','', htmlspecialchars( $content,ENT_COMPAT, 'UTF-8' )).'</pre></div>';
}
add_shortcode( 'php', 'tt_to_pre_tag' );
