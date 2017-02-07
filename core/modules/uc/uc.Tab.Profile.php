<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/06 17:49
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_author_vars; $tt_author_id = $tt_author_vars['tt_author_id']; $logged_user_id = get_current_user_id(); $is_me = $logged_user_id == $tt_author_id; ?>
<?php $vm = UCProfileVM::getInstance($tt_author_id); ?>
<?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Author profile cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<?php $info = $vm->modelData; ?>
<div class="author-tab-box profile-tab">
    <div class="tab-content author-profile">
        <section class="author-card">
            <div class="inner">
                <img class="avatar" src="<?php echo $info->avatar; ?>">
                <div class="card-text">
                    <div class="display-name"><?php echo $info->display_name; ?></div>
                    <div class="register-time"><?php printf(__('Member Since %s', 'tt'), $info->member_since); ?><?php printf(__(' <b>(The %dth Member)</b>', 'tt'), $info->ID); ?></div>
                    <div class="login-time"><?php if($is_me){printf(__('Last Login %s', 'tt'), $info->latest_login_before ? : 'N/A');} else {printf(__('Latest Login %s', 'tt'), $info->latest_login ? : 'N/A');}; ?></div>
                    <?php if($is_me) { ?>
                    <div class="login-ip"><?php echo sprintf(__('This Login IP %s', 'tt'), $info->this_login_ip ? : $_SERVER['REMOTE_ADDR']) . '&nbsp;&nbsp;&nbsp;&nbsp;' . sprintf(__('Last Login IP %s', 'tt'), $info->last_login_ip ? : 'N/A'); ?></div>
                    <?php } ?>
                    <?php if(!$is_me && current_user_can('edit_users')) { ?>
                        <div class="login-ip"><?php echo sprintf(__('Last Login IP %s', 'tt'), $info->last_login_ip ? : 'N/A'); ?></div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- 基本信息 -->
        <section class="info-basis clearfix">
            <header><h2><?php _e('Basic Info', 'tt'); ?></h2></header>
            <div class="info-group clearfix">
                <label class="col-md-3 control-label"><?php _e('Nickname', 'tt'); ?></label>
                <p class="col-md-9"><?php echo $info->nickname; ?></p>
            </div>
            <?php if($is_me || current_user_can('edit_users')) { ?>
                <div class="info-group clearfix">
                    <label class="col-md-3 control-label"><?php _e('Email', 'tt'); ?></label>
                    <p class="col-md-9"><?php echo $info->email; ?></p>
                </div>
            <?php } ?>
            <div class="info-group clearfix">
                <label class="col-md-3 control-label"><?php _e('Site', 'tt'); ?></label>
                <p class="col-md-9"><?php echo $info->site; ?></p>
            </div>
            <div class="info-group clearfix">
                <label class="col-md-3 control-label"><?php _e('Description', 'tt'); ?></label>
                <p class="col-md-9"><?php echo $info->description; ?></p>
            </div>
        </section>
        <!-- 扩展信息 -->
        <section class="info-extends clearfix">
            <header><h2><?php _e('Extended Info', 'tt'); ?></h2></header>
            <div class="info-group clearfix">
                <?php if($info->qq) { ?>
                <a class="btn btn-wide btn-social-qq" href="<?php echo $info->qq; ?>" target="_blank"><i class="tico tico-qq"></i><?php _e('Talk with QQ', 'tt'); ?></a>
                <?php } ?>
                <?php if($info->weibo) { ?>
                    <a class="btn btn-wide btn-social-weibo" href="<?php echo $info->weibo; ?>" target="_blank"><i class="tico tico-weibo"></i><?php _e('Follow on Weibo', 'tt'); ?></a>
                <?php } ?>
                <?php if($info->weixin) { ?>
                    <a class="btn btn-wide btn-social-weixin popover-qr" href="javascript: void 0" data-trigger="focus" data-container=".info-extends" data-toggle="" data-placement="top" data-content='<?php echo '<img width=175 height=175 src="' . $info->weixin . '">'; ?>'><i class="tico tico-wechat"></i><?php _e('Talk with Wechat', 'tt'); ?></a>
                <?php } ?>
                <?php if($info->twitter) { ?>
                    <a class="btn btn-wide btn-social-twitter" href="<?php echo $info->twitter; ?>" target="_blank"><i class="tico tico-twitter"></i><?php _e('Follow on Twitter', 'tt'); ?></a>
                <?php } ?>
                <?php if($info->facebook) { ?>
                    <a class="btn btn-wide btn-social-facebook" href="<?php echo $info->facebook; ?>" target="_blank"><i class="tico tico-facebook"></i><?php _e('Find on Facebook', 'tt'); ?></a>
                <?php } ?>
                <?php if($info->googleplus) { ?>
                    <a class="btn btn-wide btn-social-googleplus" href="<?php echo $info->googleplus; ?>" target="_blank"><i class="tico tico-google-plus"></i><?php _e('Google+', 'tt'); ?></a>
                <?php } ?>
            </div>
        </section>
        <?php if($info->alipay_pay || $info->wechat_pay) { ?>
        <!-- 收款信息 -->
        <section class="info-donate clearfix">
            <header><h2><?php _e('Donate Info', 'tt'); ?><small><?php _e('Donate to me', 'tt'); ?></small></h2></header>
            <div class="info-group clearfix">
                <?php if($info->alipay_pay) { ?>
                    <a class="btn btn-wide btn-alipay_pay popover-qr" href="javascript: void 0" data-trigger="focus" data-container=".info-donate" data-toggle="" data-placement="top" data-content='<?php echo '<img width=225 height=275 src="' . $info->alipay_pay . '">'; ?>'><?php _e('Donate via Alipay', 'tt'); ?></a>
                <?php } ?>
                <?php if($info->wechat_pay) { ?>
                    <a class="btn btn-wide btn-wechat_pay popover-qr" href="javascript: void 0" data-trigger="focus" data-container=".info-donate" data-toggle="" data-placement="top" data-content='<?php echo '<img width=225 height=275 src="' . $info->wechat_pay . '">'; ?>'><?php _e('Donate via Wechat', 'tt'); ?></a>
                <?php } ?>
            </div>
        </section>
        <?php } ?>
        <!-- 推广信息 -->
        <section class="info-referral clearfix">
            <header><h2><?php _e('Referral Info', 'tt'); ?></h2></header>
            <div class="info-group clearfix">
                <label class="col-md-3 control-label"><?php _e('Referral Link', 'tt'); ?></label>
                <p class="col-md-9"><?php echo $info->referral; ?><!--a href="javascript: void 0" class="copy"><i class="tico tico-copy"></i><?php _e('COPY', 'tt'); ?></a--></p>
            </div>
        </section>
        <?php if(current_user_can('edit_users')) { ?>
        <!-- 禁用或解禁账户操作 -->
        <section class="admin-operation clearfix">
            <header><h2><?php _e('Account Management', 'tt'); ?><small><?php _e('Visible for administrator', 'tt'); ?></small></h2></header>
            <div class="info-group clearfix">
                <?php if($info->banned) { ?>
                <a class="btn btn-wide btn-border-success ban-btn" href="javascript:void 0" data-action="unban" data-uid="<?php echo $info->ID; ?>"><?php _e('Unlock Account', 'tt'); ?></a>
                <p><?php _e('This action will enable the account to normal functions', 'tt'); ?></p>
                <?php }else{ ?>
                <a class="btn btn-wide btn-border-danger ban-btn" href="javascript:void 0" data-action="ban" data-uid="<?php echo $info->ID; ?>"><?php _e('Ban Account', 'tt'); ?></a>
                <p><?php _e('Warning: this action will ban the account, the all features is not accessible until manual unlock', 'tt'); ?></p>
                <?php } ?>
            </div>
        </section>
        <?php } ?>
    </div>
</div>