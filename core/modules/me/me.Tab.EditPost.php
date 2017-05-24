<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/24 19:15
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; $tt_post_id = get_query_var('me_grandchild_route'); ?>
<div class="col col-right contribute">
    <?php $vm = MeEditPostVM::getInstance($tt_post_id, $tt_user_id); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Post info cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $all_categories = $data->all_cats; ?>
    <div class="me-tab-box editpost-tab">
        <div class="tab-content me-editpost">
            <!-- 文章编辑区 -->
            <section class="post-editor clearfix">
                <header><h2><?php _e('Edit Post', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <?php if($data) { ?>
                    <?php if(current_user_can('edit_posts')): ?>
                        <p class="tips"><?php _e('Please save your draft timely in case of missing content', 'tt'); ?></p>
                        <!-- 标题 -->
                        <div class="form-group">
                            <input type="text" class="form-control" name="post_title" placeholder="<?php _e('Input your title here', 'tt');?>" value="<?php echo $data->post_title; ?>" aria-required='true' required>
                        </div>
                        <!-- 内容编辑器 -->
                        <div class="form-group">
                            <?php tt_editor_quicktags(); ?>
                            <?php wp_editor( wpautop($data->post_content), 'post_content', array('media_buttons'=>true, 'quicktags'=>true, 'editor_class'=>'form-control', 'editor_css'=>'<style>.wp-editor-container{border:1px solid #ddd;}.switch-html, .switch-tmce{height:25px !important}</style>' ) ); ?>
                        </div>
                        <!-- 分类选择 -->
                        <div class="separator"></div>
                        <div class="form-group">
                            <label for="cat-selector"><?php _e('Choose the category', 'tt'); ?></label>
                            <select id="cat-selector" name="post_cat" class="form-control">
                                <?php foreach ($all_categories as $category) { ?>
                                    <option value="<?php echo $category->term_id; ?>" <?php if($category->ID == $data->post_cat_id) echo 'selected'; ?>><?php echo $category->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- 标签 -->
                        <div class="form-group">
                            <label for="tags-input"><?php _e('Post tags, comma separate multi tags', 'tt'); ?></label>
                            <input type="text" class="form-control" id="tags-input" name="post_tags" placeholder="" value="<?php echo $data->tags; ?>">
                        </div>
                        <!-- 摘要 -->
                        <div class="form-group">
                            <label for="excerpt-input"><?php _e('Post excerpt', 'tt'); ?></label>
                            <textarea type="text" class="form-control" id="excerpt-input" name="post_excerpt" rows="5" placeholder=""><?php echo $data->post_excerpt; ?></textarea>
                        </div>
                        <!-- 版权信息 - 源文章标题 -->
                        <div class="form-group">
                            <label for="origin-title"><?php _e('Copyright - Original post title', 'tt'); ?></label>
                            <input type="text" class="form-control" id="origin-title" name="origin_title" placeholder="" value="<?php echo $data->cc_title; ?>">
                        </div>
                        <!-- 版权信息 - 源文章链接 -->
                        <div class="form-group">
                            <label for="origin-link"><?php _e('Copyright - Original post link, please leave empty if yours is original', 'tt'); ?></label>
                            <input type="text" class="form-control" id="origin-link" name="origin_link" placeholder="" value="<?php echo $data->cc_link; ?>">
                        </div>
                        <!-- 内嵌免费资源 -->
                        <div class="form-group">
                            <label for="free-downloads"><?php _e('Embed free resources', 'tt'); ?></label>
                            <textarea type="text" class="form-control" id="free-downloads" name="free_downloads" rows="5" placeholder=""><?php echo $data->free_dl; ?></textarea>
                            <p class="help-block"><?php _e('普通下载资源，格式为 资源1名称|资源1url|下载密码,资源2名称|资源2url|下载密码 资源名称与url用|隔开，不同资源用英文逗号隔开，url请添加http://头，如提供百度网盘加密下载可以填写密码，也可以留空', 'tt'); ?></p>
                        </div>
                        <!-- 内嵌付费资源 -->
                        <div class="form-group">
                            <label for="sale-downloads"><?php _e('Embed sale resources', 'tt'); ?></label>
                            <textarea type="text" class="form-control" id="sale-downloads" name="sale_downloads" rows="5" placeholder=""><?php echo $data->sale_dl; ?></textarea>
                            <p class="help-block"><?php _e('积分下载资源，格式为 资源1名称|资源1url|资源1价格|下载密码,资源2名称|资源2url|资源2价格|下载密码 资源名称与url以及价格、下载密码用|隔开，不同资源用英文逗号隔开', 'tt'); ?></p>
                        </div>
                        <!-- 提交按钮 -->
                        <div class="separator"></div>
                        <div class="form-inline text-right pull-right submit-form">
                            <div class="form-group">
                                <select name="post_status" class="form-control">
                                    <option value ="pending"><?php _e('Submit for review', 'tt');?></option>
                                    <option value ="draft"><?php _e('Save draft', 'tt');?></option>
                                    <?php if(current_user_can('publish_posts')) { ?>
                                        <option value ="publish"><?php _e('Publish post', 'tt');?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="post_id" value="<?php echo $tt_post_id; ?>">
                            <button class="btn btn-success" id="submit-post"><?php _e('Confirm Action', 'tt');?></button>
                        </div>
                    <?php else: ?>
                        <div class="warning">
                            <span class="tico tico-quill"></span>
                            <p><?php _e('Sorry, you do not have the capability to edit the post', 'tt'); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php }else{ ?>
                    <div class="warning">
                        <span class="tico tico-alert-circle"></span>
                        <p><?php _e('The post you specified is not existed', 'tt'); ?></p>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </div>
    </div>
</div>