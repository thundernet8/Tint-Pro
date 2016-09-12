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
<footer class="footer">
    <!-- TODO -->
    <div class="foot-copyright align-center">
		&copy;&nbsp;<?php the_date('Y'); ?><?php echo ' ' . get_bloginfo('name') . ' All Right Reserved · Design by <a href="https://www.webapproach.net" title="WebApproach">WebApproach.</a>'; ?>
	</div>
</footer>
<?php if(tt_get_option('tt_foot_code')) { echo tt_get_option('tt_foot_code'); } ?>
<?php wp_footer(); ?>
</body>
</html>