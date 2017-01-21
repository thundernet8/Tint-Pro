<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/07 20:59
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php
function tt_add_metaboxes() {
    // 嵌入商品
    add_meta_box(
        'tt_post_embed_product',
        __( 'Post Embed Product', 'tt' ),
        'tt_post_embed_product_callback',
        'post',
        'normal','high'
    );
    // 转载信息
    add_meta_box(
        'tt_copyright_content',
        __( 'Post Copyright Info', 'tt' ),
        'tt_post_copyright_callback',
        'post',
        'normal','high'
    );
    // 文章内嵌下载资源
    add_meta_box(
        'tt_dload_metabox',
        __( '普通与积分收费下载', 'tt' ),
        'tt_download_metabox_callback',
        'post',
        'normal','high'
    );
    // 页面关键词与描述
    add_meta_box(
        'tt_keywords_description',
        __( '页面关键词与描述', 'tt' ),
        'tt_keywords_description_callback',
        'page',
        'normal','high'
    );
    // 商品信息输入框
    add_meta_box(
        'tt_product_info',
        __( '商品信息', 'tt' ),
        'tt_product_info_callback',
        'product',
        'normal','high'
    );
}
add_action( 'add_meta_boxes', 'tt_add_metaboxes' );


/**
 * 文章内嵌入商品
 *
 * @since 2.0.0
 * @param $post
 * @return void
 */
function tt_post_embed_product_callback($post) {
    $embed_product = (int)get_post_meta( $post->ID, 'tt_embed_product', true );
    ?>
    <p style="width:100%;">
        <?php _e( 'Embed Product ID', 'tt' );?>
        <input name="tt_embed_product" class="small-text code" value="<?php echo $embed_product;?>" style="width:80px;height: 28px;">
        <?php _e( '(Leave empty or zero to disable)', 'tt' );?>
    </p>
    <?php
}

/**
 * 文章转载信息
 *
 * @since   2.0.0
 * @param   WP_Post    $post
 * @return  void
 */
function tt_post_copyright_callback($post) {
    $cc = get_post_meta( $post->ID, 'tt_post_copyright', true );
    $cc = $cc ? maybe_unserialize($cc) : array('source_title' => '', 'source_link' => '');
    ?>
    <p><?php _e( 'Post Source Title', 'tt' );?></p>
    <textarea name="tt_source_title" rows="1" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($cc['source_title']));?></textarea>
    <p><?php _e( 'Post Source Link, leaving empty means the post is original article', 'tt' );?></p>
    <textarea name="tt_source_link" rows="1" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($cc['source_link']));?></textarea>
    <?php
}


/**
 * 普通与积分下载Metabox
 *
 * @since 2.0.0
 * @param $post
 * @return void
 */
function tt_download_metabox_callback( $post ) {

    //免费下载资源
    $free_dl = get_post_meta( $post->ID, 'tt_free_dl', true ) ? : '';
    //积分下载资源
    $sale_dl = get_post_meta( $post->ID, 'tt_sale_dl', true ) ? : '';

    ?>
    <p><?php _e( '普通下载资源下载方式', 'tt' );?></p>
    <p><?php _e( '普通下载资源，格式为 资源1名称|资源1url|下载密码,资源2名称|资源2url|下载密码 资源名称与url用|隔开，不同资源用英文逗号隔开，url请添加http://头，如提供百度网盘加密下载可以填写密码，也可以留空', 'tt' );?></p>
    <textarea name="tt_free_dl" rows="5" cols="50" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($free_dl));?></textarea>
    <p><?php _e( '积分下载资源，格式为 资源1名称|资源1url|资源1价格|下载密码,资源2名称|资源2url|资源2价格|下载密码 资源名称与url以及价格、下载密码用|隔开，不同资源用英文逗号隔开', 'tt' );?></p>
    <textarea name="tt_sale_dl" rows="5" cols="50" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($sale_dl));?></textarea>

    <?php
}


/**
 * 页面关键词与描述
 *
 * @since 2.0.0
 * @param $post
 * @return void
 */
function tt_keywords_description_callback($post){
    $keywords = get_post_meta( $post->ID, 'tt_keywords', true );
    $description = get_post_meta($post->ID, 'tt_description', true);
    ?>
    <p><?php _e( '页面关键词', 'tt' );?></p>
    <textarea name="tt_keywords" rows="2" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($keywords));?></textarea>
    <p><?php _e( '页面描述', 'tt' );?></p>
    <textarea name="tt_description" rows="5" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($description));?></textarea>

    <?php
}


/**
 * 商品信息
 *
 * @since 2.0.0
 * @param $post
 * @return void
 */
