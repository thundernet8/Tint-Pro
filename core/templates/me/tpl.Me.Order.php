<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 17:20
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php tt_get_header(); ?>
<div id="content" class="wrapper">
    <!-- 主要内容区 -->
    <section class="container user-area">
        <div class="inner row">
            <?php load_mod('me/me.NavMenu'); ?>
            <?php load_mod('me/me.Tab.Order'); ?>
        </div>
    </section>
</div>
<?php tt_get_footer(); ?>