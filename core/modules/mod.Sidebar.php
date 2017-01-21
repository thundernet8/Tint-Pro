<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/24 23:05
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<!-- SideBar Begin -->
<aside class="sidebar secondary col-md-4" id="sidebar">
    <?php if(is_single()) the_widget('AuthorWidget'); ?>
    <?php dynamic_sidebar(tt_dynamic_sidebar()); ?>
    <div class="widget float-widget-mirror">
    </div>
</aside>