function tt_product_info_callback($post){
    $currency = get_post_meta($post->ID, 'tt_pay_currency', true); // 0 - credit 1 - cash
    $channel = get_post_meta($post->ID, 'tt_buy_channel', true) == 'taobao' ? 'taobao' : 'instation';
    $price = get_post_meta($post->ID, 'tt_product_price', true);
    $amount = get_post_meta($post->ID, 'tt_product_quantity', true);

    // 注意，折扣保存的是百分数的数值部分
    $discount_summary = tt_get_product_discount_array($post->ID); // 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣
    $site_discount = $discount_summary[0];
    $monthly_vip_discount = $discount_summary[1];
    $annual_vip_discount = $discount_summary[2];
    $permanent_vip_discount = $discount_summary[3];

    //$promote_code_support = get_post_meta($post->ID, 'tt_promote_code_support', true) ? (int)get_post_meta($post->ID, 'tt_promote_code_support', true) : 0;
    //$promote_discount = get_post_meta($post->ID,'product_promote_discount',true);
    //$promote_discount = empty($promote_discount) ? 1 : $promote_discount;;
    //$discount_begin_date = get_post_meta($post->ID,'product_discount_begin_date',true);
    //$discount_period = get_post_meta($post->ID,'product_discount_period',true);
    $download_links = get_post_meta($post->ID, 'tt_product_download_links', true);
    $pay_content = get_post_meta($post->ID,'tt_product_pay_content',true);
    ?>
    <p style="clear:both;font-weight:bold;">
        <?php echo sprintf(__('此商品购买按钮快捷插入短代码为[product id="%1$s"][/product]', 'tt'), $post->ID); ?>
    </p>
    <p style="clear:both;font-weight:bold;border-bottom:1px solid #ddd;padding-bottom:8px;">
        <?php _e('基本信息', 'tt'); ?>
    </p>
    <p style="width:20%;float:left;"><?php _e( '选择支付币种', 'tt' );?>
        <select name="tt_pay_currency">
            <option value="0" <?php if( $currency!=1) echo 'selected="selected"';?>><?php _e( '积分', 'tt' );?></option>
            <option value="1" <?php if( $currency==1) echo 'selected="selected"';?>><?php _e( '人民币', 'tt' );?></option>
        </select>
    </p>
    <p style="width:20%;float:left;"><?php _e( '选择购买渠道', 'tt' );?>
        <select name="tt_buy_channel">
            <option value="instation" <?php if( $channel!='taobao') echo 'selected="selected"';?>><?php _e( '站内购买', 'tt' );?></option>
            <option value="taobao" <?php if( $channel=='taobao') echo 'selected="selected"';?>><?php _e( '淘宝链接', 'tt' );?></option>
        </select>
    </p>
    <p style="width:20%;float:left;"><?php _e( '商品售价 ', 'tt' );?>
        <input name="tt_product_price" class="small-text code" value="<?php echo sprintf('%0.2f', $price);?>" style="width:80px;height: 28px;">
    </p>
    <p style="width:20%;float:left;"><?php _e( '商品数量 ', 'tt' );?>
        <input name="tt_product_quantity" class="small-text code" value="<?php echo (int)$amount;?>" style="width:80px;height: 28px;">
    </p>
    <p style="clear:both;font-weight:bold;border-bottom:1px solid #ddd;padding-bottom:8px;">
        <?php _e('VIP会员折扣百分数(100代表原价)', 'tt'); ?>
    </p>
    <p style="width:33%;float:left;clear:left;"><?php _e( 'VIP月费会员折扣 ', 'tt' );?>
        <input name="tt_monthly_vip_discount" class="small-text code" value="<?php echo $monthly_vip_discount; ?>" style="width:80px;height: 28px;"> %
    </p>
    <p style="width:33%;float:left;"><?php _e( 'VIP年费会员折扣 ', 'tt' );?>
        <input name="tt_annual_vip_discount" class="small-text code" value="<?php echo $annual_vip_discount; ?>" style="width:80px;height: 28px;"> %
    </p>
    <p style="width:33%;float:left;"><?php _e( 'VIP永久会员折扣 ', 'tt' );?>
        <input name="tt_permanent_vip_discount" class="small-text code" value="<?php echo $permanent_vip_discount; ?>" style="width:80px;height: 28px;"> %
    </p>
    <p style="clear:both;font-weight:bold;border-bottom:1px solid #ddd;padding-bottom:8px;">
        <?php _e('促销信息', 'tt'); ?>
    </p>
    <p style="width:20%;clear:both;"><?php _e( '优惠促销折扣(100代表原价)', 'tt' );?>
        <input name="tt_product_promote_discount" class="small-text code" value="<?php echo $site_discount; ?>" style="width:80px;height: 28px;"> %
    </p>
    <p style="clear:both;font-weight:bold;border-bottom:1px solid #ddd;padding-bottom:8px;">
        <?php _e('付费内容', 'tt'); ?>
    </p>
    <p style="clear:both;"><?php _e( '付费查看下载链接,一行一个,每个资源格式为资源名|资源下载链接|密码', 'tt' );?></p>
    <textarea name="tt_product_download_links" rows="5" class="large-text code"><?php echo $download_links;?></textarea>
    <p style="clear:both;"><?php _e( '付费查看的内容信息', 'tt' );?></p>
    <textarea name="tt_product_pay_content" rows="5" class="large-text code"><?php echo $pay_content;?></textarea>

    <?php
}


