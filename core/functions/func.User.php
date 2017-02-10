<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/24 21:19
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 获取用户权限描述字符
 *
 * @since 2.0.0
 * @param $user_id
 * @return string
 */
function tt_get_user_cap_string ($user_id) {
    if(user_can($user_id,'install_plugins')) {
        return __('Site Manager', 'tt');
    }
    if(user_can($user_id,'edit_others_posts')) {
        return __('Editor', 'tt');
    }
    if(user_can($user_id,'publish_posts')) {
        return __('Author', 'tt');
    }
    if(user_can($user_id,'edit_posts')) {
        return __('Contributor', 'tt');
    }
    return __('Reader', 'tt');
}


/**
 * 获取用户的封面
 *
 * @since 2.0.0
 * @param $user_id
 * @param $size
 * @param $default
 * @return string
 */
function tt_get_user_cover ($user_id, $size = 'full', $default = '') {
    if(!in_array($size, array('full', 'mini'))) {
        $size = 'full';
    }
    if($cover = get_user_meta($user_id, 'tt_user_cover', true)) {
        return $cover; // TODO size
    }
    return $default ? $default : THEME_ASSET . '/img/user-default-cover-' . $size . '.jpg';
}


/**
 * 获取用户正在关注的人数
 *
 * @since 2.0.0
 * @param $user_id
 * @return int
 */
function tt_count_user_following ($user_id) {
    return tt_count_following($user_id);
}

/**
 * 获取用户的粉丝数量
 *
 * @since 2.0.0
 * @param $user_id
 * @return int
 */
function tt_count_user_followers ($user_id) {
    return tt_count_followers($user_id);
}


/**
 * 获取作者的文章被浏览总数
 *
 * @since 2.0.0
 * @param $user_id
 * @param $view_key
 * @return int
 */
function tt_count_author_posts_views ($user_id, $view_key = 'views') {
    global $wpdb;
    $sql = $wpdb->prepare("SELECT SUM(meta_value) FROM $wpdb->postmeta RIGHT JOIN $wpdb->posts ON $wpdb->postmeta.meta_key='%s' AND $wpdb->posts.post_author=%d AND $wpdb->postmeta.post_id=$wpdb->posts.ID", $view_key, $user_id);
    $count = $wpdb->get_var($sql);

    return $count;
}


/**
 * 统计某个作者的文章被赞的总次数
 *
 * @since 2.0.0
 * @param $user_id
 * @return null|string
 */
function tt_count_author_posts_stars ($user_id) {
    global $wpdb;
    $sql = $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta  WHERE meta_key='%s' AND post_id IN (SELECT ID FROM $wpdb->posts WHERE post_author=%d)", 'tt_post_star_users', $user_id);
    $count = $wpdb->get_var($sql);

    return $count;
}


/**
 * 获取用户点赞的所有文章ID
 *
 * @since 2.0.0
 * @param $user_id
 * @return array
 */
function tt_get_user_star_post_ids ($user_id) {
    global $wpdb;
    $sql = $wpdb->prepare("SELECT `post_id` FROM $wpdb->postmeta  WHERE `meta_key`='%s' AND `meta_value`=%d", 'tt_post_star_users', $user_id);
    $results = $wpdb->get_results($sql);
    //ARRAY_A -> array(3) { [0]=> array(1) { [0]=> string(4) "1420" } [1]=> array(1) { [0]=> string(3) "242" } [2]=> array(1) { [0]=> string(4) "1545" } }
    //OBJECT -> array(3) { [0]=> object(stdClass)#3862 (1) { ["post_id"]=> string(4) "1420" } [1]=> object(stdClass)#3863 (1) { ["post_id"]=> string(3) "242" } [2]=> object(stdClass)#3864 (1) { ["post_id"]=> string(4) "1545" } }
    $ids = array();
    foreach ($results as $result) {
        $ids[] = intval($result->post_id);
    }
    $ids = array_unique($ids);
    rsort($ids); //从大到小排序
    return $ids;
}


