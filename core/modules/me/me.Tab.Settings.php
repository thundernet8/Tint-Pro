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
 * @link https://webapproach.net/tint.html
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
                    <label class="col-md-2 control-label"><?php _e('Avatar', 'tt'); ?></label>
                    <div class="col-md-10">
                        <div class="radio">
                            <label class="local-avatar-label" title="<?php _e('Upload Avatar', 'tt'); ?>">
                                <img src="<?php echo $info->custom_avatar; ?>" class="avatar" data-filename="<?php echo $tt_user_id . '.jpg'; ?>" width="40" height="40">
                                <span class="avatar-picker img-picker"></span>
                                <svg class="svgIcon-use" width="40" height="40" viewBox="-8 -8 80 80"><g fill-rule="evenodd"><path d="M10.61 44.486V23.418c0-2.798 2.198-4.757 5.052-4.757h6.405c1.142-1.915 2.123-5.161 3.055-5.138L40.28 13.5c.79 0 1.971 3.4 3.073 5.14 0 .2 6.51 0 6.51 0 2.856 0 5.136 1.965 5.136 4.757V44.47c-.006 2.803-2.28 4.997-5.137 4.997h-34.2c-2.854.018-5.052-2.184-5.052-4.981zm5.674-23.261c-1.635 0-3.063 1.406-3.063 3.016v19.764c0 1.607 1.428 2.947 3.063 2.947H49.4c1.632 0 2.987-1.355 2.987-2.957v-19.76c0-1.609-1.357-3.016-2.987-3.016h-7.898c-.627-1.625-1.909-4.937-2.28-5.148 0 0-13.19.018-13.055 0-.554.276-2.272 5.143-2.272 5.143l-7.611.01z"></path><path d="M32.653 41.727c-5.06 0-9.108-3.986-9.108-8.975 0-4.98 4.047-8.966 9.108-8.966 5.057 0 9.107 3.985 9.107 8.969 0 4.988-4.047 8.974-9.107 8.974v-.002zm0-15.635c-3.674 0-6.763 3.042-6.763 6.66 0 3.62 3.089 6.668 6.763 6.668 3.673 0 6.762-3.047 6.762-6.665 0-3.616-3.088-6.665-6.762-6.665v.002z"></path></g></svg>
                                <input type="radio" name="avatar" value="custom" <?php if($info->avatar_type=='custom') echo 'checked'; ?>><?php _e('Custom Avatar', 'tt'); ?>
                            </label>
                            <label  class="letter-avatar-label"><img src="<?php echo $info->letter_avatar; ?>" class="avatar" width="40" height="40"><input type="radio" name="avatar" value="letter" <?php if($info->avatar_type=='letter') echo 'checked'; ?>><?php _e('Letter Avatar', 'tt'); ?></label>
                            <?php if(isset($info->qq_avatar)) { ?>
                            <label  class="qq-avatar-label"><img src="<?php echo $info->qq_avatar; ?>" class="avatar" width="40" height="40"><input type="radio" name="avatar" value="qq" <?php if($info->avatar_type=='qq') echo 'checked'; ?>><?php _e('QQ Avatar', 'tt'); ?></label>
                            <?php } ?>
                            <?php if(isset($info->weibo_avatar)) { ?>
                                <label  class="weibo-avatar-label"><img src="<?php echo $info->weibo_avatar; ?>" class="avatar" width="40" height="40"><input type="radio" name="avatar" value="weibo" <?php if($info->avatar_type=='weibo') echo 'checked'; ?>><?php _e('Weibo Avatar', 'tt'); ?></label>
                            <?php } ?>
                            <?php if(isset($info->weixin_avatar)) { ?>
                                <label  class="weixin-avatar-label"><img src="<?php echo $info->weixin_avatar; ?>" class="avatar" width="40" height="40"><input type="radio" name="avatar" value="weixin" <?php if($info->avatar_type=='weixin') echo 'checked'; ?>><?php _e('Weixin Avatar', 'tt'); ?></label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Nickname', 'tt'); ?></label>
                    <p class="col-md-10"><input name="nickname" type="text" value="<?php echo $info->nickname; ?>" placeholder="" class="form-control"></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site', 'tt'); ?></label>
                    <p class="col-md-10"><input name="user_url" type="text" value="<?php echo $info->site; ?>" placeholder="" class="form-control"></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Description', 'tt'); ?></label>
                    <p class="col-md-10"><textarea name="description" type="text" placeholder="<?php _e('Add your bio...', 'tt'); ?>" class="form-control" rows="5"><?php echo $info->description; ?></textarea></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-sm-10">
                        <a href="javascript:;" class="btn btn-primary btn-save-settings" data-save-info="basis"><?php _e('Save Profile', 'tt'); ?></a>
                    </div>
                </div>
            </section>
            <!-- 扩展信息 -->
            <section class="info-extends clearfix">
                <header><h2><?php _e('Extended Info', 'tt'); ?><small><?php _e('e.g. Social Infos', 'tt'); ?></small></h2></header>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('QQ', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_qq" type="text" value="<?php echo $info->qq; ?>" placeholder="" class="form-control">
                        <!--span class="help-block"><?php //_e('', 'tt'); ?></span-->
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Sina Weibo', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_weibo" type="text" value="<?php echo $info->weibo; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('微博用户名, 如weibo.com/touchumind中的touchumind', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Wechat Qrcode', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_weixin" type="text" value="<?php echo $info->weixin; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('微信二维码图片地址', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Twitter', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_twitter" type="text" value="<?php echo $info->twitter; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('Twitter用户名, 如twitter.com/thundernet8中的thundernet8', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Facebook', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_facebook" type="text" value="<?php echo $info->facebook; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('Facebook用户名, 如www.facebook.com/xueqian.wu中的xueqian.wu', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Google+', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_googleplus" type="text" value="<?php echo $info->googleplus; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('Google+用户ID, 如plus.google.com/u/0/103638104473894849180中的103638104473894849180', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Alipay Account', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_alipay_email" type="text" value="<?php echo $info->alipay_email; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('支付宝账户邮箱或手机, 推荐邮箱', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Alipay Gather Qrcode', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_alipay_pay_qr" type="text" value="<?php echo $info->alipay_pay; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('支付宝收款二维码地址', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Wechat Gather Qrcode', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="tt_wechat_pay_qr" type="text" value="<?php echo $info->wechat_pay; ?>" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('微信收款二维码地址', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-sm-10">
                        <a href="javascript:;" class="btn btn-success btn-save-settings" data-save-info="extends"><?php _e('Save Extended Profile', 'tt'); ?></a>
                    </div>
                </div>
            </section>
            <!-- 绑定账号 -->
            <?php
                $open_weibo = tt_get_option('tt_enable_weibo_login');
                $open_qq = tt_get_option('tt_enable_qq_login');
                $open_weixin = tt_get_option('tt_enable_weixin_login');
                $has_open_login = $open_weibo || $open_qq || $open_weixin;
            ?>
            <?php if($has_open_login) { ?>
                <section class="info-bind clearfix">
                    <header><h2><?php _e('Bind Account', 'tt'); ?><small><?php _e('Used for quick login', 'tt'); ?></small></h2></header>
                    <?php if($open_qq) { ?>
                    <div class="form-group info-group clearfix">
                        <label class="col-md-2 control-label"><?php _e('QQ Social Account', 'tt'); ?></label>
                        <p class="col-md-10">
                            <?php if(tt_has_connect('qq', $tt_user_id)) { ?>
                            <a class="btn btn-wide btn-danger btn-disconnect-qq" href="<?php echo tt_url_for('oauth_qq_disconnect'); ?>"><?php _e('Disconnect QQ', 'tt'); ?></a>
                            <?php }else{ ?>
                            <a class="btn btn-wide btn-social-qq btn-connect-qq" href="<?php echo tt_url_for('oauth_qq'); ?>"><?php _e('Connect QQ', 'tt'); ?></a>
                            <?php } ?>
                        </p>
                    </div>
                    <?php } ?>
                    <?php if($open_weibo) { ?>
                        <div class="form-group info-group clearfix">
                            <label class="col-md-2 control-label"><?php _e('Weibo Social Account', 'tt'); ?></label>
                            <p class="col-md-10">
                                <?php if(tt_has_connect('weibo', $tt_user_id)) { ?>
                                    <a class="btn btn-wide btn-danger btn-disconnect-weibo" href="<?php echo tt_url_for('oauth_weibo_disconnect'); ?>"><?php _e('Disconnect Weibo', 'tt'); ?></a>
                                <?php }else{ ?>
                                    <a class="btn btn-wide btn-social-weibo btn-connect-weibo" href="<?php echo tt_url_for('oauth_weibo'); ?>"><?php _e('Connect Weibo', 'tt'); ?></a>
                                <?php } ?>
                            </p>
                        </div>
                    <?php } ?>
                    <?php if($open_weixin) { ?>
                        <div class="form-group info-group clearfix">
                            <label class="col-md-2 control-label"><?php _e('Wechat Social Account', 'tt'); ?></label>
                            <p class="col-md-10">
                                <?php if(tt_has_connect('weixin', $tt_user_id)) { ?>
                                    <a class="btn btn-wide btn-danger btn-disconnect-weixin" href="<?php echo tt_url_for('oauth_weixin_disconnect'); ?>"><?php _e('Disconnect Wechat', 'tt'); ?></a>
                                <?php }else{ ?>
                                    <a class="btn btn-wide btn-social-weixin btn-connect-weibo" href="<?php echo tt_url_for('oauth_weixin'); ?>"><?php _e('Connect Wechat', 'tt'); ?></a>
                                <?php } ?>
                            </p>
                        </div>
                    <?php } ?>
                </section>
            <?php } ?>
            <!-- 账户安全 -->
            <section class="info-security clearfix" id="securityInfo">
                <header><h2><?php _e('Account Security', 'tt'); ?><small><?php _e('Be careful', 'tt'); ?></small></h2></header>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Email (Required)', 'tt'); ?></label>
                    <p class="col-md-10"><input name="user_email" type="text" value="<?php echo $info->email; ?>" placeholder="" class="form-control"></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('New Password', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="password" type="password" value="" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('如果您想修改您的密码, 请在此输入新密码, 否则请留空.', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Repeat Password', 'tt'); ?></label>
                    <p class="col-md-10">
                        <input name="password2" type="password" value="" placeholder="" class="form-control">
                        <span class="help-block"><?php _e('再输入一遍新密码. 提示: 您的密码最好至少包含7个字符. 为了保证密码强度, 使用大小写字母、数字和符号（例如! " ? $ % ^ &amp; ) ).', 'tt'); ?></span>
                    </p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-sm-10">
                        <a href="javascript:;" class="btn btn-danger btn-save-settings" data-save-info="security"><?php _e('Save Security Info', 'tt'); ?></a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>