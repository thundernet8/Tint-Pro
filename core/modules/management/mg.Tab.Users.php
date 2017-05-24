<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.3
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/07 21:38
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_mg_vars; $tt_user_id = $tt_mg_vars['tt_user_id']; $tt_filter_type = get_query_var('manage_grandchild_route'); $tt_page = $tt_mg_vars['tt_paged']; ?>
<div class="col col-right users">
    <?php $vm = MgUsersVM::getInstance($tt_page, $tt_filter_type); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Manage users cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $users = $data->users; $count = $data->count; $total = $data->total; $max_pages = $data->max_pages; ?>
    <div class="mg-tab-box users-tab">
        <div class="tab-content">
            <!-- 用户列表 -->
            <section class="mg-users clearfix">
                <header><h2><?php _e('Users List', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <div class="col-md-6 users-info">
                        <span><?php printf(__('%d users in total', 'tt'), $total); ?></span>
                    </div>
                    <div class="col-md-6 users-filter">
                        <label><?php _e('User Role', 'tt'); ?></label>
                        <select class="form-control select select-primary" data-toggle="select" onchange="document.location.href=this.options[this.selectedIndex].value;">
                            <option value="<?php echo tt_url_for('manage_users'); ?>" <?php if(strtolower($tt_filter_type) == 'all') echo 'selected'; ?>><?php _e('ALL', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('manage_admins'); ?>" <?php if(strtolower($tt_filter_type) == 'administrator') echo 'selected'; ?>><?php _e('ADMINISTRATOR', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('manage_editors'); ?>" <?php if(strtolower($tt_filter_type) == 'editor') echo 'selected'; ?>><?php _e('EDITOR', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('manage_authors'); ?>" <?php if(strtolower($tt_filter_type) == 'author') echo 'selected'; ?>><?php _e('AUTHOR', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('manage_contributors'); ?>" <?php if(strtolower($tt_filter_type) == 'contributor') echo 'selected'; ?>><?php _e('CONTRIBUTOR', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('manage_subscribers'); ?>" <?php if(strtolower($tt_filter_type) == 'subscriber') echo 'selected'; ?>><?php _e('SUBSCRIBER', 'tt'); ?></option>
                        </select>
                    </div>
                </div>
                <?php if($count > 0) { ?>
                    <table class="table table-striped table-framed table-centered">
                        <thead>
                        <tr>
                            <th class="th-uid"><?php _e('ID', 'tt'); ?></th>
                            <th class="th-name"><?php _e('User Name', 'tt'); ?></th>
                            <th class="th-email"><?php _e('Email', 'tt'); ?></th>
                            <th class="th-role"><?php _e('Role', 'tt'); ?></th>
                            <th class="th-time"><?php _e('Register Time', 'tt'); ?></th>
                            <th class="th-last"><?php _e('Last Login', 'tt'); ?></th>
                            <th class="th-actions"><?php _e('Actions', 'tt'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user){ ?>
                            <tr id="uid-<?php echo $user->ID; ?>">
                                <td><?php echo $user->ID; ?></td>
                                <td><?php echo $user->display_name; ?></td>
                                <td><?php echo $user->user_email; ?></td>
                                <td><?php _e(strtoupper($user->roles[0]), 'tt'); ?></td>
                                <td><?php echo $user->user_registered; ?></td>
                                <td><?php echo mysql2date('Y-m-d H:i:s', get_user_meta($user->ID, 'tt_latest_login', true));; ?></td>
                                <td>
                                    <div class="user-actions">
                                        <a class="view-detail" href="<?php echo tt_url_for('manage_user', $user->id); ?>" title="<?php _e('Manage the user', 'tt'); ?>" target="_blank"><?php _e('Manage', 'tt'); ?></a>
                                        <span class="text-explode">|</span>
                                        <a class="view-home" href="<?php echo get_author_posts_url($user->id); ?>" title="<?php _e('View the user\'s homepage', 'tt'); ?>" target="_blank"><?php _e('Homepage', 'tt'); ?></a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php if($max_pages > 1) { ?>
                        <div class="pagination-mini clearfix">
                            <?php if($tt_page == 1) { ?>
                                <div class="col-md-3 prev disabled"><a href="javascript:;"><?php _e('← 上一页', 'tt'); ?></a></div>
                            <?php }else{ ?>
                                <div class="col-md-3 prev"><a href="<?php echo $data->prev_page; ?>"><?php _e('← 上一页', 'tt'); ?></a></div>
                            <?php } ?>
                            <div class="col-md-6 page-nums">
                                <span class="current-page"><?php printf(__('Current Page %d', 'tt'), $tt_page); ?></span>
                                <span class="separator">/</span>
                                <span class="max-page"><?php printf(__('Total %d Pages', 'tt'), $max_pages); ?></span>
                            </div>
                            <?php if($tt_page != $data->max_pages) { ?>
                                <div class="col-md-3 next"><a href="<?php echo $data->next_page; ?>"><?php _e('下一页 →', 'tt'); ?></a></div>
                            <?php }else{ ?>
                                <div class="col-md-3 next disabled"><a href="javascript:;"><?php _e('下一页 →', 'tt'); ?></a></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="empty-content">
                        <span class="tico tico-users"></span>
                        <p><?php _e('Nothing found here', 'tt'); ?></p>
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
</div>