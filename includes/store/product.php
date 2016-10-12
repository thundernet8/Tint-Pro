<?php
/**
 * Single Product Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.8
 * @date      2015.6.5
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<?php get_header(); ?>
<!-- Main Wrap -->
<div id="main-wrap">
	<div id="single-blog-wrap" class="container shop">
		<div class="area">
		<!-- Content -->
		<div class="product-content">
			<div class="breadcrumb">
				<a href="<?php echo get_bloginfo('url').'/store'; ?>"><?php _e('商店','tinection'); ?></a>&nbsp;»&nbsp;<span><?php echo get_the_term_list($post,'products_category','','|'); ?></span>
			</div>
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="<?php echo 'product-'.$post->ID; ?>" class="product">
				<div class="preview">
					<?php if(has_post_thumbnail()){$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');$imgsrc = $large_image_url[0];}else{$imgsrc = catch_first_image();} ?>
					<img src="<?php echo tin_thumb_source($imgsrc,360,300); ?>" alt="<?php the_title(); ?>">
					<div class="view-share">
						<p class="view"><?php _e('人气：','tinection'); ?><?php echo get_tin_traffic( 'single' , $post->ID ); ?></p>
						<div class="share">
							<div id="bdshare" class="bdshare_t get-codes-bdshare baidu-share" data-bd-bind="1421241759771"><span><?php _e('分享：','tinection'); ?></span>
								<a href="#" class="bds_qzone" data-cmd="qzone" title="<?php _e('分享到QQ空间','tinection'); ?>"></a>
								<a href="#" class="bds_tsina" data-cmd="tsina" title="<?php _e('分享到新浪微博','tinection'); ?>"></a>
								<a href="#" class="bds_tqq" data-cmd="tqq" title="<?php _e('分享到腾讯微博','tinection'); ?>"></a>
								<a href="#" class="bds_weixin weixin-btn" data-cmd="weixin" title="<?php _e('分享到微信','tinection'); ?>">
									<div id="weixin-qt" style="display: none; top: 80px; opacity: 1;">
										<img src="http://qr.liantu.com/api.php?text=<?php the_permalink(); ?>" width="120">
										<div id="weixin-qt-msg"><?php _e('打开微信，点击底部的“发现”，使用“扫一扫”即可将网页分享至朋友圈。','tinection'); ?></div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="property">
					<div class="title row">
						<h1><?php the_title(); ?></h1>
						<p><?php $contents = get_the_excerpt(); $excerpt = wp_trim_words($contents,50,''); echo $excerpt;?></p>
					</div>
					<div class="summary row">
						<ul>
							<?php $currency = get_post_meta($post->ID,'pay_currency',true); ?>
							<?php $discount_arr = product_smallest_price($post->ID);if($discount_arr[3]==0&&$discount_arr[4]==0){?>
							<li class="summary-price"><span class="dt"><?php _e('商品售价','tinection'); ?></span><strong><?php if($currency==1)echo '<em>¥</em>'.sprintf('%0.2f',$discount_arr[0]).'<em>(元)</em>'; else echo '<em><i class="fa fa-gift"></i></em>'.sprintf('%0.2f',$discount_arr[0]).'<em>(积分)</em>';?></strong></li>
							<?php }else{ ?>
							<li class="summary-price"><span class="dt"><?php _e('商品售价','tinection'); ?></span><strong><?php if($currency==1)echo '<em>¥</em><del>'.sprintf('%0.2f',$discount_arr[0]).'</del><em>(元)</em>'; else echo '<em><i class="fa fa-gift"></i></em><del>'.sprintf('%0.2f',$discount_arr[0]).'</del><em>(积分)</em>';?></strong><?php if($discount_arr[4]!=0){?><strong><?php echo '&nbsp;'.sprintf('%0.2f',$discount_arr[2]); ?></strong><span><?php _e('(限时特惠)','tinection'); ?></span><?php }?></li>
							<?php if($discount_arr[3]!=0){?>
							<li class="summary-price"><span class="dt"><?php _e('会员特惠','tinection'); ?></span><?php if(getUserMemberType()) { ?><strong><?php if($currency==1)echo '<em>¥</em>'.sprintf('%0.2f',$discount_arr[1]).'<em>(元)</em>'; else echo '<em><i class="fa fa-gift"></i></em>'.sprintf('%0.2f',$discount_arr[1]).'<em>(积分)</em>';?></strong><?php }else if(is_user_logged_in()){echo sprintf(__('非<a href="%1$s" target="_blank" title="开通会员">会员</a>不能享受该优惠','tinection'),tin_get_user_url('membership'));} else {_e('<a href="javascript:" class="user-login">登录</a> 查看优惠','tinection');} ?><?php }?></li>
							<?php }?>
							<li class="summary-amount"><span class="dt"><?php _e('商品数量','tinection'); ?></span><span class="dt-num"><?php $amount = get_post_meta($post->ID,'product_amount',true) ? (int)get_post_meta($post->ID,'product_amount',true):0; echo $amount; ?></span></li>
							<li class="summary-sales"><span class="dt"><?php _e('商品销量','tinection'); ?></span><span class="dt-num"><?php $sales = get_post_meta($post->ID,'product_sales',true) ? (int)get_post_meta($post->ID,'product_sales',true):0; echo $sales; ?></span></li>
							<li class="summary-market"><span class="dt"><?php _e('商品编号','tinection'); ?></span><?php echo $post->ID; ?></li>
                        </ul>
					</div>
					<div class="amount row"><span class="dt"><?php _e('数量','tinection'); ?></span>
						<div class="amount-number">
							<a href="javascript:" hidefocus="true" field="amountquantity" id="minus" class="control minus"><i class="fa fa-minus"></i></a>
							<input type="text" name="amountquantity" class="amount-input" value="1" maxlength="5" title="<?php _e('请输入购买量','tinection'); ?>">
							<a href="javascript:" hidefocus="true" field="amountquantity" id="plus" class="control plus"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="buygroup row">
						<?php if($amount<=0){ ?>
						<a class="buy-btn sold-out"><i class="fa fa-shopping-cart"></i><?php _e('已售完','tinection'); ?></a>
						<?php }else{ ?>
							<?php if(is_user_logged_in()&&$discount_arr[5]>0){ ?>
							<a class="buy-btn" data-top="true" data-pop="order"><i class="fa fa-shopping-cart"></i><?php _e('立即购买','tinection'); ?></a>
							<?php }elseif($discount_arr[0]>0&&!is_user_logged_in()){ ?>
							<a data-sign="0" class="user-signin buy-btn user-login"><i class="fa fa-shopping-cart"></i><?php _e('登录购买','tinection'); ?></a>
							<?php }else{ ?>
							<a class="buy-btn free-buy" data-top="false"><i class="fa fa-shopping-cart"></i><?php _e('立即购买','tinection'); ?></a>
							<?php } ?>			
                        <?php } ?>
                    </div>
					<div class="tips row">
						<p><?php _e('注意：本站为本商品唯一销售点，请勿在其他途径购买，以免遭受安全损失。','tinection'); ?></p>
					</div>
				</div>
				<div class="main-content">
					<div class="shop-content">
						<div class="mainwrap">
							<div id="wrapnav">
								<?php $order_records = get_user_order_records($post->ID); $order_num = count($order_records); ?>
								<ul class="nav">
									<div class="intro"></div>
									<li class="active"><a href="#description" rel="nofollow" hidefocus="true"><?php _e('商品详情','tinection'); ?></a></li>
									<li><a href="#reviews" rel="nofollow" hidefocus="true"><?php _e('商品评价','tinection'); ?><em><?php $count_comments = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND comment_post_ID = %d AND comment_type not in ('trackback','pingback')", $post->ID ) ); echo $count_comments; ?></em></a></li>
                                    <li class="nav-history"><a href="#history" rel="nofollow" hidefocus="true"><i class="fa fa-history"></i><?php _e('我的购买记录','tinection'); ?><em><?php echo $order_num; ?></em></a></li>
                                    <a class="fixed-buy-btn buy-btn" data-top="true" data-pop="order"><i class="fa fa-shopping-cart"></i><?php _e('立即购买','tinection'); ?></a>
                                </ul>
							</div>
							<div id="wrapnav-container">
								<div id="description" class="wrapbox single-content single-text">
								<p>
								<?php echo store_pay_content_show(get_the_content()); ?>
								</p>
								</div>
								<div id="reviews" class="wrapbox">
								<?php if (comments_open()) comments_template( '', true ); ?>
								</div>
								<div id="history" class="wrapbox">
									<?php if(!is_user_logged_in()){ ?>
									<p class="history-tip"><?php _e('我的购买记录，登陆后可见，','tinection'); ?><a class="user-signin user-login" href="#" title="<?php _e('点击登录','tinection'); ?>"><?php _e('立即登录','tinection'); ?></a>。</p>
									<?php }else{ ?>
                         	    	<div class="pay-history">
										<div class="greytip"><?php _e('Tips：若商品可循环使用则无须多次购买','tinection'); ?></div>
										<table width="100%" border="0" cellspacing="0">
										<thead>
											<tr>
												<th scope="col"><?php _e('订单号','tinection'); ?></th>
												<th scope="col"><?php _e('购买时间','tinection'); ?></th>
												<th scope="col"><?php _e('数量','tinection'); ?></th>
												<th scope="col"><?php _e('价格','tinection'); ?></th>
												<th scope="col"><?php _e('金额','tinection'); ?></th>
												<th scope="col"><?php _e('交易状态','tinection'); ?></th>
											</tr>
										</thead>
										<tbody class="the-list">
											<?php foreach($order_records as $order_record){ ?>
                                            <tr>
												<td><?php echo $order_record['order_id']; ?></td>
												<td><?php echo $order_record['order_time']; ?></td>
												<td><?php echo $order_record['order_quantity']; ?></td>
												<td><?php echo $order_record['order_price']; ?></td>
												<td><?php echo $order_record['order_total_price']; ?></td>
												<td><?php if($order_record['order_status']){echo output_order_status($order_record['order_status']);}; ?></td>
											</tr>
											<?php } ?>
                                        </tbody>
										</table>
									</div>
									<?php } ?>
                            	</div>
                            </div>
						</div>
					</div>
					<?php if(ot_get_option('lazy_load_img')=='on')$lazy = 'class="box-hide" src="'.THEME_URI.'/images/image-pending.gif" data-original';else $lazy ='src'; ?>
					<div class="shop-sidebar">
						<h3><i class="fa fa-gavel"></i><?php _e('相关推荐','tinection'); ?></h3>
						<ul>
						<?php $tags = get_the_terms($post->ID,'products_tag');$tagcount = $tags ? count($tags):0;$tagIDs=array();for ($i = 0;$i <$tagcount;$i++) {$tagIDs[] = $tags[$i]->term_id;};$args=array('term__in'=>$tagIDs,'post_type'=>'store','post__not_in'=>array($post->ID),'showposts'=>4,'orderby'=>'rand','ignore_sticky_posts'=>1);$my_query = new WP_Query($args);if( $my_query->have_posts() ){while ($my_query->have_posts()) : $my_query->the_post();if ( has_post_thumbnail() ){$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');$imgsrc = $large_image_url[0];}else{$imgsrc = catch_first_image();}
						?>
							<li>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" class="fancyimg">
									<div class="thumb-img">
										<img <?php echo $lazy; ?>="<?php echo tin_thumb_source($imgsrc,280,180,false); ?>" alt="<?php the_title(); ?>">
										<span><?php the_article_icon();?></span>
									</div>
								</a>
								<p><?php the_title(); ?></p>
							</li>
						<?php endwhile;} wp_reset_query(); ?>
				        </ul>
					</div>
				</div>	
			</article>
			<div id="order" class="popupbox">
				<form id="alipayment" name="alipayment" action="<?php echo THEME_URI.'/functions/alipay/alipayapi.php'; ?>" method="post">
					<div id="pay">
						<div class="part-order">
							<ul>
								<h3><?php _e('订单信息','tinection'); ?><span><?php _e('（价格单位：','tinection'); ?><?php if($currency==1)echo '元'; else echo '积分';?><?php _e('）','tinection'); ?></span></h3>
								<input type="hidden" name="order_nonce" value="<?php echo wp_create_nonce( 'order-nonce' );?>" >
								<input type = "hidden" id="product_id" name="product_id" readonly="" value="<?php echo $post->ID; ?>">
								<input type = "hidden" id="order_id" name="order_id" readonly="" value="0">
								<li><label for="order_name"><small>*</small><?php _e('商品名称：','tinection'); ?></label><input id="order_name" name="order_name" readonly="" value="<?php the_title();?>"></li>
								<li><label for="order_price"><small>*</small><?php _e('商品单价：','tinection'); ?></label><input id="order_price" readonly="" value="<?php echo $discount_arr[5]; ?>"></li>
								<li><label for="order_quantity"><small>*</small><?php _e('商品数量：','tinection'); ?></label><input id="order_quantity" name="order_quantity" value="1" maxlength="8" title="<?php _e('请输入购买量','tinection'); ?>" onkeydown="if(event.keyCode==13)return false;"></li>
							</ul>
							<ul>
								<h3><?php _e('收货信息','tinection'); ?><span><?php _e('商店','tinection'); ?><?php _e('（虚拟商品除邮箱外可不填）','tinection'); ?></span></h3>
								<?php $autofill = get_user_autofill_info();?>
								<li><label for="receive_name"><?php _e('收货姓名：','tinection'); ?></label><input id="receive_name" name="order_receive_name" value="<?php echo $autofill['user_name']; ?>" onkeydown="if(event.keyCode==13)return false;"></li>
								<li><label for="receive_address"><?php _e('收货地址：','tinection'); ?></label><input id="receive_address" name="order_receive_address" value="<?php echo isset($autofill['user_address'])?$autofill['user_address']:''; ?>" onkeydown="if(event.keyCode==13)return false;"></li>
								<li><label for="receive_zip"><?php _e('收货邮编：','tinection'); ?></label><input id="receive_zip" name="order_receive_zip" value="<?php echo isset($autofill['user_zip'])?$autofill['user_zip']:''; ?>" onkeydown="if(event.keyCode==13)return false;"></li>
								<li><label for="receive_email"><?php _e('用户邮箱：','tinection'); ?></label><input id="receive_email" name="order_receive_email" value="<?php echo $autofill['user_email']; ?>" onkeydown="if(event.keyCode==13)return false;"></li>
								<li><label for="receive_phone"><?php _e('电话号码：','tinection'); ?></label><input id="receive_phone" name="order_receive_phone" value="<?php echo isset($autofill['user_phone'])?$autofill['user_phone']:''; ?>" onkeydown="if(event.keyCode==13)return false;"></li>
								<li><label for="receive_mobile"><?php _e('手机号码：','tinection'); ?></label><input id="receive_mobile" name="order_receive_mobile" value="<?php echo isset($autofill['user_cellphone'])?$autofill['user_cellphone']:''; ?>" onkeydown="if(event.keyCode==13)return false;"></li>
								<li><label for="body"><?php _e('留言备注：','tinection'); ?></label><input name="order_body" value="" onkeydown="if(event.keyCode==13)return false;"></li>
							</ul>
						</div>
						<div class="checkout">
						<?php if($currency==1&&get_post_meta($post->ID,'product_promote_code_support',true)==1){ ?>
							<div id="promote">
								<input id="promote_code" value="" onkeydown="if(event.keyCode==13)return false;">
								<span id="promote_code_apply"><?php _e('使用优惠码','tinection'); ?></span>
							</div>
						<?php } ?>
							<button id="pay-submit" type="submit"><?php _e('立即付款','tinection'); ?></button>
							<div id="total-price"><?php _e('总金额：','tinection'); ?><strong>￥1.00</strong><?php if($currency==1)echo '元'; else echo '积分';?></div>
						</div>
						<div>
						</div>
						<a class="popup-close"><i class="fa fa-times"></i></a>
					</div>
				</form>
			</div>
			<?php endwhile; ?>
		</div>
		<!-- /.Content -->
		</div>
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
<?php } ?>
<!-- /.Bottom Banner -->
<?php get_footer(); ?>