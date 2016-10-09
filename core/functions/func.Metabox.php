<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/07 20:59
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php
function tt_add_metaboxes() {
    $screens = array( 'post', 'page' );
    foreach ( $screens as $screen ) {

    }
    // 转载信息
    add_meta_box(
        'tt_copyright_content',
        __( 'Post Copyright Info', 'tt' ),
        'tt_post_copyright_callback',
        'post',
        'normal','high'
    );
}
add_action( 'add_meta_boxes', 'tt_add_metaboxes' );


/**
 * 文章转载信息
 *
 * @since   2.0.0
 * @param   WP_Post    $post
 * @return  void
 */
function tt_post_copyright_callback($post) {
    $cc = get_post_meta( $post->ID, 'tt_post_copyright', true );
    $cc = $cc ? maybe_unserialize($cc) : array('source_title' => '', 'source_link' => '');
    ?>
    <p><?php _e( 'Post Source Title', 'tt' );?></p>
    <textarea name="tt_source_title" rows="1" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($cc['source_title']));?></textarea>
    <p><?php _e( 'Post Source Link, leaving empty means the post is original article', 'tt' );?></p>
    <textarea name="tt_source_link" rows="1" class="large-text code"><?php echo stripcslashes(htmlspecialchars_decode($cc['source_link']));?></textarea>
    <?php
}
