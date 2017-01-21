<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/13 13:48
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php load_mod('mod.Head'); ?>
<body <?php body_class(is_single() ? 'is-loadingApp without-menu' : 'is-loadingApp'); ?>>
<div class="loading-line"></div>
<?php load_mod('mod.ModalQrCodes'); ?>
<header class="header shop-header blue">
    <nav class="header primary-nav">
        <div class="primary-nav-inner clearfix">
            <!-- Logo -->
            <div class="header_logo-wrap clearfix">
                <?php if(!is_single()) { ?>
                <a href="javascript:;" class="hamburger" data-action="toggleMenu">
                    <i class="tico tico-list"></i>
                </a>
                <?php } ?>
                <a class="logo" href="<?php echo tt_url_for('shop_archive'); ?>" title="<?php echo get_bloginfo('name') . '-' . __('Market', 'tt'); ?>">
                    <img src="<?php echo tt_get_option('tt_small_logo'); ?>" alt="Logo">
                </a>
            </div>

            <!-- Search -->
            <div class="header_search-wrap">
                <div class="header-search">
                    <form method="get" action="/">
                        <input autocomplete="off" class="header_search-input" placeholder="<?php _e('Search for something...', 'tt'); ?>" spellcheck="false" name="s" type="text" value="">
                        <input type="hidden" name="in_shop" value="1">
                    </form>
                    <i class="tico tico-search"></i>
                </div>
            </div>

            <!-- Menu tools -->
            <ul class="header_menu-items">
