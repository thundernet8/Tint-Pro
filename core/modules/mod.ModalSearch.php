<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/28 23:09
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<!-- 搜索模态框 -->
<div id="globalSearch" class="js-search search-form search-form-modal fadeZoomIn" role="dialog" aria-hidden="true">
    <form method="get" action="<?php echo home_url(); ?>" role="search">
        <div class="search-form-inner">
            <div class="search-form-box">
                <input class="form-search" type="text" name="s" placeholder="<?php _e('Type a keyword', 'tt'); ?>">
            </div>
        </div>
    </form>
</div>