/**
 * 统计用户点赞(收藏)的文章数
 *
 * @since 2.0.0
 * @param $user_id
 * @return int
 */
function tt_count_user_star_posts($user_id) {
    return count(tt_get_user_star_post_ids($user_id));
}


/**
 * 获取一定数量特定角色用户
 *
 * @since 2.0.0
 * @param $role
 * @param $offset
 * @param $limit
 * @return array
 */
function tt_get_users_with_role ($role, $offset = 0, $limit = 20) {
    // TODO $role 过滤
    $user_query = new WP_User_Query(
        array(
            'role' => $role,
            'orderby' => 'ID',
            'order' => 'ASC',
            'number' => $limit,
            'offset' => $offset
        )
    );
    $users = $user_query->get_results();
    if (!empty($users)) {
        return $users;
    }
    return array();
}


/**
 * 获取管理员用户的ID
 *
 * @since 2.0.0
 * @return array
 */
function tt_get_administrator_ids () {
    $ids = array();
    $administrators = tt_get_users_with_role('Administrator');
    foreach ($administrators as $administrator) {
        $ids[] = $administrator->ID;
    }
    return $ids;
}


/**
 * 获取用户私信对话地址
 *
 * @since 2.0.0
 * @param $user_id
 * @return string
 */
function tt_get_user_chat_url($user_id) {
    return get_author_posts_url($user_id) . '/chat';
}


/**
 * 将用户的资料编辑页面链接改至前台
 *
 * @since 2.0.0
 * @param $url
 * @return mixed
 */
function tt_custom_profile_edit_link( $url ) {
    return is_admin() ? $url : tt_url_for('my_settings');
}
add_filter( 'edit_profile_url', 'tt_custom_profile_edit_link' );


/**
 * 将普通用户的文章编辑链接改至前台
 *
 * @since 2.0.0
 * @param $url
 * @param $post_id
 * @return string
 */
function tt_frontend_edit_post_link($url, $post_id){
    if( !current_user_can('edit_users') ){
        $url = add_query_arg(array('id'=>$post_id), tt_url_for('new_post'));
    }
    return $url;
}
add_filter('get_edit_post_link', 'tt_frontend_edit_post_link', 10, 2);


/**
 * 拒绝普通用户访问后台
 *
 * @since 2.0.0
 * @return void
 */
function tt_redirect_wp_admin(){
    if( is_admin() && is_user_logged_in() && !current_user_can('edit_users') && ( !defined('DOING_AJAX') || !DOING_AJAX )  ){
        wp_redirect( tt_url_for('my_settings') );
        exit;
    }
}
add_action( 'init', 'tt_redirect_wp_admin' );


/**
 * 记录用户登录时间、IP等信息
 *
 * @since 2.0.0
 * @param $login
 * @param $user
 */
function tt_update_user_latest_login( $login, $user ) {
    if(!$user) $user = get_user_by( 'login', $login );
    $latest_login = get_user_meta( $user->ID, 'tt_latest_login', true );
    $latest_login_ip = get_user_meta( $user->ID, 'tt_latest_login_ip', true );
    update_user_meta( $user->ID, 'tt_latest_login_before', $latest_login );
    update_user_meta( $user->ID, 'tt_latest_login', current_time( 'mysql' ) );
    update_user_meta( $user->ID, 'tt_latest_ip_before', $latest_login_ip );
    update_user_meta( $user->ID, 'tt_latest_login_ip', $_SERVER['REMOTE_ADDR'] );
}
add_action( 'wp_login', 'tt_update_user_latest_login', 10, 2 );


/**
 * 获取用户的真实IP
 *
 * @since 2.0.0
 * @return void
 */
function tt_get_true_ip(){
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realIP = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);
            $realIP = $realIP[0];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realIP = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realIP = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realIP = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realIP = getenv("HTTP_CLIENT_IP");
        } else {
            $realIP = getenv("REMOTE_ADDR");
        }
    }
    $_SERVER['REMOTE_ADDR'] = $realIP;
}
add_action( 'init', 'tt_get_true_ip' );


