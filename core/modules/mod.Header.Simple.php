<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/24 23:00
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php $keywords_description = tt_get_keywords_and_description(); ?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,minimal-ui=yes">
    <title><?php echo tt_get_page_title(); ?></title>
    <meta name="keywords" content="<?php echo $keywords_description['keywords']; ?>">
    <meta name="description" content="<?php echo $keywords_description['description']; ?>">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta http-equiv="Cache-Control" content="no-siteapp"> <!-- 禁止移动端百度转码 -->
    <meta http-equiv="Cache-Control" content="private">
    <!--    <meta http-equiv="Cache-Control" content="max-age=0">-->
    <!--    <meta http-equiv="Cache-Control" content="must-revalidate">-->
    <meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no, email=no"> <!-- 禁止自动识别电话号码和邮箱 -->
    <?php if($favicon = tt_get_option('tt_favicon')) { ?>
        <link rel="shortcut icon" href="<?php echo $favicon; ?>" >
    <?php } ?>
    <?php if($png_favicon = tt_get_option('tt_png_favicon')) { ?>
        <link rel="alternate icon" type="image/png" href="<?php echo $png_favicon; ?>" >
    <?php } ?>
    <!--[if lt IE 9]>
    <script src="<?php echo THEME_ASSET . '/vender/js/html5shiv/3.7.3/html5shiv.min.js'; ?>"></script>
    <script src="<?php echo THEME_ASSET . '/vender/js/respond/1.4.2/respond.min.js'; ?>"></script>
    <![endif]-->
    <!--[if lte IE 7]>
    <script type="text/javascript">
        window.location.href = "<?php echo tt_url_for('upgrade_browser'); ?>";
    </script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo tt_get_css(); ?>"  />
    <!-- 页头自定义代码 -->
    <?php if(tt_get_option('tt_head_code')) { echo tt_get_option('tt_head_code'); } ?>
    <?php wp_head(); ?>
</head>