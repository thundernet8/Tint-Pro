<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/06 17:54
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_author_vars; $tt_paged = $tt_author_vars['tt_paged']; $tt_author_id = $tt_author_vars['tt_author_id']; $logged_user_id = get_current_user_id(); ?>
<?php $vm = UCFollowingVM::getInstance($tt_paged, $tt_author_id); ?>
<?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Author followings cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<div class="author-tab-box follow-tab following-tab">
    <div class="tab-content author-follow author-following">
        <?php if($data = $vm->modelData) { $count = $data->count; $followings = $data->followings; $total = $data->total; $max_pages = $data->max_pages; ?>
            <?php if($count > 0) { ?>
                <div class="row">
                    <?php foreach ($followings as $following) { ?>
                        <div class="follow-box follower-box col-md-4 col-sm-6">
                            <div class="box-inner transition">
                                <div class="cover" style="background-image: url(<?php echo $following['cover']; ?>)">
                                    <img class="avatar lazy" src="<?php echo LAZY_PENDING_AVATAR; ?>" data-original="<?php echo $following['avatar']; ?>">
                                    <div class="mask">
                                        <h2><?php echo $following['display_name']; ?></h2>
                                        <p><?php echo __('Brief Intro : ', 'tt') . $following['description']; ?></p>
                                    </div>
                                </div>
                                <div class="user-stats">
                                    <span class="following"><span class="unit"><?php _e('FOLLOWING', 'tt'); ?></span><?php echo $following['following_count']; ?></span>
                                    <span class="followers"><span class="unit"><?php _e('FOLLOWERS', 'tt'); ?></span><?php echo $following['followers_count']; ?></span>
                                    <span class="posts"><span class="unit"><?php _e('POSTS', 'tt'); ?></span><?php echo $following['posts_count']; ?></span>
                                </div>
                                <div class="user-interact">
                                    <?php if ($following['ID'] != $logged_user_id) { ?>
                                        <?php echo tt_follow_button($following['ID']); ?>
                                        <a class="pm-btn" href="javascript: void 0" data-receiver="<?php echo $following['display_name']; ?>" data-receiver-id="1" title="<?php _e('Send a message', 'tt'); ?>"><i class="tico tico-envelope"></i><?php _e('Chat', 'tt'); ?></a>
                                        <a class="dropdown-toggle more-link-btn" href="javascript: void 0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="tico tico-list"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo $following['home']; ?>"><?php _e('View Homepage', 'tt'); ?></a></li>
                                            <li><a href="<?php echo $following['posts_url']; ?>"><?php _e('His Posts', 'tt'); ?></a></li>
                                            <li><a href="<?php echo $following['comments_url']; ?>"><?php _e('His Comments', 'tt'); ?></a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="<?php echo $following['activities_url']; ?>"><?php _e('Check Activities', 'tt'); ?></a></li>
                                        </ul>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if($max_pages > 1) { ?>
                    <div class="pagination-mini clearfix">
                        <?php if($tt_paged == 1) { ?>
                            <div class="col-md-3 prev disabled"><a href="javascript:;"><?php _e('← 上一页', 'tt'); ?></a></div>
                        <?php }else{ ?>
                            <div class="col-md-3 prev"><a href="<?php echo $data->prev_page; ?>"><?php _e('← 上一页', 'tt'); ?></a></div>
                        <?php } ?>
                        <div class="col-md-6 page-nums">
                            <span class="current-page"><?php printf(__('Current Page %d', 'tt'), $tt_paged); ?></span>
                            <span class="separator">/</span>
                            <span class="max-page"><?php printf(__('Total %d Pages', 'tt'), $max_pages); ?></span>
                        </div>
                        <?php if($tt_paged != $data->max_pages) { ?>
                            <div class="col-md-3 next"><a href="<?php echo $data->next_page; ?>"><?php _e('下一页 →', 'tt'); ?></a></div>
                        <?php }else{ ?>
                            <div class="col-md-3 next disabled"><a href="javascript:;"><?php _e('下一页 →', 'tt'); ?></a></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="empty-content">
                    <span class="tico tico-dropbox"></span>
                    <p><?php _e('Nothing found here', 'tt'); ?></p>
                    <a class="btn btn-info" href="/"><?php _e('Back to home', 'tt'); ?></a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>