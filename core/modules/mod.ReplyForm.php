<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/09 13:34
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php
global $postdata;
?>
<div class="submit-box comment-form clearfix" id="comment-form">
    <?php //comment_id_fields(); ?>
    <input type="hidden" name="comment_post_ID" value="<?php echo $postdata->ID; ?>" id="comment_post_ID">
    <input type="hidden" name="comment_parent" id="comment_parent" value="0">
    <input type="hidden" name="tt_comment_nonce" id="comment_nonce" value="<?php echo wp_create_nonce('tt_comment_nonce'); ?>">
    <?php do_action('comment_form', $postdata->ID); ?>
    <div class="text">
        <?php if(is_user_logged_in()) { ?>
        <textarea name="comment" placeholder="<?php _e('Leave some words...', 'tt'); ?>" id="comment-text" required></textarea>
        <?php }else{ ?>
        <textarea name="comment" placeholder="<?php _e('Signin and Leave some words...', 'tt'); ?>" id="comment-text" required></textarea>
        <?php } ?>
    </div>
    <?php if(is_user_logged_in()) { ?>
    <button class="btn btn-info comment-submit" id="submit" type="submit" title="<?php _e('Submit', 'tt'); ?>"><?php _e('Submit', 'tt'); ?></button>
    <div class="err text-danger"></div>
    <div class="comment-kits">
            <span class="emotion-ico transition" data-emotion="0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="tico tico-smile-o"></i><?php _e('Emotion', 'tt'); ?></span>
            <!--        <div class="qqFace dropdown-menu">-->
            <!--            <table border="0" cellspacing="0" cellpadding="0">-->
            <!--                <tbody>-->
            <!--                    <tr>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/1.gif'; ?><!--" data-code="[em_1]"></td>																				<td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/2.gif'; ?><!--" data-code="[em_2]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/3.gif'; ?><!--" data-code="[em_3]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/4.gif'; ?><!--" data-code="[em_4]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/5.gif'; ?><!--" data-code="[em_5]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/6.gif'; ?><!--" data-code="[em_6]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/7.gif'; ?><!--" data-code="[em_7]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/8.gif'; ?><!--" data-code="[em_8]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/9.gif'; ?><!--" data-code="[em_9]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/10.gif'; ?><!--" data-code="[em_10]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/11.gif'; ?><!--" data-code="[em_11]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/12.gif'; ?><!--" data-code="[em_12]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/13.gif'; ?><!--" data-code="[em_13]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/14.gif'; ?><!--" data-code="[em_14]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/15.gif'; ?><!--" data-code="[em_15]"></td>-->
            <!--                    </tr>-->
            <!--                    <tr>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/16.gif'; ?><!--" data-code="[em_16]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/17.gif'; ?><!--" data-code="[em_17]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/18.gif'; ?><!--" data-code="[em_18]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/19.gif'; ?><!--" data-code="[em_19]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/20.gif'; ?><!--" data-code="[em_20]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/21.gif'; ?><!--" data-code="[em_21]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/22.gif'; ?><!--" data-code="[em_22]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/23.gif'; ?><!--" data-code="[em_23]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/24.gif'; ?><!--" data-code="[em_24]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/25.gif'; ?><!--" data-code="[em_25]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/26.gif'; ?><!--" data-code="[em_26]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/27.gif'; ?><!--" data-code="[em_27]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/28.gif'; ?><!--" data-code="[em_28]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/29.gif'; ?><!--" data-code="[em_29]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/30.gif'; ?><!--" data-code="[em_30]"></td>-->
            <!--                    </tr>-->
            <!--                    <tr>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/31.gif'; ?><!--" data-code="[em_31]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/32.gif'; ?><!--" data-code="[em_32]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/33.gif'; ?><!--" data-code="[em_33]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/34.gif'; ?><!--" data-code="[em_34]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/35.gif'; ?><!--" data-code="[em_35]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/36.gif'; ?><!--" data-code="[em_36]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/37.gif'; ?><!--" data-code="[em_37]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/38.gif'; ?><!--" data-code="[em_38]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/39.gif'; ?><!--" data-code="[em_39]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/40.gif'; ?><!--" data-code="[em_40]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/41.gif'; ?><!--" data-code="[em_41]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/42.gif'; ?><!--" data-code="[em_42]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/43.gif'; ?><!--" data-code="[em_43]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/44.gif'; ?><!--" data-code="[em_44]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/45.gif'; ?><!--" data-code="[em_45]"></td>-->
            <!--                    </tr>-->
            <!--                    <tr>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/46.gif'; ?><!--" data-code="[em_46]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/47.gif'; ?><!--" data-code="[em_47]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/48.gif'; ?><!--" data-code="[em_48]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/49.gif'; ?><!--" data-code="[em_49]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/50.gif'; ?><!--" data-code="[em_50]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/51.gif'; ?><!--" data-code="[em_51]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/52.gif'; ?><!--" data-code="[em_52]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/53.gif'; ?><!--" data-code="[em_53]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/54.gif'; ?><!--" data-code="[em_54]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/55.gif'; ?><!--" data-code="[em_55]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/56.gif'; ?><!--" data-code="[em_56]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/57.gif'; ?><!--" data-code="[em_57]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/58.gif'; ?><!--" data-code="[em_58]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/59.gif'; ?><!--" data-code="[em_59]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/60.gif'; ?><!--" data-code="[em_60]"></td>-->
            <!--                    </tr>-->
            <!--                    <tr>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/61.gif'; ?><!--" data-code="[em_61]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/62.gif'; ?><!--" data-code="[em_62]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/63.gif'; ?><!--" data-code="[em_63]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/64.gif'; ?><!--" data-code="[em_64]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/65.gif'; ?><!--" data-code="[em_65]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/66.gif'; ?><!--" data-code="[em_66]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/67.gif'; ?><!--" data-code="[em_67]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/68.gif'; ?><!--" data-code="[em_68]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/69.gif'; ?><!--" data-code="[em_69]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/70.gif'; ?><!--" data-code="[em_70]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/71.gif'; ?><!--" data-code="[em_71]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/72.gif'; ?><!--" data-code="[em_72]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/73.gif'; ?><!--" data-code="[em_73]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/74.gif'; ?><!--" data-code="[em_74]"></td>-->
            <!--                        <td><img src="--><?php //echo THEME_ASSET . '/img/qqFace/75.gif'; ?><!--" data-code="[em_75]"></td>-->
            <!--                    </tr>-->
            <!--                </tbody>-->
            <!--            </table>-->
            <!--        </div>-->
            <div class="qqFace dropdown-menu" data-inputbox-id="comment-text"></div>
        </div>
    <?php }else{ ?>
    <button class="btn btn-success comment-submit" id="submit" type="submit" title="<?php _e('Submit', 'tt'); ?>" disabled><?php _e('Submit', 'tt'); ?></button>
    <?php } ?>
</div>