<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 19:46
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_mg_vars; $tt_user_id = $tt_mg_vars['tt_user_id']; $tt_page = $tt_mg_vars['tt_paged']; ?>
<div class="col col-right members">
    <?php $vm = MgMembersVM::getInstance($tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Manage members cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $members = $data->members; $count = $data->count; $max_pages = $data->max_pages; ?>
    <div class="mg-tab-box members-tab">
        <div class="tab-content">
            <!-- 添加会员 -->
            <section class="mg-member clearfix">
                <header><h2><?php _e('Add Member', 'tt'); ?></h2></header>
                <div class="form-group info-group clearfix">
                    <div class="member-radios">
                        <?php _e('Member VIP Type', 'tt'); ?>
                        <label class="radio-inline">
                            <input type="radio" name="member_type" value="<?php echo Member::MONTHLY_VIP; ?>" checked><?php _e('MONTHLY VIP', 'tt'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="member_type" value="<?php echo Member::ANNUAL_VIP; ?>"><?php _e('ANNUAL VIP', 'tt'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="member_type" value="<?php echo Member::PERMANENT_VIP; ?>"><?php _e('PERMANENT VIP', 'tt'); ?>
                        </label>
                    </div>
                </div>
                <div class="form-group info-group clearfix">
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="input-group active">
                                <div class="input-group-addon" style="background-color: #788b90;border-color: #788b90;"><?php _e('User name or ID', 'tt'); ?></div>
                                <input class="form-control" type="text" name="user" value="" aria-required="true" required>
                            </div>
                        </div>
                        <button class="btn btn-inverse" type="submit" id="add-member"><?php _e('ADD', 'tt'); ?></button>
                    </div>
                    <p class="help-block"><?php _e('请提供要提升会员用户的登录名或用户ID', 'tt'); ?></p>
                </div>
            </section>
            <!-- 会员列表 -->
            <section class="mg-members clearfix">
                <header><h2><?php _e('Members List', 'tt'); ?></h2></header>
                <?php if($count > 0) { ?>
                    <table class="table table-striped table-framed table-centered">
                        <thead>
                        <tr>
                            <th class="th-sid"><?php _e('Sequence', 'tt'); ?></th>
                            <th class="th-uid"><?php _e('User ID', 'tt'); ?></th>
                            <th class="th-uname"><?php _e('User Name', 'tt'); ?></th>
                            <th class="th-type"><?php _e('Member Type', 'tt'); ?></th>
                            <th class="th-effect"><?php _e('Member Effect Date', 'tt'); ?></th>
                            <th class="th-expire"><?php _e('Member Expire Date', 'tt'); ?></th>
                            <th class="th-actions"><?php _e('Actions', 'tt'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $seq = 0; ?>
                        <?php foreach ($members as $member){ ?>
                            <?php $seq++; ?>
                            <tr id="mid-<?php echo $member->id; ?>">
                                <td><?php echo $seq; ?></td>
                                <td><?php echo $member->user_id; ?></td>
                                <td><?php echo get_user_meta($member->user_id, 'nickname', true); ?></td>
                                <td><?php echo tt_get_member_type_string($member->user_type); ?></td>
                                <td><?php echo $member->startTime ?></td>
                                <td><?php echo $member->endTime ?></td>
                                <td>
                                    <div class="member-actions">
                                        <a class="view-detail" href="<?php echo get_author_posts_url($member->user_id); ?>" title="<?php _e('View the user homepage', 'tt'); ?>" target="_blank"><?php _e('View User', 'tt'); ?></a>
                                        <a class="delete-member" href="javascript:;" data-member-action="delete" data-member-id="<?php echo $member->id; ?>" title="<?php _e('Delete the member', 'tt'); ?>"><?php _e('Delete', 'tt'); ?></a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php if($max_pages > 1) { ?>
                        <div class="pagination-mini clearfix">
                            <?php if($tt_page == 1) { ?>
                                <div class="col-md-3 prev disabled"><a href="javascript:;"><?php _e('← 上一页', 'tt'); ?></a></div>
                            <?php }else{ ?>
                                <div class="col-md-3 prev"><a href="<?php echo $data->prev_page; ?>"><?php _e('← 上一页', 'tt'); ?></a></div>
                            <?php } ?>
                            <div class="col-md-6 page-nums">
                                <span class="current-page"><?php printf(__('Current Page %d', 'tt'), $tt_page); ?></span>
                                <span class="separator">/</span>
                                <span class="max-page"><?php printf(__('Total %d Pages', 'tt'), $max_pages); ?></span>
                            </div>
                            <?php if($tt_page != $data->max_pages) { ?>
                                <div class="col-md-3 next"><a href="<?php echo $data->next_page; ?>"><?php _e('下一页 →', 'tt'); ?></a></div>
                            <?php }else{ ?>
                                <div class="col-md-3 next disabled"><a href="javascript:;"><?php _e('下一页 →', 'tt'); ?></a></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="empty-content">
                        <span class="tico tico-users"></span>
                        <p><?php _e('Nothing found here', 'tt'); ?></p>
                        <!--                        <a class="btn btn-info" href="/">--><?php //_e('Back to home', 'tt'); ?><!--</a>-->
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
</div>