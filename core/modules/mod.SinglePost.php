<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/07 16:04
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<div id="main" class="main primary col-md-8 post-box" role="main">
    <?php global $post; $vm = SinglePostVM::getInstance($post->ID); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Post cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; ?>
    <div class="post">
        <div class="single-header" style="background-image: url(<?php echo $data->thumb; ?>)">
            <div class="header-wrap">
                <div class="header-meta">
                    <span class="meta-category"><?php echo $data->category; ?></span>
                    <span class="separator" role="separator">·</span>
                    <span class="meta-date"><time class="entry-date" datetime="<?php echo $data->datetime; ?>" title="<?php echo $data->datetime; ?>"><?php echo $data->timediff; ?></time></span>
                </div>
                <h1 class="h2"><?php echo $data->title; ?></h1>
            </div>
        </div>
        <div class="single-body">
            <aside class="share-bar">
                <a class="share-btn share-weibo" href="<?php echo 'http://service.weibo.com/share/share.php?url=' . $data->permalink . '&count=1&title=' . $data->title . ' - ' . get_bloginfo('name') . '&pic=' . urlencode($data->thumb) . '&appkey=' . tt_get_option('tt_weibo_openkey'); ?>" title="<?php _e('Share to Weibo', 'tt'); ?>" target="_blank"></a>
                <a class="share-btn share-qzone" href="<?php echo 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $data->permalink . '&summary=' . $data->excerpt . '&title=' . $data->title . ' - ' . get_bloginfo('name') . '&site=' . get_bloginfo('name') . '&pics=' . urlencode($data->thumb); ?>" title="<?php _e('Share to QZone', 'tt'); ?>" target="_blank"></a>
                <a class="share-btn share-qq" href="<?php echo 'http://connect.qq.com/widget/shareqq/index.html?url=' . $data->permalink . '&title=' . $data->title . ' - ' . get_bloginfo('name') . '&summary=' . $data->excerpt . '&pics=' . urlencode($data->thumb) . '&flash=&site=' . get_bloginfo('name') . '&desc='; ?>" title="<?php _e('Share to QQ', 'tt'); ?>" target="_blank"></a>
                <a class="share-btn share-weixin" href="javascript: void(0)" title="<?php _e('Share to Wechat', 'tt'); ?>" target="_blank">
                    <div class="weixin-qr transition">
                        <img src="<?php echo 'http://qr.liantu.com/api.php?text=' . $data->permalink; ?>" width="120">
                    </div>
                </a>
                <a class="share-btn share-douban" href="<?php echo 'https://www.douban.com/share/service?href=' . $data->permalink . '&name=' . $data->title . ' - ' . get_bloginfo('name') . '&text=' . $data->excerpt . '&image=' . urlencode($data->thumb); ?>" title="<?php _e('Share to Douban', 'tt'); ?>" target="_blank"></a>
            </aside>
            <div class="article-header">
                <div class="post-tags"><?php echo $data->tags; ?></div>
                <div class="post-meta">
                    <a class="post-meta-views" href="javascript: void(0)"><i class="tico tico-eye"></i><span class="num"><?php echo $data->views; ?></span></a>
                    <a class="post-meta-comments js-article-comment js-article-comment-count" href="#respond"><i class="tico tico-comment"></i><span class="num"><?php echo $data->comment_count; ?></span></a>
                    <a class="post-meta-likes js-article-like js-article-like-count" href="javascript: void(0)"><i class="tico tico-favorite"></i><span class="num"><?php echo $data->stars; ?></span></a>
                </div>
            </div>
            <article class="single-article"><?php echo $data->content; ?></article>
            <div class="article-footer">
                <div class="post-copyright">
                    <p><i class="tico tico-bell-o"></i><?php echo $data->cc_text; ?></p>
                </div>
                <div class="support-author"></div>
                <div class="post-like">
                    <a class="post-meta-likes js-article-like js-article-like-count" href="javascript: void(0)" data-post-id="<?php echo $data->ID; ?>"><i class="tico tico-favorite"></i><span class="text"><?php _e('Star It', 'tt'); ?></span></a>
                    <ul class="post-like-avatars">
                        <?php foreach ($data->star_users as $id) { ?>
                        <li class="post-like-user"><img src="<?php echo tt_get_avatar($id, 'small'); ?>" alt=""></li>
                        <?php } ?>
                        <li class="post-like-user"><img src="<?php echo tt_get_avatar(1, 'small'); ?>" alt=""></li>
                        <li class="post-like-counter"><span><span class="js-article-like-count num"><?php echo $data->stars; ?></span> <?php _e('persons', 'tt'); ?></span><?php _e('Stared', 'tt'); ?></li>
                    </ul>
                </div>
                <div class="post-share">
                    <ul class="rrssb-buttons rrssb-2"><li class="rrssb-twitter" data-initwidth="12.5" style="width: calc(20% - 25.2px);" data-size="35"> <a rel="nofollow" href="https://twitter.com/intent/tweet?text=5 Things to Consider Before Buying an Apple Watch&amp;url=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/" class="popup"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"> <path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62c-3.122.162-6.22-.646-8.86-2.32 2.702.18 5.375-.648 7.507-2.32-2.072-.248-3.818-1.662-4.49-3.64.802.13 1.62.077 2.4-.154-2.482-.466-4.312-2.586-4.412-5.11.688.276 1.426.408 2.168.387-2.135-1.65-2.73-4.62-1.394-6.965C5.574 7.816 9.54 9.84 13.802 10.07c-.842-2.738.694-5.64 3.434-6.48 2.018-.624 4.212.043 5.546 1.682 1.186-.213 2.318-.662 3.33-1.317-.386 1.256-1.248 2.312-2.4 2.942 1.048-.106 2.07-.394 3.02-.85-.458 1.182-1.343 2.15-2.48 2.71z"></path> </svg> </span> <span class="rrssb-text">Twitter</span> </a></li><li class="rrssb-facebook" data-initwidth="12.5" style="width: calc(20% - 25.2px);" data-size="54"> <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/" class="popup"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" width="29" height="29" viewBox="0 0 29 29"> <path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z" class="cls-2" fill-rule="evenodd"></path> </svg> </span> <span class="rrssb-text">Facebook</span> </a></li><li class="rrssb-googleplus" data-initwidth="12.5" style="width: calc(20% - 25.2px);" data-size="47"> <a rel="nofollow" href="https://plus.google.com/share?url=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/" class="popup"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 8.29h-1.95v2.6h-2.6v1.82h2.6v2.6H21v-2.6h2.6v-1.885H21V8.29zM7.614 10.306v2.925h3.9c-.26 1.69-1.755 2.925-3.9 2.925-2.34 0-4.29-2.016-4.29-4.354s1.885-4.353 4.29-4.353c1.104 0 2.014.326 2.794 1.105l2.08-2.08c-1.3-1.17-2.924-1.883-4.874-1.883C3.65 4.586.4 7.835.4 11.8s3.25 7.212 7.214 7.212c4.224 0 6.953-2.988 6.953-7.082 0-.52-.065-1.104-.13-1.624H7.614z"></path></svg> </span> <span class="rrssb-text">Google+</span> </a></li><li class="rrssb-pinterest" data-initwidth="12.5" style="width: calc(20% - 25.2px);" data-size="48"> <a rel="nofollow" href="http://pinterest.com/pin/create/button/?url=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/&amp;description=With the holiday shopping season now gearing up, the expectation is that many people will purchase an Apple Watch as a gift. While Apple won’t disclose how many Apple Watches it has sold, market research firm Canalys estimates Apple has already shipped 7 million watches. More and more people — from office types to fitness […]&amp;media=http://demos.famethemes.com/codilight/wp-content/uploads/sites/3/2015/11/smart-watch-821559_1280.jpg"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"> <path d="M14.02 1.57c-7.06 0-12.784 5.723-12.784 12.785S6.96 27.14 14.02 27.14c7.062 0 12.786-5.725 12.786-12.785 0-7.06-5.724-12.785-12.785-12.785zm1.24 17.085c-1.16-.09-1.648-.666-2.558-1.22-.5 2.627-1.113 5.146-2.925 6.46-.56-3.972.822-6.952 1.462-10.117-1.094-1.84.13-5.545 2.437-4.632 2.837 1.123-2.458 6.842 1.1 7.557 3.71.744 5.226-6.44 2.924-8.775-3.324-3.374-9.677-.077-8.896 4.754.19 1.178 1.408 1.538.49 3.168-2.13-.472-2.764-2.15-2.683-4.388.132-3.662 3.292-6.227 6.46-6.582 4.008-.448 7.772 1.474 8.29 5.24.58 4.254-1.815 8.864-6.1 8.532v.003z"></path> </svg> </span> <span class="rrssb-text">Pinterest</span> </a></li><li class="rrssb-tumblr" data-initwidth="12.5" style="width: calc(20% - 25.2px);" data-size="38"> <a rel="nofollow" href="http://tumblr.com/share/link?url=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/&amp;name=5 Things to Consider Before Buying an Apple Watch"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"> <path d="M18.02 21.842c-2.03.052-2.422-1.396-2.44-2.446v-7.294h4.73V7.874H15.6V1.592h-3.714s-.167.053-.182.186c-.218 1.935-1.144 5.33-4.988 6.688v3.637h2.927v7.677c0 2.8 1.7 6.7 7.3 6.6 1.863-.03 3.934-.795 4.392-1.453l-1.22-3.54c-.52.213-1.415.413-2.115.455z"></path> </svg> </span> <span class="rrssb-text">Tumblr</span> </a></li><li class="rrssb-linkedin small" data-initwidth="12.5" style="width: 42px;" data-size="47"> <a rel="nofollow" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/&amp;title=5 Things to Consider Before Buying an Apple Watch&amp;summary=With the holiday shopping season now gearing up, the expectation is that many people will purchase an Apple Watch as a gift. While Apple won’t disclose how many Apple Watches it has sold, market research firm Canalys estimates Apple has already shipped 7 million watches. More and more people — from office types to fitness […]" class="popup"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"> <path d="M25.424 15.887v8.447h-4.896v-7.882c0-1.98-.71-3.33-2.48-3.33-1.354 0-2.158.91-2.514 1.802-.13.315-.162.753-.162 1.194v8.216h-4.9s.067-13.35 0-14.73h4.9v2.087c-.01.017-.023.033-.033.05h.032v-.05c.65-1.002 1.812-2.435 4.414-2.435 3.222 0 5.638 2.106 5.638 6.632zM5.348 2.5c-1.676 0-2.772 1.093-2.772 2.54 0 1.42 1.066 2.538 2.717 2.546h.032c1.71 0 2.77-1.132 2.77-2.546C8.056 3.593 7.02 2.5 5.344 2.5h.005zm-2.48 21.834h4.896V9.604H2.867v14.73z"></path> </svg> </span> <span class="rrssb-text">Linkedin</span> </a></li><li class="rrssb-reddit small" data-initwidth="12.5" style="width: 42px;" data-size="36"> <a rel="nofollow" href="http://www.reddit.com/submit?url=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/&amp;title=5 Things to Consider Before Buying an Apple Watch&amp;text=With the holiday shopping season now gearing up, the expectation is that many people will purchase an Apple Watch as a gift. While Apple won’t disclose how many Apple Watches it has sold, market research firm Canalys estimates Apple has already shipped 7 million watches. More and more people — from office types to fitness […]"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"> <path d="M11.794 15.316c0-1.03-.835-1.895-1.866-1.895-1.03 0-1.893.866-1.893 1.896s.863 1.9 1.9 1.9c1.023-.016 1.865-.916 1.865-1.9zM18.1 13.422c-1.03 0-1.895.864-1.895 1.895 0 1 .9 1.9 1.9 1.865 1.03 0 1.87-.836 1.87-1.865-.006-1.017-.875-1.917-1.875-1.895zM17.527 19.79c-.678.68-1.826 1.007-3.514 1.007h-.03c-1.686 0-2.834-.328-3.51-1.005-.264-.265-.693-.265-.958 0-.264.265-.264.7 0 1 .943.9 2.4 1.4 4.5 1.402.005 0 0 0 0 0 .005 0 0 0 0 0 2.066 0 3.527-.46 4.47-1.402.265-.264.265-.693.002-.958-.267-.334-.688-.334-.988-.043z"></path> <path d="M27.707 13.267c0-1.785-1.453-3.237-3.236-3.237-.792 0-1.517.287-2.08.76-2.04-1.294-4.647-2.068-7.44-2.218l1.484-4.69 4.062.955c.07 1.4 1.3 2.6 2.7 2.555 1.488 0 2.695-1.208 2.695-2.695C25.88 3.2 24.7 2 23.2 2c-1.06 0-1.98.616-2.42 1.508l-4.633-1.09c-.344-.082-.693.117-.803.454l-1.793 5.7C10.55 8.6 7.7 9.4 5.6 10.75c-.594-.45-1.3-.75-2.1-.72-1.785 0-3.237 1.45-3.237 3.2 0 1.1.6 2.1 1.4 2.69-.04.27-.06.55-.06.83 0 2.3 1.3 4.4 3.7 5.9 2.298 1.5 5.3 2.3 8.6 2.325 3.227 0 6.27-.825 8.57-2.325 2.387-1.56 3.7-3.66 3.7-5.917 0-.26-.016-.514-.05-.768.965-.465 1.577-1.565 1.577-2.698zm-4.52-9.912c.74 0 1.3.6 1.3 1.3 0 .738-.6 1.34-1.34 1.34s-1.343-.602-1.343-1.34c.04-.655.596-1.255 1.396-1.3zM1.646 13.3c0-1.038.845-1.882 1.883-1.882.31 0 .6.1.9.21-1.05.867-1.813 1.86-2.26 2.9-.338-.328-.57-.728-.57-1.26zm20.126 8.27c-2.082 1.357-4.863 2.105-7.83 2.105-2.968 0-5.748-.748-7.83-2.105-1.99-1.3-3.087-3-3.087-4.782 0-1.784 1.097-3.484 3.088-4.784 2.08-1.358 4.86-2.106 7.828-2.106 2.967 0 5.7.7 7.8 2.106 1.99 1.3 3.1 3 3.1 4.784C24.86 18.6 23.8 20.3 21.8 21.57zm4.014-6.97c-.432-1.084-1.19-2.095-2.244-2.977.273-.156.59-.245.928-.245 1.036 0 1.9.8 1.9 1.9-.016.522-.27 1.022-.57 1.327z"></path> </svg> </span> <span class="rrssb-text">Reddit</span> </a></li><li class="rrssb-email small" data-initwidth="12.5" style="width: 42px;" data-size="31"> <a rel="nofollow" href="mailto:?subject=5 Things to Consider Before Buying an Apple Watch&amp;body=http://demos.famethemes.com/codilight/2015/11/21/5-things-to-consider-before-buying-an-apple-watch/"> <span class="rrssb-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"> <path d="M20.11 26.147c-2.335 1.05-4.36 1.4-7.124 1.4C6.524 27.548.84 22.916.84 15.284.84 7.343 6.602.45 15.4.45c6.854 0 11.8 4.7 11.8 11.252 0 5.684-3.193 9.265-7.398 9.3-1.83 0-3.153-.934-3.347-2.997h-.077c-1.208 1.986-2.96 2.997-5.023 2.997-2.532 0-4.36-1.868-4.36-5.062 0-4.75 3.503-9.07 9.11-9.07 1.713 0 3.7.4 4.6.972l-1.17 7.203c-.387 2.298-.115 3.3 1 3.4 1.674 0 3.774-2.102 3.774-6.58 0-5.06-3.27-8.994-9.304-8.994C9.05 2.87 3.83 7.545 3.83 14.97c0 6.5 4.2 10.2 10 10.202 1.987 0 4.09-.43 5.647-1.245l.634 2.22zM16.647 10.1c-.31-.078-.7-.155-1.207-.155-2.572 0-4.596 2.53-4.596 5.53 0 1.5.7 2.4 1.9 2.4 1.44 0 2.96-1.83 3.31-4.088l.592-3.72z"></path> </svg> </span> <span class="rrssb-text">Email</span> </a></li></ul></div>
            </div>
        </div>
        <!-- 上下篇导航 -->
        <div class="navigation clearfix">
            <div class="col-md-6">
                <span><?php _e('Previous article', 'tt'); ?></span>
                <h2 class="h5"><?php echo $data->prev; ?></h2>
            </div>
            <div class="col-md-6 post-navi-next">
                <span><?php _e('Next article', 'tt'); ?></span>
                <h2 class="h5"><?php echo $data->next; ?></h2>
            </div>
        </div>
        <!-- 相关文章 -->
        <?php if(count($data->relates) > 0) { ?>
        <div class="related-posts">
            <h3><?php _e('Related Articles', 'tt'); ?></h3>
            <div class="related-articles row clearfix">
                <?php foreach ($data->relates as $relate) { ?>
                <article class="col-md-4 col-sm-12">
                    <div class="related-thumb">
                        <a href="<?php echo $relate['permalink']; ?>" title="<?php echo $relate['title']; ?>"><img src="<?php echo $relate['thumb']; ?>" class="thumb-medium wp-post-image" alt=""> </a>
                        <div class="entry-category"><?php echo $relate['category']; ?></div>
                    </div>
                    <div class="entry-detail">
                        <header class="entry-header">
                            <h2 class="entry-title h5"><a href="<?php echo $relate['permalink']; ?>" rel="bookmark"><?php echo $relate['title']; ?></a></h2>
                        </header>
                    </div>
                </article>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <!-- 评论 -->
        <div id="respond">

        </div>
    </div>
</div>