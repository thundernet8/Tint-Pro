<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/14 22:05
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_me_vars; ?>
<?php $tt_me_vars['tt_user_id'] = get_current_user_id(); ?>
<?php $tt_me_vars['tt_user'] = get_user_by('ID', $tt_me_vars['tt_user_id']); ?>
<?php $tt_me_vars['tt_paged'] = get_query_var('paged') ? : 1; ?>
<?php global $wp_query; $query_vars=$wp_query->query_vars; $me_tab = isset($query_vars['me_child_route']) && in_array($query_vars['me_child_route'], array_keys((array)json_decode(ALLOWED_ME_ROUTES))) ? $query_vars['me_child_route'] : 'settings'; $tt_me_vars['me_child_route'] = $me_tab; ?>
<aside class="col col-left">
    <nav class="nav clearfix">
        <div class="context-avatar context-avatar-tiny">
            <img class="avatar avatar-tiny" src="<?php echo tt_get_avatar($tt_me_vars['tt_user_id'], 'small'); ?>"><a href="<?php echo tt_url_for('uc_me'); ?>" title="<?php _e('Visit My Homepage', 'tt'); ?>"><?php echo $tt_me_vars['tt_user']->display_name; ?></a>
        </div>
        <ul class="me_tabs">
            <li><a class="<?php echo tt_conditional_class('me_tab settings', $me_tab == 'settings'); ?>" href="<?php echo tt_url_for('my_settings', $tt_me_vars['tt_user_id']); ?>"><?php _e('SETTINGS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('me_tab notifications', $me_tab == 'notifications'); ?>" href="<?php echo tt_url_for('all_notify', $tt_me_vars['tt_user_id']); ?>"><?php _e('NOTIFICATIONS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('me_tab messages', $me_tab == 'messages'); ?>" href="<?php echo tt_url_for('in_msg', $tt_me_vars['tt_user_id']); ?>"><?php _e('MESSAGES', 'tt'); ?></a></li>
            <li><a class="me_tab posts" href="<?php echo tt_url_for('uc_latest', $tt_me_vars['tt_user_id']); ?>" target="_blank"><?php _e('MY POSTS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('me_tab drafts', $me_tab == 'drafts'); ?>" href="<?php echo tt_url_for('my_drafts', $tt_me_vars['tt_user_id']); ?>"><?php _e('MY DRAFTS', 'tt'); ?></a></li>
            <li><a class="me_tab stars" href="<?php echo tt_url_for('uc_stars', $tt_me_vars['tt_user_id']); ?>" target="_blank"><?php _e('MY STARS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('me_tab credits', $me_tab == 'credits'); ?>" href="<?php echo tt_url_for('my_credits', $tt_me_vars['tt_user_id']); ?>"><?php _e('CREDITS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('me_tab membership', $me_tab == 'membership'); ?>" href="<?php echo tt_url_for('my_membership', $tt_me_vars['tt_user_id']); ?>"><?php _e('MEMBERSHIP', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('me_tab orders', $me_tab == 'orders' || $me_tab == 'order'); ?>" href="<?php echo tt_url_for('my_all_orders', $tt_me_vars['tt_user_id']); ?>"><?php _e('ORDERS', 'tt'); ?></a></li>
            <li><a class="me_tab followers" href="<?php echo tt_url_for('uc_followers', $tt_me_vars['tt_user_id']); ?>" target="_blank"><?php _e('MY FOLLOWERS', 'tt'); ?></a></li>
            <li><a class="me_tab following" href="<?php echo tt_url_for('uc_following', $tt_me_vars['tt_user_id']); ?>" target="_blank"><?php _e('MY FOLLOWING', 'tt'); ?></a></li>
        </ul>
    </nav>
</aside>