/**
 * 对封禁账户处理
 *
 * @since   2.0.0
 * @return  void
 */
function tt_handle_banned_user(){
    if($user_id = get_current_user_id()) { //TODO 忽略管理员
        $ban_status = get_user_meta($user_id, 'tt_banned', true);
        if($ban_status) {
            wp_die(sprintf(__('Your account is banned for reason: %s', 'tt'), get_user_meta($user_id, 'tt_banned_reason', true)), __('Account Banned', 'tt'), 404); //TODO add banned time
        }
    }
}
add_action('template_redirect', 'tt_handle_banned_user');


/**
 * 获取用户账户状态
 *
 * @since 2.0.0
 * @param $user_id
 * @param $return
 * @return array|bool
 */
function tt_get_account_status($user_id, $return = 'bool') {
    $ban = get_user_meta($user_id, 'tt_banned', true);
    if($ban) {
        if($return == 'bool') {
            return true;
        }
        $reason = get_user_meta($user_id, 'tt_banned_reason', true);
        $time = get_user_meta($user_id, 'tt_banned_time', true);
        return array(
            'banned' => true,
            'banned_reason' => strval($reason),
            'banned_time' => strval($time)
        );
    }
    return $return == 'bool' ? false : array(
        'banned' => false
    );
}


/**
 * 封禁用户
 *
 * @since 2.0.0
 * @param $user_id
 * @param string $reason
 * @param string $return
 * @return array|bool
 */
function tt_ban_user($user_id, $reason = '', $return = 'bool') {
    $user = get_user_by('ID', $user_id);
    if(!$user) {
        return $return == 'bool' ? false : array(
            'success' => false,
            'message' => __('The specified user is not existed', 'tt')
        );
    }
    if(update_user_meta($user_id, 'tt_banned', 1)) {
        update_user_meta($user_id, 'tt_banned_reason', $reason);
        update_user_meta($user_id, 'tt_banned_time', current_time('mysql'));
        // 清理Profile缓存
        // tt_clear_cache_key_like('tt_cache_daily_vm_UCProfileVM');
        tt_clear_cache_by_key('tt_cache_daily_vm_UCProfileVM_author' . $user_id);

        return $return == 'bool' ? true : array(
            'success' => true,
            'message' => __('The specified user is banned', 'tt')
        );
    }
    return $return == 'bool' ? false : array(
        'success' => false,
        'message' => __('Error occurs when banning the user', 'tt')
    );
}


/**
 * 解禁用户
 *
 * @since 2.0.0
 * @param $user_id
 * @param string $return
 * @return array|bool
 */
function tt_unban_user($user_id, $return = 'bool') {
    $user = get_user_by('ID', $user_id);
    if(!$user) {
        return $return == 'bool' ? false : array(
            'success' => false,
            'message' => __('The specified user is not existed', 'tt')
        );
    }
    if(update_user_meta($user_id, 'tt_banned', 0)) {
        //update_user_meta($user_id, 'tt_banned_reason', '');
        //update_user_meta($user_id, 'tt_banned_time', '');
        // 清理Profile缓存
        // tt_clear_cache_key_like('tt_cache_daily_vm_UCProfileVM');
        tt_clear_cache_by_key('tt_cache_daily_vm_UCProfileVM_author' . $user_id);
        return $return == 'bool' ? true : array(
            'success' => true,
            'message' => __('The specified user is unlocked', 'tt')
        );
    }
    return $return == 'bool' ? false : array(
        'success' => false,
        'message' => __('Error occurs when unlock the user', 'tt')
    );
}


/**
 * 输出UC小工具中登录后的内容
 *
 * @since 2.0.0
 * @return void
 */
