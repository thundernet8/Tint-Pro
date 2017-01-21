<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/10 18:05
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<div id="main" class="main primary col-md-8 post-box" role="main">
    <?php global $post; $vm = SinglePageVM::getInstance($post->ID); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Page cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php global $postdata; $postdata = $vm->modelData; ?>
    <div class="post page">
        <div class="single-header">
            <div class="header-wrap">
                <h1 class="h2"><?php echo $postdata->title; ?></h1>
                <div class="header-meta">
                    <span class="meta-date"><?php _e('Post on: ', 'tt'); ?><time class="entry-date" datetime="<?php echo $postdata->datetime; ?>" title="<?php echo $postdata->datetime; ?>"><?php echo $postdata->timediff; ?></time></span>
                    <span class="separator" role="separator"> · </span>
                    <span class="meta-date"><?php _e('Modified on: ', 'tt'); ?><time class="entry-date" datetime="<?php echo $postdata->modified; ?>" title="<?php echo $postdata->modified; ?>"><?php echo $postdata->modifieddiff; ?></time></span>
                </div>
            </div>
        </div>
        <div class="single-body">
<!--            <div class="article-header">-->
<!--                <div class="post-tags">--><?php //echo $postdata->tags; ?><!--</div>-->
<!--                <div class="post-meta">-->
<!--                    <a class="post-meta-views" href="javascript: void(0)"><i class="tico tico-eye"></i><span class="num">--><?php //echo $postdata->views; ?><!--</span></a>-->
<!--                    <a class="post-meta-comments js-article-comment js-article-comment-count" href="#respond"><i class="tico tico-comment"></i><span class="num">--><?php //echo $postdata->comment_count; ?><!--</span></a>-->
<!--                    <a class="post-meta-likes js-article-like --><?php //if(in_array(get_current_user_id(), $postdata->star_uids)) echo 'active'; ?><!--" href="javascript: void(0)" data-post-id="--><?php //echo $postdata->ID; ?><!--" data-nonce="--><?php //echo wp_create_nonce('tt_post_star_nonce'); ?><!--"><i class="tico tico-favorite"></i><span class="js-article-like-count num">--><?php //echo $postdata->stars; ?><!--</span></a>-->
<!--                </div>-->
<!--            </div>-->
            <article class="single-article"><?php echo $postdata->content; apply_filters('the_content', 'content'); // 一些插件(如crayon-syntax-highlighter)将非内容性的钩子(wp_enqueue_script等)挂载在the_content上, 缓存命中时将失效 ?></article>
            <div class="article-footer">
                <div class="support-author"></div>
                <div class="post-like">
                    <a class="post-meta-likes js-article-like <?php if(in_array(get_current_user_id(), $postdata->star_uids)) echo 'active'; ?>" href="javascript: void(0)" data-post-id="<?php echo $postdata->ID; ?>" data-nonce="<?php echo wp_create_nonce('tt_post_star_nonce'); ?>"><i class="tico tico-favorite"></i><span class="text"><?php in_array(get_current_user_id(), $postdata->star_uids) ? _e('Stared', 'tt') : _e('Star It', 'tt'); ?></span></a>
                    <ul class="post-like-avatars">
                        <?php foreach ($postdata->star_users as $star_user) { ?>
                            <li class="post-like-user"><img src="<?php echo $star_user->avatar; ?>" alt="<?php echo $star_user->name; ?>" title="<?php echo $star_user->name; ?>" data-user-id="<?php echo $star_user->uid; ?>"></li>
                        <?php } ?>
                        <li class="post-like-counter"><span><span class="js-article-like-count num"><?php echo $postdata->stars; ?></span> <?php _e('persons', 'tt'); ?></span><?php _e('Stared', 'tt'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- 评论 -->
        <div id="respond">
            <?php if($postdata->comment_status) { ?>
                <h3><?php _e('LEAVE A REPLY', 'tt'); ?></h3>
                <?php load_mod( 'mod.ReplyForm', true ); ?>
                <?php comments_template( '/core/modules/mod.Comments.php', true ); ?>
            <?php }else{ ?>
                <h3><?php _e('COMMENTS CLOSED', 'tt'); ?></h3>
                <?php comments_template( '/core/modules/mod.Comments.php', true ); ?>
            <?php } ?>
        </div>
    </div>
</div>