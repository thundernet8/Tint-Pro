<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 16:44
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_mg_vars; $current_user = wp_get_current_user(); ?>
<?php $tt_mg_vars['tt_user'] = $current_user; ?>
<?php $tt_mg_vars['tt_user_id'] = $current_user->ID; ?>
<?php $tt_mg_vars['tt_paged'] = get_query_var('paged') ? : 1; ?>
<?php global $wp_query; $query_vars=$wp_query->query_vars; $mg_tab = isset($query_vars['manage_child_route']) && in_array($query_vars['manage_child_route'], array_keys((array)json_decode(ALLOWED_MANAGE_ROUTES))) ? $query_vars['manage_child_route'] : 'status'; $tt_mg_vars['manage_child_route'] = $mg_tab; ?>
<aside class="col col-left">
    <nav class="nav clearfix">
        <div class="context-title">
            <i class="tico tico-list-small-thumbnails"></i>
            <h4><?php _e('SITE MANAGEMENT', 'tt'); ?></h4>
        </div>
        <ul class="mg_tabs">
            <li><a class="<?php echo tt_conditional_class('mg_tab status', $mg_tab == 'status'); ?>" href="<?php echo tt_url_for('manage_status'); ?>"><?php _e('STATISTIC', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('mg_tab posts', $mg_tab == 'posts'); ?>" href="<?php echo tt_url_for('manage_posts'); ?>"><?php _e('POSTS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('mg_tab products', $mg_tab == 'products'); ?>" href="<?php echo tt_url_for('manage_products'); ?>"><?php _e('PRODUCTS', 'tt'); ?></a></li>
<!--            <li><a class="--><?php //echo tt_conditional_class('mg_tab comments', $mg_tab == 'comments'); ?><!--" href="--><?php //echo tt_url_for('manage_comments'); ?><!--">--><?php //_e('COMMENTS', 'tt'); ?><!--</a></li>-->
            <li><a class="<?php echo tt_conditional_class('mg_tab orders', $mg_tab == 'orders'); ?>" href="<?php echo tt_url_for('manage_orders'); ?>"><?php _e('ORDERS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('mg_tab users', $mg_tab == 'users'); ?>" href="<?php echo tt_url_for('manage_users'); ?>"><?php _e('USERS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('mg_tab members', $mg_tab == 'members'); ?>" href="<?php echo tt_url_for('manage_members'); ?>"><?php _e('MEMBERS', 'tt'); ?></a></li>
            <li><a class="<?php echo tt_conditional_class('mg_tab coupons', $mg_tab == 'coupons'); ?>" href="<?php echo tt_url_for('manage_coupons'); ?>"><?php _e('COUPONS', 'tt'); ?></a></li>
        </ul>
    </nav>
</aside>