function tt_uc_widget_content() {
    $user = wp_get_current_user();
    ?>
    <li class="login-info"><img class="avatar" src="<?php echo tt_get_avatar($user->ID); ?>"><span><?php printf(__('Log User <a href="%1$s">%2$s</a>', 'tt'), tt_url_for('my_settings'), $user->display_name); ?></span><span><?php printf(__('<a href="%1$s" title="Log Out">Log Out &raquo;</a>', 'tt'), tt_signout_url()); ?></span></li>
    <?php if(!filter_var($user->user_email, FILTER_VALIDATE_EMAIL)) { ?>
    <li><?php printf(__('<a href="%1$s#securityInfo">Please add correct email for safety of your account.</a>', 'tt'), tt_url_for('my_settings')); ?></li>
    <?php } ?>
    <?php
    $links = array();
    $links[] = array(
        'title' => __('My HomePage', 'tt'),
        'url' => get_author_posts_url($user->ID)
    );
    if( current_user_can( 'manage_options' ) ) {
		$links[] = array(
			'title' => __('Manage Dashboard', 'tt'),
			'url' => admin_url()
		);
	}
    $links[] = array(
        'title' => __('Add New Post', 'tt'),
        'url' => tt_url_for('new_post')
    );
    ?>
    <li class="active">
    <?php foreach($links as $link) { ?>
    <a href="<?php echo $link['url']; ?>"><?php echo $link['url'] . ' &raquo;'; ?></a>
    <?php } ?>
    </li>
    <?php
    $credit = tt_get_user_credit($user->ID);
    $credit_void = tt_get_user_consumed_credit($user->ID);
    $unread_count = tt_count_messages('chat', 0);
    $stared_count = tt_count_user_star_posts($user->ID);

    $statistic_info = array(
		array(
			'title' => __('Posts', 'tt'),
            'url' => tt_url_for('uc_latest', $user->ID),
			'count' => count_user_posts($user->ID)
		),
		array(
			'title' => __('Comments', 'tt'),
			'url' => tt_url_for('uc_comments', $user->ID),
			'count' => get_comments( array('status' => '1', 'user_id'=>$user->ID, 'count' => true) )
		),
		array(
			'title' => __('Stars', 'tt'),
			'url' => tt_url_for('uc_stars', $user->ID),
			'count' => $stared_count
		),
	);
    if($unread_count) {
        $statistic_info[] = array(
            'title' => __('Unread Messages', 'tt'),
            'url' => tt_url_for('in_msg'),
            'count' => $unread_count
        );
    }
    $statistic_info[] = array(
        'title' => __('Credits', 'tt'),
        'url' => tt_url_for('my_credits'),
        'count' => $credit
    );
    ?>
    <li>
    <?php
    foreach ($statistic_info as $info_item) { ?>
    <span><?php printf('%1$s<a href="%2$s">%3$s</a>', $info_item['title'], $info_item['url'], $info_item['count']); ?></span>
    <?php } ?>
    <?php echo tt_daily_sign_anchor($user->ID); ?>
    </li>
    <li>
		<div class="input-group">
			<span class="input-group-addon"><?php _e('Ref url for this page', 'tt'); ?></span>
			<input class="tin_aff_url form-control" type="text" class="form-control" value="<?php echo add_query_arg('ref', $current_user->ID, Utils::getPHPCurrentUrl()); ?>">
		</div>
	</li>
    <?php
}


/**
 * 站内信欢迎新注册用户并通知完善账号信息
 *
 * @since 2.0.4
 * @param $user_id
 * @return void
 */
function tt_welcome_for_new_registering($user_id){
    $blog_name = get_bloginfo('name');
    //tt_create_message($user_id, 0, 'System', 'notification', sprintf( __('欢迎来到%1$s, 请首先在个人设置中完善您的账号信息, 如邮件地址是必需的', 'tt'), $blog_name ), '', 0, 'publish');
    tt_create_pm($user_id, $blog_name, sprintf( __('欢迎来到%1$s, 请首先在个人设置中完善您的账号信息, 如邮件地址是必需的', 'tt'), $blog_name), true);
}
add_action('user_register', 'tt_welcome_for_new_registering');