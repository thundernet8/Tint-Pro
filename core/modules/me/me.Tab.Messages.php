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
<div class="col col-right messages">
    <?php $vm = MeMessagesVM::getInstance($tt_user_id, $tt_filter_type, $tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Messages cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $messages = $data->messages; $count = $data->count; $total = $data->total; $max_pages = $data->max_pages; ?>
    <div class="me-tab-box messages-tab">
        <div class="tab-content me-messages">
            <!-- 消息列表 -->
            <section class="my-messages clearfix">
                <header><h2><?php _e('My Messages', 'tt'); ?><small><?php echo $tt_filter_type=='sendbox' ? __('Sendbox', 'tt') : __('Inbox', 'tt'); ?></small></h2></header>
                <div class="info-group clearfix">
                    <div class="col-md-6 messages-info">
                        <span><?php printf(__('%d message records in total', 'tt'), $total); ?></span>
                    </div>
                    <div class="col-md-6 messages-filter">
                        <label><?php _e('Messages Type', 'tt'); ?></label>
                        <select class="form-control select select-primary" data-toggle="select" onchange="document.location.href=this.options[this.selectedIndex].value;">
                            <option value="<?php echo tt_url_for('in_msg'); ?>" <?php if(strtolower($tt_filter_type) == 'inbox') echo 'selected'; ?>><?php _e('INBOX', 'tt'); ?></option>
                            <option value="<?php echo tt_url_for('out_msg'); ?>" <?php if(strtolower($tt_filter_type) == 'sendbox') echo 'selected'; ?>><?php _e('SENDBOX', 'tt'); ?></option>
                        </select>
                    </div>
                </div>
                <?php if($count > 0) { ?>
                <div class="info-group clearfix">
                    <ul class="messages-list">
                        <?php foreach ($messages as $message) { ?>
                            <?php if($tt_filter_type == 'sendbox') { ?>
                            <?php $receiver_name = get_user_meta($message->user_id, 'nickname', true); ?>
                            <li id="message-<?php echo $message->msg_id; ?>" class="message sendbox">
                                <div class="msg-title" title="<?php printf(__('Sent to %s', 'tt'), $receiver_name); ?>">
                                        <span class="msg-author">
                                            <img class="avatar lazy" src="<?php echo LAZY_PENDING_AVATAR; ?>" data-original="<?php echo tt_get_avatar($message->user_id, 'medium'); ?>" style="display: block;">
                                            <?php _e('Send Time: ', 'tt'); ?><span><?php echo $message->msg_date; ?></span>
                                            <?php _e('Send To: ', 'tt'); ?><span><a href="<?php echo tt_url_for('uc_chat', $message->user_id); ?>" target="_blank"><?php echo $receiver_name; ?></a></span>
                                        </span>
                                </div>
                                <div class="msg-content">
                                    <p><?php echo $message->msg_title; ?></p>
                                </div>
                            </li>
                            <?php }else{ ?>
                            <li id="message-<?php echo $message->msg_id; ?>" class="message inbox">
                                <div class="msg-title" title="<?php printf(__('%s send to me', 'tt'), $message->sender); ?>">
                                        <span class="msg-author">
                                            <img class="avatar lazy" src="<?php echo LAZY_PENDING_AVATAR; ?>" data-original="<?php echo tt_get_avatar($message->sender_id, 'medium'); ?>" style="display: block;">
<!--                                            --><?php //if(!$message->read) { ?>
<!--                                                <span class="unread-mark"><i class="tico tico-info-circle"></i></span>-->
<!--                                            --><?php //} ?>
                                            <?php _e('Send Time: ', 'tt'); ?><span><?php echo $message->msg_date; ?></span>
                                            <?php _e('Sender: ', 'tt'); ?><span><a href="<?php echo tt_url_for('uc_chat', $message->sender_id); ?>" target="_blank"><?php echo $message->sender; ?></a></span>
                                            <?php _e('Status: ', 'tt'); ?><span><?php echo $message->msg_read ? __('Read', 'tt') : __('Unread', 'tt'); ?></span>
                                        </span>
                                </div>
                                <div class="msg-content">
                                    <p><?php echo $message->msg_title; ?></p>
                                </div>
                            </li>
                            <?php } ?>
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
                    <span class="tico tico-comments"></span>
                    <p><?php _e('No message records', 'tt'); ?></p>
<!--                    <a class="btn btn-info" href="/">--><?php //_e('Back to home', 'tt'); ?><!--</a>-->
                </div>
                <?php } ?>
            </section>
        </div>
    </div>
</div>