<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/06 17:51
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_author_vars; $tt_paged = $tt_author_vars['tt_paged']; $tt_author_id = $tt_author_vars['tt_author_id']; $logged_user_id = get_current_user_id(); ?>
<?php $vm = UCCommentsVM::getInstance($tt_paged, $tt_author_id, $logged_user_id == $tt_author_id); ?>
<?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Author comments cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<div class="author-tab-box comments-tab">
    <div class="tab-content author-comments">
        <?php if($data = $vm->modelData) { $pagination_args = $data->pagination; $uc_comments = $data->comments; ?>
            <div class="tip"><?php echo sprintf(__('%d comments in total, %d comments approved and %d comments under review', 'tt'), $data->all_count, $data->approved_count, $data->pending_count); ?></div>
            <?php if(count($uc_comments) > 0) { ?>
                <div class="loop-rows comments-loop-rows">
                    <?php foreach ($uc_comments as $uc_comment) { ?>
                        <div id="<?php echo 'comment-' . $uc_comment['comment_ID']; ?>" class="<?php echo $uc_comment['class']; ?>">
                            <div class="comment-title" title="<?php echo $uc_comment['comment_date_diff']; ?>">
                                <span class="comment-author"><img class="avatar lazy" src="<?php echo LAZY_PENDING_AVATAR; ?>" data-original="<?php echo $uc_comment['author_avatar']; ?>"><?php echo $uc_comment['comment_datetime']; ?></span><?php _e('REVIEW', 'tt'); ?><a href="<?php echo $uc_comment['post_permalink']; ?>"><?php echo $uc_comment['post_title']; ?></a>
                            </div>
                            <div class="comment-content"><?php echo $uc_comment['comment_text']; ?></div>
                        </div>
                    <?php } ?>
                </div>

                <?php if($pagination_args['max_num_pages'] > 1) { ?>
                    <?php tt_pagination($pagination_args['base'], $pagination_args['current_page'], $pagination_args['max_num_pages']); ?>
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