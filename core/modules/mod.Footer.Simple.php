<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/24 23:01
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<!-- Footer -->
<footer class="footer simple-footer">
    <div class="foot-menu pull-left">
        <a href="<?php echo home_url(); ?>" title="" target="_blank"><?php _e('HOME', 'tt'); ?></a>
        <span class="sep" role="separator"></span>
        <a href="<?php echo tt_url_for('privacy'); ?>" title="" target="_blank"><?php _e('TERMS AND POLICIES', 'tt'); ?></a>
        <span class="sep" role="separator"></span>
        <a href="<?php echo home_url('/about'); ?>" title="" target="_blank"><?php _e('ABOUT', 'tt'); ?></a>
        <span class="sep" role="separator"></span>
        <a href="javascript:void(0)" title="" target="_blank"><?php _e('CONTACT', 'tt'); ?></a> <!-- TODO: 联系方式如二维码 -->
    </div>
    <div class="foot-copyright pull-right">&copy;&nbsp;<?php the_date('Y'); ?><?php echo ' ' . get_bloginfo('name') . ' All Right Reserved · Design by <a href="https://www.webapproach.net" title="WebApproach">WebApproach.</a>'; ?>
    </div>
</footer>
<?php if(tt_get_option('tt_foot_code')) { echo tt_get_option('tt_foot_code'); } ?>
<?php wp_footer(); ?>
</body>
</html>