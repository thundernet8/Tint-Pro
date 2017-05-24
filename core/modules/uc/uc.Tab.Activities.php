<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/06 17:55
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_author_vars; $tt_paged = $tt_author_vars['tt_paged']; $tt_author_id = $tt_author_vars['tt_author_id']; $logged_user_id = get_current_user_id(); ?>
<?php $vm = UCChatVM::getInstance($tt_paged, $tt_author_id); ?>
<?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Author activities cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<div class="author-tab-box activities-tab">
    <div class="tab-content author-activities">

    </div>
</div>