<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.3
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/07 21:38
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; $tt_mg_uid = get_query_var('manage_grandchild_route'); ?>
<div class="col col-right user">
    <?php $vm = MgUserVM::getInstance($tt_mg_uid); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- User detail cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; ?>
    <div class="mg-tab-box user-tab">
        <div class="tab-content">
            <?php if($data) { ?>
                <!-- 用户信息 -->
                <section class="mg-user clearfix">
                    <header><h2><?php _e('User Detail', 'tt'); ?></h2></header>
                    <div class="info-group clearfix">
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('User ID', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->ID; ?></p></div>
                        </div>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('User Display Name', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->display_name; ?></p></div>
                        </div>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('User Email', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->email; ?></p></div>
                        </div>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Register Time', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->member_since; ?><?php printf(__(' <b>(%d days)</b>', 'tt'), $data->member_days); ?></p></div>
                        </div>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Last Login', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->latest_login; ?></p></div>
                        </div>
                    </div>
                </section>
                <!-- 积分管理 -->
                <section class="mg-credits clearfix">
                    <header><h2><?php _e('User Credits', 'tt'); ?></h2></header>
                    <div class="info-group clearfix">
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Current Credits', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->credit_balance; ?></p></div>
                        </div>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Consumed Credits', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->credit_consumed; ?></p></div>
                        </div>
                        <div class="form-group add-credits-form">
                            <div class="form-inline">
                                <div class="form-group">
                                    <div class="input-group active">
                                        <div class="input-group-addon"><?php _e('Credits', 'tt'); ?></div>
                                        <input class="form-control" type="text" name="credits-num" value="100" aria-required="true" required="">
                                    </div>
                                </div>
                                <button class="btn btn-inverse" type="submit" id="add-credits" data-uid="<?php echo $data->ID; ?>"><?php _e('ADD CREDITS', 'tt'); ?></button>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- 会员管理 -->
                <section class="mg-membership clearfix">
                    <header><h2><?php _e('User Membership', 'tt'); ?></h2></header>
                    <div class="info-group clearfix">
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Member Type', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo tt_get_member_type_string($data->member_type); ?></p></div>
                        </div>
                        <?php if($data->is_vip) { ?>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Member Status', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->member_status; ?></p></div>
                        </div>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Join Time', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->join_time; ?></p></div>
                        </div>
                        <div class="row clearfix">
                            <label class="col-md-3 control-label"><?php _e('Expire Date', 'tt'); ?></label>
                            <div class="col-md-9"><p class="form-control-static"><?php echo $data->end_time; ?></p></div>
                        </div>
                        <?php } ?>
                        <div class="form-group promote-vip-form">
                            <label class="radio-inline"><input type="radio" name="vip_product_id" value="-1" aria-required="true" required checked><?php printf(__('Monthly VIP', 'tt'), tt_get_vip_price(Member::MONTHLY_VIP)); ?></label>
                            <label class="radio-inline"><input type="radio" name="vip_product_id" value="-2" aria-required="true" required><?php printf(__('Annual VIP', 'tt'), tt_get_vip_price(Member::ANNUAL_VIP)); ?></label>
                            <label class="radio-inline"><input type="radio" name="vip_product_id" value="-3" aria-required="true" required><?php printf(__('Permanent VIP', 'tt'), tt_get_vip_price(Member::PERMANENT_VIP)); ?></label>
                            <button class="btn btn-success" id="promotevip-submit" data-uid="<?php echo $data->ID; ?>"><?php _e('PROMOTE VIP', 'tt'); ?></button>
                            <p class="help-block"><?php _e('提示:若已开通会员则按照选择开通的类型自动续费,若会员已到期,则按重新开通计算有效期', 'tt'); ?></p>
                        </div>
                    </div>
                </section>
                <?php if($latest_orders = $data->latest_orders) { ?>
                <!-- 近期订单 -->
                <section class="mg-orders clearfix">
                    <header><h2><?php _e('Latest Orders', 'tt'); ?></h2></header>
                    <div class="info-group clearfix">
                        <ul>
                        <?php foreach ($latest_orders as $latest_order) { ?>
                            <li>
                                <span class="order-time"><?php echo $latest_order['time']; ?></span>
                                <span class="order-title"><a href="<?php echo $latest_order['mgUrl']; ?>" target="_blank"><?php echo $latest_order['title']; ?></a></span>
                            </li>
                        <?php } ?>
                        </ul>
                    </div>
                </section>
                <?php } ?>
            <?php }else{ ?>
            <section class="mg-user clearfix">
                <header><h2><?php _e('User Detail', 'tt'); ?></h2></header>
                <div class="empty-content">
                    <span class="tico tico-user"></span>
                    <p><?php _e('No this user', 'tt'); ?></p>
                </div>
                <?php } ?>
        </div>
    </div>
</div>