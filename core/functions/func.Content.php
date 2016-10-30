<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/29 15:16
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 处理文章内容图片链接以支持lightbox
 *
 * @since 2.0.0
 * @param string $content
 * @return string
 */
function tt_filter_content_for_lightbox ($content){
    $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
    $replacement = '<a$1href=$2$3.$4$5 class="lightbox-gallery" data-lightbox="postContentImages" $6>$7</a>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
add_filter('the_content', 'tt_filter_content_for_lightbox', 98);


/**
 * 替换摘要more字样
 * @param $more
 * @return mixed
 */
function tt_excerpt_more($more) {
    $read_more=tt_get_option('tt_read_more', ' ···');
    return $read_more;
}
add_filter('excerpt_more', 'tt_excerpt_more');