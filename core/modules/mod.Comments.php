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

$per_page = tt_get_option('tt_comments_per_page', 20);

$the_comments = get_comments(array(
    'status' => 'approve',
    'type' => 'comment', // 'pings' (includes 'pingback' and 'trackback'),
    'post_id'=> $postdata->ID,
    //'meta_key' => 'tt_sticky_comment',
    'orderby' => 'comment_date', //meta_value_num
    'order' => 'DESC',
    'number' => $per_page,
    'offset' => 0
));

$comment_list = wp_list_comments(array(
    'type'=>'all',
    'callback'=>'tt_comment',
    'end-callback'=>'tt_end_comment',
    'max_depth'=>3,
    'reverse_top_level'=>0,
    'style'=>'div',
    'page'=>1,
    'per_page'=>$per_page,
    'echo'=>false
), $the_comments);
?>
<div id="comments-wrap">
    <ul class="comments-list">
        <?php echo $comment_list; ?>
        <div class="pages"><?php //paginate_comments_links('prev_text=«&next_text=»&type=list'); ?></div>
    </ul>
    <?php //$max_pages = get_comment_pages_count($wp_query->comments, $per_page); if($max_pages>1){ ?>
    <div class="load-more" data-pages="<?php //echo $max_pages; ?>"><button class="btn btn-more">加载更多</button></div>
    <?php //} ?>
</div>
