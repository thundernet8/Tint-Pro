<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/15 00:15
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; $tt_filter_type = get_query_var('me_grandchild_route'); $tt_page = $tt_me_vars['tt_paged']; ?>
<div class="col col-right notifications">
    <?php $vm = MeNotificationsVM::getInstance($tt_user_id, $tt_filter_type, $tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Notifications cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $notifications = $data->notifications; $count = $data->count; $total = $data->total; $max_pages = $data->max_pages; ?>
    <div class="me-tab-box notifications-tab">
        <div class="tab-content me-notifications">
            <!-- 通知列表 -->
            <section class="my-notifications clearfix">
                <header><h2><?php _e('My Notifications', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <div class="col-md-6 notifications-info">
                        <span><?php printf(__('%d notifications records in total', 'tt'), $total); ?></span>
                    </div>
                    <div class="col-md-6 notifications-filter">
                        <label><?php _e('Notifications Type', 'tt'); ?></label>
                        <select class="form-control select select-primary" data-toggle="select" onchange="document.location.href=this.options[this.selectedIndex].value;">
                            <option value="<?php echo tt_url_for('all_notify'); ?>" <?php if(strtolower($tt_filter_type) == 'all') echo 'selected'; ?>><?php _e('ALL', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('comment_notify'); ?>" <?php if(strtolower($tt_filter_type) == 'comment') echo 'selected'; ?>><?php _e('COMMENT NOTIFICATION', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('star_notify'); ?>" <?php if(strtolower($tt_filter_type) == 'star') echo 'selected'; ?>><?php _e('STAR NOTIFICATION', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('credit_notify'); ?>" <?php if(strtolower($tt_filter_type) == 'credit') echo 'selected'; ?>><?php _e('CREDIT NOTIFICATION', 'tt'); ?></option>
                        </select>
                    </div>
                </div>
                <?php if($count > 0) { ?>
                    <div class="info-group clearfix">
                        <ul class="notifications-list">
                            <?php foreach ($notifications as $notification) { ?>
                                <li id="notification-<?php echo $notification->msg_id; ?>"><?php echo $notification->msg_date; ?><span class="record-text"><?php echo $notification->msg_title; ?></span></li>
                            <?php } ?>
                        </ul>
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
                    </div>
                <?php }else{ ?>
                    <div class="empty-content">
                        <span class="tico tico-bell-o"></span>
                        <p><?php _e('No notifications', 'tt'); ?></p>
                        <!--                    <a class="btn btn-info" href="/">--><?php //_e('Back to home', 'tt'); ?><!--</a>-->
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
</div>