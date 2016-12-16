<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/14 22:05
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<script type="text/javascript" src="https://cdn.staticfile.org/webuploader/0.1.5/webuploader.html5only.min.js"></script>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; ?>
<div class="col col-right settings">
    <?php $vm = MeSettingsVM::getInstance($tt_user_id); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- User settings cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $info = $vm->modelData; ?>
    <div class="me-tab-box setting-tab">
        <div class="tab-content me-settings">
            <!-- 基本信息 -->
            <section class="info-basis clearfix">
                <header><h2><?php _e('Basic Info', 'tt'); ?></h2></header>
                <div class="form-group info-group clearfix">
                    <label class="col-sm-3 control-label"><?php _e('Avatar', 'tt'); ?></label>
                    <div class="col-sm-9">
                        <div class="radio">
                            <?php if($info->use_local_avatar){ ?>
                            <label class="local-avatar-label" title="<?php _e('Upload Avatar', 'tt'); ?>">
                                <img src="<?php echo $info->avatar; ?>" class="avatar" data-filename="<?php echo $tt_user_id . '.jpg'; ?>" width="40" height="40">
                                <svg class="svgIcon-use avatar-picker img-picker" width="40" height="40" viewBox="-8 -8 80 80"><g fill-rule="evenodd"><path d="M10.61 44.486V23.418c0-2.798 2.198-4.757 5.052-4.757h6.405c1.142-1.915 2.123-5.161 3.055-5.138L40.28 13.5c.79 0 1.971 3.4 3.073 5.14 0 .2 6.51 0 6.51 0 2.856 0 5.136 1.965 5.136 4.757V44.47c-.006 2.803-2.28 4.997-5.137 4.997h-34.2c-2.854.018-5.052-2.184-5.052-4.981zm5.674-23.261c-1.635 0-3.063 1.406-3.063 3.016v19.764c0 1.607 1.428 2.947 3.063 2.947H49.4c1.632 0 2.987-1.355 2.987-2.957v-19.76c0-1.609-1.357-3.016-2.987-3.016h-7.898c-.627-1.625-1.909-4.937-2.28-5.148 0 0-13.19.018-13.055 0-.554.276-2.272 5.143-2.272 5.143l-7.611.01z"></path><path d="M32.653 41.727c-5.06 0-9.108-3.986-9.108-8.975 0-4.98 4.047-8.966 9.108-8.966 5.057 0 9.107 3.985 9.107 8.969 0 4.988-4.047 8.974-9.107 8.974v-.002zm0-15.635c-3.674 0-6.763 3.042-6.763 6.66 0 3.62 3.089 6.668 6.763 6.668 3.673 0 6.762-3.047 6.762-6.665 0-3.616-3.088-6.665-6.762-6.665v.002z"></path></g></svg>
                                <input type="radio" name="avatar" value="custom" checked><?php _e('Default Avatar', 'tt'); ?>
                            </label>
                            <?php }else{ ?>
                            <label class="local-avatar-label" title="<?php _e('Upload Avatar', 'tt'); ?>">
                                <img src="<?php echo $info->local_avatar; ?>" class="avatar" data-filename="<?php echo $tt_user_id . '.jpg'; ?>" width="40" height="40">
                                <svg class="svgIcon-use avatar-picker img-picker" width="40" height="40" viewBox="-8 -8 80 80"><g fill-rule="evenodd"><path d="M10.61 44.486V23.418c0-2.798 2.198-4.757 5.052-4.757h6.405c1.142-1.915 2.123-5.161 3.055-5.138L40.28 13.5c.79 0 1.971 3.4 3.073 5.14 0 .2 6.51 0 6.51 0 2.856 0 5.136 1.965 5.136 4.757V44.47c-.006 2.803-2.28 4.997-5.137 4.997h-34.2c-2.854.018-5.052-2.184-5.052-4.981zm5.674-23.261c-1.635 0-3.063 1.406-3.063 3.016v19.764c0 1.607 1.428 2.947 3.063 2.947H49.4c1.632 0 2.987-1.355 2.987-2.957v-19.76c0-1.609-1.357-3.016-2.987-3.016h-7.898c-.627-1.625-1.909-4.937-2.28-5.148 0 0-13.19.018-13.055 0-.554.276-2.272 5.143-2.272 5.143l-7.611.01z"></path><path d="M32.653 41.727c-5.06 0-9.108-3.986-9.108-8.975 0-4.98 4.047-8.966 9.108-8.966 5.057 0 9.107 3.985 9.107 8.969 0 4.988-4.047 8.974-9.107 8.974v-.002zm0-15.635c-3.674 0-6.763 3.042-6.763 6.66 0 3.62 3.089 6.668 6.763 6.668 3.673 0 6.762-3.047 6.762-6.665 0-3.616-3.088-6.665-6.762-6.665v.002z"></path></g></svg>
                                <input type="radio" name="avatar" value="<?php echo $info->avatar; ?>"><?php _e('Default Avatar', 'tt'); ?>
                            </label><!-- //TODO change the value to custom after upload image finished -->
                            <label  class="current-avatar-label"><img src="<?php echo $info->avatar; ?>" class="avatar" width="40" height="40"><input type="radio" name="avatar" value="<?php echo $info->avatar_type; ?>" checked><?php _e('Default Avatar', 'tt'); ?></label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
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
</div>