<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * 4040 Page Template
 *
 * @since 2.0.0
 *
 * @author Zhiyan
 * @date 2016/08/22 21:08
 * @license GPL v3 LICENSE
 */
?>
<?php
tt_get_header();
?>
<div class="wrapper container no-aside">
    <div class="main inner-wrap">
        <div class="box text-center" id="404-box">
            <h1>404</h1>
            <p class="404-msg"><?php _e('The page you were looking for doesn\'t exist' , 'tt'); ?></p>
            <div class="btns">
                <a class="btn btn-lg btn-success link-home" id="linkBackHome" href="<?php echo home_url(); ?>" title="<?php _e('Go Back Home', 'tt'); ?>" role="button"><?php _e('Redirect to home after <span class="num">5</span>s', 'tt'); ?></a>
            </div>
        </div>
    </div>
</div>
<?php
tt_get_footer();