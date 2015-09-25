<?php
/**
 * Store Category Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.4
 * @date      2015.1.13
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<?php get_header(); ?>
<!-- Main Wrap -->
<div id="main-wrap" style="margin-top:-60px;background:#f1f1f1;">
<div class="sub-billboard billboard shopping">
  <div class="wrapper">
    <div class="inner">
    <h1><?php echo ot_get_option('shop_archives_title','WordPress商店'); ?></h1>
    <p><?php echo ot_get_option('shop_archives_sub_title','Theme - Service - Resource'); ?></p>
    </div>
  </div>
</div>
<div class="container shop centralnav">
	<div id="guide" class="navcaret">
        <div class="group">
            <?php wp_nav_menu( array( 'theme_location' => 'shopcatbar', 'container' => '', 'menu_id' => '', 'menu_class' => 'clr', 'depth' => '1', 'fallback_cb' => ''  ) ); ?>
        </div>
	</div>
	<div id="goodslist" class="goodlist" role="main">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="col span_1_of_4" role="main">
			<div class="shop-item">
				<?php get_template_part('includes/thumbnail'); ?>
				<h3>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h3>
				<p>
					<?php $contents = get_the_excerpt(); $excerpt = wp_trim_words($contents,50,'...'); echo $excerpt;?>
				</p>
				<div class="pricebtn"><?php $currency = get_post_meta($post->ID,'pay_currency',true); if($currency==1) echo '￥'; else echo '<i class="fa fa-gift">&nbsp;</i>'; ?><strong><?php echo tin_get_product_price($post->ID); ?></strong><a class="buy" href="<?php the_permalink(); ?>">前往购买</a></div>
			</div>
		</div>
	<?php endwhile;endif;?>
    </div>

<!-- pagination -->
<div class="clear">
</div>
<div class="pagination">
<?php pagenavi(); ?>
</div>
<!-- /.pagination -->


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
<?php }?>
<!-- /.Bottom Banner -->
<?php if(ot_get_option('footer-widgets-singlerow') == 'on'){?>
<div id="ft-wg-sr">
	<div class="container">
	<?php dynamic_sidebar( 'footer-row'); ?>
	</div>
</div>
<?php }?>
<?php get_footer(); ?>