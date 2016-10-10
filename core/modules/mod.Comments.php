<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/09 13:28
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php
global $postdata;
$vm = PostCommentsVM::getInstance($postdata->ID);
if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Comments cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<?php $comment_list = $vm->modelData->list_html; ?>
<div id="comments-wrap">
    <ul class="comments-list">
        <?php echo $comment_list; ?>
        <div class="pages"><?php //paginate_comments_links('prev_text=«&next_text=»&type=list'); ?></div>
    </ul>
    <?php //$max_pages = get_comment_pages_count($wp_query->comments, $per_page); if($max_pages>1){ ?>
    <div class="load-more" data-pages="<?php //echo $max_pages; ?>"><button class="btn btn-more">加载更多</button></div>
    <?php //} ?>
</div>