<!--                <li class="header_menu-item header_menu-item--notification">-->
<!--                    <a href="--><?php //echo tt_url_for('in_msg'); ?><!--" data-toggle="dropdown" class="dropdown-toggle dropdown-toggle--notifications"><i class="tico tico-bell-o"></i></a>-->
<!--                    <div class="notifications_preview dropdown-menu dropdown-menu--large">-->
<!--                        <div class="notification">-->
<!--                            <div class="notification_header">--><?php //_e('Notifications', 'tt'); ?><!--</div>-->
<!--                            <div class="notification_footer">You don't have any notifications yet.</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </li>-->

                <li class="header_menu-item header_menu-item--shopcart">
                    <a href="<?php echo tt_url_for('in_msg'); ?>" data-toggle="dropdown" class="dropdown-toggle dropdown-toggle--shopcart"><i class="tico tico-cart"></i></a>
                    <div class="cart_preview dropdown-menu dropdown-menu--large">
                        <?php global $cart_items; $cart_items = tt_get_cart(); $display_cart = $cart_items && count($cart_items) > 0; ?>
                        <div class="shopcart header_shopping_cart <?php if($display_cart) echo 'active'; ?>">
                            <div class="shopcart_header"><?php _e('Shopping Cart', 'tt'); ?></div>
                            <ul class="header_shopping_cart-list">
                                <?php $total = 0; foreach ($cart_items as $cart_item) { $total += $cart_item['price'] * $cart_item['quantity'] ?>
                                    <li class="cart-item" data-product-id="<?php echo $cart_item['id']; ?>">
                                        <a href="<?php echo $cart_item['permalink']; ?>" title="<?php echo $cart_item['name']; ?>">
                                            <img class="thumbnail" src="<?php echo $cart_item['thumb']; ?>">
                                            <span class="product-title"><?php echo $cart_item['name']; ?></span>
                                        </a>
                                        <div class="price"><i class="tico tico-cny"></i><?php echo $cart_item['price'] . ' x ' . $cart_item['quantity']; ?></div>
                                        <i class="tico tico-close delete"></i>
                                    </li>
                                <?php } ?>
                                <div class="cart-amount"><?php echo __('TOTAL: '); ?><i class="tico tico-cny"></i><span><?php echo $total; ?></span></div>
                            </ul>
                            <div class="cart-actions">
                                <a class="btn btn-border-success cart-act check-act" href="javascript:;"><?php _e('Check Out Now', 'tt'); ?></a>
                                <a class="btn btn-border-danger cart-act clear-act" href="javascript:;"><?php _e('Clear All', 'tt'); ?></a>
                            </div>
                            <div class="shopcart_footer"><?php _e('The shopping cart is empty.', 'tt'); ?></div>
                        </div>
                    </div>
                </li>

                <?php $user = wp_get_current_user(); ?>
                <?php if($user && $user->ID) { ?>
                    <li class="header_menu-item header_menu-item--user nav-user">
                        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo tt_get_avatar($user->ID, 'small'); ?>" class="avatar">
<!--                            <span class="username">--><?php //echo $user->display_name; ?><!--</span>-->
<!--                            <span class="tico-angle-down"></span>-->
                        </a>
                        <ul class="nav-user-menu dropdown-menu">
                            <?php if(current_user_can('edit_users')) { ?>
                                <li><a href="<?php echo get_dashboard_url(); ?>"><span class="tico tico-meter"></span><?php _e('Go Dashboard', 'tt'); ?></a></li>
                            <?php } ?>
<!--                            <li><a href="--><?php //echo tt_url_for('new_post'); ?><!--"><span class="tico tico-quill"></span>--><?php //_e('New Post', 'tt'); ?><!--</a></li>-->
<!--                            <li><a href="--><?php //echo tt_url_for('uc_latest'); ?><!--"><span class="tico tico-user"></span>--><?php //_e('My Posts', 'tt'); ?><!--</a></li>-->
                            <li><a href="<?php echo tt_url_for('my_all_orders'); ?>"><span class="tico tico-ticket"></span><?php _e('My Orders', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('in_msg'); ?>"><span class="tico tico-envelope"></span><?php _e('My Messages', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('my_settings'); ?>"><span class="tico tico-equalizer"></span><?php _e('My Settings', 'tt'); ?></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo tt_add_redirect(tt_url_for('signout'), tt_get_current_url()); ?>"><span class="tico tico-sign-out"></span><?php _e('Sign Out', 'tt'); ?></a></li>
                        </ul>
                    </li>
                <?php }else{ ?>
                    <li class="header_menu-item login-actions">
                        <a href="<?php echo tt_add_redirect(tt_url_for('signin'), tt_get_current_url()); ?>" class="login-link bind-redirect"><span><?php _e('Sign In or Up', 'tt'); ?></span></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
    <?php if(is_archive() && !tt_is_product_category() && !tt_is_product_tag()) { ?>
    <!-- Secondary navbar -->
    <?php $vm = ShopHeaderSubNavVM::getInstance(); $data = $vm->modelData; ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Secondary navbar cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <div class="secondary-navbar">
        <div class="secondary-navbar-inner clearfix">
            <ul class="secondary-navbar_list-items secondary-navbar_list-items--left clearfix">
                <!-- Categories -->
                <?php $categories = $data->categories; ?>
                <li class="secondary-navbar_list-item secondary-navbar_list-item--filter category-filter">
                    <a href="javascript:;"><?php _e('Categories', 'tt'); ?></a>
                    <ul>
                        <?php foreach ($categories as $category) { ?>
                            <li><a href="<?php echo $category['permalink']; ?>"><strong><?php echo $category['name']; ?></strong> (<?php echo $category['count']; ?>)</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <!-- Price -->
                <?php $price_types = $data->price_types; ?>
                <li class="secondary-navbar_list-item secondary-navbar_list-item--filter price-filter">
                    <a href="javascript:;"><?php _e('Price', 'tt'); ?></a>
                    <ul>
                        <?php foreach ($price_types as $price_type) { ?>
                            <li><a href="<?php echo $price_type['url']; ?>"><strong><?php echo $price_type['name']; ?></strong> (<?php echo $price_type['count']; ?>)</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <!-- Tags -->
                <?php $tags = $data->tags; ?>
                <li class="secondary-navbar_list-item secondary-navbar_list-item--filter tag-filter">
                    <a href="javascript:;"><?php _e('Tags', 'tt'); ?></a>
                    <ul>
                        <?php foreach ($tags as $tag) { ?>
                            <li><a href="<?php echo $tag['permalink']; ?>"><strong><?php echo $tag['name']; ?></strong> (<?php echo $tag['count']; ?>)</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
            <ul class="secondary-navbar_list-items secondary-navbar_list-items--right clearfix">
                <li class="<?php echo tt_conditional_class('secondary-navbar_list-item secondary-navbar_list-item--sort', !isset($_GET['sort']) || $_GET['sort']=='popular'); ?>">
                    <a href="<?php echo add_query_arg(array('sort' => 'popular'), Utils::getPHPCurrentUrl()/*tt_url_for('shop_archive')*/); ?>"><?php _e('Most Popular', 'tt'); ?></a>
                </li>
                <li class="<?php echo tt_conditional_class('secondary-navbar_list-item secondary-navbar_list-item--sort', isset($_GET['sort']) && $_GET['sort']=='latest'); ?>">
                    <a href="<?php echo add_query_arg(array('sort' => 'latest'), Utils::getPHPCurrentUrl()/*tt_url_for('shop_archive')*/); ?>"><?php _e('Latest', 'tt'); ?></a>
                </li>
            </ul>
        </div>
    </div>
    <?php } ?>
</header>