/**
 * 保存文章时保存自定义数据
 *
 * @since 2.0.0
 * @param $post_id
 * @return void
 */
function tt_save_meta_box_data( $post_id ) {
    // 检查安全字段验证
//    if ( ! isset( $_POST['tt_meta_box_nonce'] ) ) {
//        return;
//    }
    // 检查安全字段的值
//    if ( ! wp_verify_nonce( $_POST['tt_meta_box_nonce'], 'tt_meta_box' ) ) {
//        return;
//    }
    // 检查是否自动保存，自动保存则跳出
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // 检查用户权限
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    // 检查和更新字段
    if(isset($_POST['tt_embed_product'])) {
        update_post_meta($post_id, 'tt_embed_product', absint($_POST['tt_embed_product']));
    }

    if(isset($_POST['tt_source_title']) && isset($_POST['tt_source_link'])) {
        $cc = array(
            'source_title' => trim($_POST['tt_source_title']),
            'source_link' => trim($_POST['tt_source_link'])
        );
        update_post_meta($post_id, 'tt_post_copyright', maybe_serialize($cc));
    }

    if(isset($_POST['tt_free_dl']) && !empty($_POST['tt_free_dl'])) {
        update_post_meta($post_id, 'tt_free_dl', trim($_POST['tt_free_dl']));
    }

    if(isset($_POST['tt_sale_dl']) && !empty($_POST['tt_sale_dl'])) {
        update_post_meta($post_id, 'tt_sale_dl', trim($_POST['tt_sale_dl']));
    }

    if(isset($_POST['tt_keywords']) && !empty($_POST['tt_keywords'])) {
        update_post_meta($post_id, 'tt_keywords', trim($_POST['tt_keywords']));
    }

    if(isset($_POST['tt_description']) && !empty($_POST['tt_description'])) {
        update_post_meta($post_id, 'tt_description', trim($_POST['tt_description']));
    }

    if(isset($_POST['tt_pay_currency'])){
        $currency = (int)$_POST['tt_pay_currency'] == 1 ? 1 : 0;
        update_post_meta($post_id, 'tt_pay_currency', $currency);
    }

    if(isset($_POST['tt_buy_channel'])){
        $channel = trim($_POST['tt_buy_channel']) == 'taobao' ? 'taobao' : 'instation';
        update_post_meta($post_id, 'tt_buy_channel', $channel);
    }

    if(isset($_POST['tt_product_price'])){
        update_post_meta($post_id, 'tt_product_price', abs($_POST['tt_product_price']));
    }

    if(isset($_POST['tt_product_quantity'])){
        update_post_meta($post_id, 'tt_product_quantity', absint($_POST['tt_product_quantity']));
    }

    if(isset($_POST['tt_product_promote_discount']) && isset($_POST['tt_monthly_vip_discount']) && isset($_POST['tt_annual_vip_discount']) && isset($_POST['tt_permanent_vip_discount'])) {
        $discount_summary = array(
            absint($_POST['tt_product_promote_discount']),
            absint($_POST['tt_monthly_vip_discount']),
            absint($_POST['tt_annual_vip_discount']),
            absint($_POST['tt_permanent_vip_discount'])
        );
        update_post_meta($post_id, 'tt_product_discount', maybe_serialize($discount_summary));
    }

    if(isset($_POST['tt_product_download_links'])){
        update_post_meta($post_id, 'tt_product_download_links', trim($_POST['tt_product_download_links']));
    }

    if(isset($_POST['tt_product_pay_content'])){
        update_post_meta($post_id, 'tt_product_pay_content', trim($_POST['tt_product_pay_content']));
    }
}
add_action( 'save_post', 'tt_save_meta_box_data' );