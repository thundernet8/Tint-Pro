<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/06 17:23
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_author_vars; global $wp_query; $query_vars=$wp_query->query_vars; $uc_tab = isset($query_vars['uctab']) && in_array($query_vars['uctab'], (array)json_decode(ALLOWED_UC_TABS)) ? $query_vars['uctab'] : 'profile'; $tt_author_vars['uctab'] = $uc_tab; ?>
<nav class="author-tabs clearfix">
    <a class="<?php echo tt_conditional_class('author_tab profile', $uc_tab == 'profile'); ?>" href="<?php echo tt_url_for('uc_profile', $tt_author_vars['tt_author_id']); ?>"><?php _e('PROFILE', 'tt'); ?></a>
    <a class="<?php echo tt_conditional_class('author_tab latest', $uc_tab == 'latest'); ?>" href="<?php echo tt_url_for('uc_latest', $tt_author_vars['tt_author_id']); ?>"><?php _e('ARTICLES', 'tt'); ?></a>
    <a class="<?php echo tt_conditional_class('author_tab comments', $uc_tab == 'comments'); ?>" href="<?php echo tt_url_for('uc_comments', $tt_author_vars['tt_author_id']); ?>"><?php _e('COMMENTS', 'tt'); ?></a>
    <a class="<?php echo tt_conditional_class('author_tab stars', $uc_tab == 'stars'); ?>" href="<?php echo tt_url_for('uc_stars', $tt_author_vars['tt_author_id']); ?>"><?php _e('MY STARS', 'tt'); ?></a>
    <a class="<?php echo tt_conditional_class('author_tab followers', $uc_tab == 'followers'); ?>" href="<?php echo tt_url_for('uc_followers', $tt_author_vars['tt_author_id']); ?>"><?php _e('FOLLOWERS', 'tt'); ?></a>
    <a class="<?php echo tt_conditional_class('author_tab following', $uc_tab == 'following'); ?>" href="<?php echo tt_url_for('uc_following', $tt_author_vars['tt_author_id']); ?>"><?php _e('FOLLOWING', 'tt'); ?></a>
    <!--a class="<?php echo tt_conditional_class('author_tab activities', $uc_tab == 'activities'); ?>" href="<?php echo tt_url_for('uc_activities', $tt_author_vars['tt_author_id']); ?>"><?php _e('ACTIVITIES', 'tt'); ?></a-->
    <!--a class="<?php echo tt_conditional_class('author_tab chat', $uc_tab == 'chat'); ?>" href="<?php echo tt_url_for('uc_chat', $tt_author_vars['tt_author_id']); ?>"><?php _e('CHAT', 'tt'); ?></a-->
</nav>