<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/15 00:16
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; ?>
<div class="col col-right stars">
    <?php //$vm = UCProfileVM::getInstance($tt_user_id); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Author profile cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $info = $vm->modelData; ?>
    <div class="me-tab-box stars-tab">
        <div class="tab-content me-stars">


        </div>
    </div>
</div>