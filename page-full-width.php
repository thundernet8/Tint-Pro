<?php
/*
Template Name: 全宽空白模板
*/
?>
<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.9
 * @date      2015.6.27
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<?php get_header(); ?>
<?php get_template_part( 'includes/breadcrumbs');?>
<!-- Header Banner -->
<?php $headerad=ot_get_option('headerad');if (!empty($headerad)) {?>
<div id="header-banner">
	<div class="container">
		<?php echo ot_get_option('headerad');?>
	</div>
</div>
<?php }?>
<!-- /.Header Banner -->
<!-- Main Wrap -->
<div id="main-wrap">
	<div id="single-blog-wrap" class="container-full">
		<!-- Content -->
		<div class="content">
		<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
		</div>
		<!-- /.Content -->
		<!-- Comments -->
		<div class="container"><?php if (comments_open()) comments_template( '', true ); ?></div>
		<!-- /.Comments -->
		<?php endwhile; ?>
	</div>
</div>
<!--/.Main Wrap -->
<!-- Bottom Banner -->
<?php $bottomad=ot_get_option('bottomad');if (!empty($bottomad)) {?>
<div id="bottom-banner">
	<div class="container">
		<?php echo ot_get_option('bottomad');?>
	</div>
</div>
<?php }else{?>
<div style="height:10px;"></div>
<?php }?>
<!-- Bottom Banner -->
<?php $bottomad=ot_get_option('bottomad');if (!empty($bottomad)) {?>
<div id="bottom-banner">
	<div class="container">
		<?php echo ot_get_option('bottomad');?>
	</div>
</div>
<?php } ?>
<!-- /.Bottom Banner -->
<?php get_footer(); ?>