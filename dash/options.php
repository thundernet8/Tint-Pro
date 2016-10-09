<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	// Change this to use your theme slug
	return 'options-framework-theme-tint';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'tt'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {
    // 主题版本
    $theme_version = trim(wp_get_theme()->get('Version'));

    $theme_pro = !!preg_match('/([0-9-\.]+)PRO/i', $theme_version);

    // 博客名
    $blog_name = trim(get_bloginfo('name'));

    // 博客主页
    $blog_home = home_url();

    // 定义选项面板图片引用路径
    $imagepath =  THEME_URI . '/dash/of_inc/images/';

    // 所有分类
    $options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

    $options = array();

	// 主题选项 - 基本设置
	$options[] = array(
		'name' => __( 'Basic', 'tt' ),
		'type' => 'heading'
	);

	// - 首页描述
    $options[] = array(
        'name' => __( 'Home Page Description', 'tt' ),
        'desc' => __( 'Home page description meta information, good for SEO', 'tt' ),
        'id' => 'tt_home_description',
        'std' => '',
        'type' => 'text'
    );

    // - 首页关键词
    $options[] = array(
        'name' => __( 'Home Page Keywords', 'tt' ),
        'desc' => __( 'Home page keywords meta information, good for SEO', 'tt' ),
        'id' => 'tt_home_keywords',
        'std' => '',
        'type' => 'text'
    );

    // - 收藏夹图标
    $options[] = array(
        'name' => __( 'Favicon', 'tt' ),
        'desc' => __( 'Please upload an ico file', 'tt' ),
        'id' => 'tt_favicon',
        'std' => THEME_ASSET . '/img/favicon.ico',
        'type' => 'upload'
    );

    // - 收藏夹图标
    $options[] = array(
        'name' => __( 'Favicon(PNG)', 'tt' ),
        'desc' => __( 'Please upload an png file', 'tt' ),
        'id' => 'tt_png_favicon',
        'std' => THEME_ASSET . '/img/favicon.png',
        'type' => 'upload'
    );

    // - 本地化语言
    $options[] = array(
        'name' => __( 'I18n', 'tt' ),
        'desc' => __( 'Multi languages and I18n support', 'tt' ),
        'id' => 'tt_l10n',
        'std' => 'zh_CN',
        'type' => 'select',
        'options' => array(
            'zh_CN' => __( 'zh_cn', 'tt' ),
            'en_US' => __( 'en_us', 'tt' )
        )
    );

    // - Gravatar
    $options[] = array(
        'name' => __( 'Gravatar', 'tt' ),
        'desc' => __( 'Gravatar support', 'tt' ),
        'id' => 'tt_enable_gravatar',
        'std' => false,
        'type' => 'checkbox'
    );


    // - Timthumb
    $options[] = array(
        'name' => __( 'Timthumb Crop', 'tt' ),
        'desc' => __( 'Timthumb crop support', 'tt' ),
        'id' => 'tt_enable_timthumb',
        'std' => false,
        'type' => 'checkbox'
    );

    // - jQuery 源
    $options[] = array(
        'name' => __( 'jQuery Source', 'tt' ),
        'desc' => __( 'Choose local or a CDN jQuery file', 'tt' ),
        'id' => 'tt_jquery',
        'std' => 'local_2',
        'type' => 'select',
        'options' => array(
            'local_1' => __('Local v1.12', 'tt'),
            'local_2' => __('Local v3.1', 'tt'),
            'cdn_http' => __('CDN HTTP', 'tt'),
            'cdn_https' => __('CDN HTTPS', 'tt')
        )
    );

    // - jQuery 加载位置
    $options[] = array(
        'name' => __( 'jQuery Load Position', 'tt' ),
        'desc' => __( 'Check to load jQuery on `body` end', 'tt' ),
        'id' => 'tt_foot_jquery',
        'std' => false,
        'type' => 'checkbox'
    );


	// 主题选项 - 样式设置
	$options[] = array(
		'name' => __( 'Style', 'tt' ),
		'type' => 'heading'
	);

    // - 网站 Logo
    $options[] = array(
        'name' => __( 'Site Logo', 'tt' ),
        'desc' => __( 'Please upload an png file as site logo', 'tt' ),
        'id' => 'tt_logo',
        'std' => THEME_ASSET . '/img/logo.png',
        'type' => 'upload'
    );

    // - 网站小 Logo
    $options[] = array(
        'name' => __( 'Site Small Logo', 'tt' ),
        'desc' => __( 'Please upload an png file as site small logo', 'tt' ), // 用于邮件、登录页Logo等
        'id' => 'tt_small_logo',
        'std' => THEME_ASSET . '/img/small-logo.png',
        'type' => 'upload'
    );


    // 主题选项 - 内容设置
    $options[] = array(
        'name' => __( 'Content', 'tt' ),
        'type' => 'heading'
    );

    // - 首页排除分类
    $options[] = array(
        'name' => __('Home Hide Categories', 'tt'),
        'desc' => __('Choose categories those are not displayed in homepage', 'tt'),
        'id' => 'tt_home_undisplay_cats',
        'std' => array(),
        'type' => 'multicheck',
        'options' => $options_categories
    );

    // - 幻灯文章ID列表
    $options[] = array(
        'name' => __( 'Slide Post IDs', 'tt' ),
        'desc' => __( 'The post IDs for home slides, separate with comma', 'tt' ),
        'id' => 'tt_home_slides',
        'std' => '',
        'type' => 'text'
    );

    // - 热门文章来源
    $options[] = array(
        'name' => __('Home Popular Posts Algorithm', 'tt'),
        'desc' => __('Choose the method of retrieving popular posts for homepage', 'tt'),
        'id' => 'tt_home_popular_algorithm',
        'std' => 'latest_reviewed',
        'type' => 'select',
        'options' => array(
            'most_viewed' => __('Most Viewed', 'tt'),
            'most_reviewed' => __('Most Reviewed', 'tt'),
            'latest_reviewed' => __('Latest Reviewed', 'tt')
        )
    );

    // - 置顶分类1
    $options[] = array(
        'name' => __('Featured Category 1', 'tt'),
        'desc' => __('Choose the first featured category for homepage', 'tt'),
        'id' => 'tt_home_featured_category_one',
        'std' => array_keys($options_categories)[0],
        'type' => 'select',
        'options' => $options_categories
    );

    // - 置顶分类2
    $options[] = array(
        'name' => __('Featured Category 2', 'tt'),
        'desc' => __('Choose the second featured category for homepage', 'tt'),
        'id' => 'tt_home_featured_category_two',
        'std' => array_keys($options_categories)[min(1, count($options_categories)-1)],
        'type' => 'select',
        'options' => $options_categories
    );

    // - 置顶分类3
    $options[] = array(
        'name' => __('Featured Category 3', 'tt'),
        'desc' => __('Choose the third featured category for homepage', 'tt'),
        'id' => 'tt_home_featured_category_three',
        'std' => array_keys($options_categories)[min(2, count($options_categories)-1)],
        'type' => 'select',
        'options' => $options_categories
    );

    // - 商品推荐
    $options[] = array(
        'name' => __( 'Home Products Recommendation', 'tt' ),
        'desc' => __( 'Enable products recommendation module for homepage', 'tt' ),
        'id' => 'tt_home_products_recommendation',
        'std' => false,
        'type' => $theme_pro ? 'checkbox' : 'disabled'
    );


	// 主题设置 - 边栏设置
	$options[] = array(
		'name' => __( 'Sidebar', 'tt' ),
		'type' => 'heading'
	);


	// - 待注册的边栏
    $options[] = array(
        'name' => __('Register Sidebars', 'tt'),
        'desc' => __('Check the sidebars to register', 'tt'),
        'id'   => 'tt_register_sidebars',
        'std'  => array('sidebar_common' => true),
        'type' => 'multicheck',
        'options' => array(
            'sidebar_common'    =>    __('Common Sidebar', 'tt'),
            'sidebar_home'      =>    __('Home Sidebar', 'tt'),
            'sidebar_single'    =>    __('Single Sidebar', 'tt'),
            'sidebar_archive'   =>    __('Archive Sidebar', 'tt'),
            'sidebar_category'  =>    __('Category Sidebar', 'tt'),
            'sidebar_search'    =>    __('Search Sidebar', 'tt'),
            'sidebar_404'       =>    __('404 Sidebar', 'tt'),
            'sidebar_page'      =>    __('Page Sidebar', 'tt')
        )
    );


    // - 首页边栏
    $all_sidebars = array(
        'sidebar_common'    =>    __('Common Sidebar', 'tt'),
        'sidebar_home'      =>    __('Home Sidebar', 'tt'),
        'sidebar_single'    =>    __('Single Sidebar', 'tt'),
        'sidebar_archive'   =>    __('Archive Sidebar', 'tt'),
        'sidebar_category'  =>    __('Category Sidebar', 'tt'),
        'sidebar_search'    =>    __('Search Sidebar', 'tt'),
        'sidebar_404'       =>    __('404 Sidebar', 'tt'),
        'sidebar_page'      =>    __('Page Sidebar', 'tt')
    );
    $register_status = of_get_option('tt_register_sidebars', array('sidebar_common' => true));
    $available_sidebars = [];
    foreach ($register_status as $key => $value){
        if($value) $available_sidebars[$key] = $all_sidebars[$key];
    }

    $options[] = array(
        'name' => __('Home Sidebar', 'tt'),
        'desc' => __('Select a sidebar for homepage', 'tt'),
        'id'   => 'tt_home_sidebar',
        'std'  => array('sidebar_common' => true),
        'type' => 'select',
        'class' => 'mini',
        'options' => $available_sidebars
    );

    $options[] = array(
        'name' => __('Single Sidebar', 'tt'),
        'desc' => __('Select a sidebar for single post page', 'tt'),
        'id'   => 'tt_single_sidebar',
        'std'  => array('sidebar_common' => true),
        'type' => 'select',
        'class' => 'mini',
        'options' => $available_sidebars
    );

    $options[] = array(
        'name' => __('Archive Sidebar', 'tt'),
        'desc' => __('Select a sidebar for archive page', 'tt'),
        'id'   => 'tt_archive_sidebar',
        'std'  => array('sidebar_common' => true),
        'type' => 'select',
        'class' => 'mini',
        'options' => $available_sidebars
    );

    $options[] = array(
        'name' => __('Category Sidebar', 'tt'),
        'desc' => __('Select a sidebar for category page', 'tt'),
        'id'   => 'tt_category_sidebar',
        'std'  => array('sidebar_common' => true),
        'type' => 'select',
        'class' => 'mini',
        'options' => $available_sidebars
    );

    $options[] = array(
        'name' => __('Search Sidebar', 'tt'),
        'desc' => __('Select a sidebar for search page', 'tt'),
        'id'   => 'tt_search_sidebar',
        'std'  => array('sidebar_common' => true),
        'type' => 'select',
        'class' => 'mini',
        'options' => $available_sidebars
    );

    $options[] = array(
        'name' => __('404 Sidebar', 'tt'),
        'desc' => __('Select a sidebar for 404 page', 'tt'),
        'id'   => 'tt_404_sidebar',
        'std'  => array('sidebar_common' => true),
        'type' => 'select',
        'class' => 'mini',
        'options' => $available_sidebars
    );

    $options[] = array(
        'name' => __('Page Sidebar', 'tt'),
        'desc' => __('Select a sidebar for page', 'tt'),
        'id'   => 'tt_page_sidebar',
        'std'  => array('sidebar_common' => true),
        'type' => 'select',
        'class' => 'mini',
        'options' => $available_sidebars
    );


	// 主题设置 - 社会化设置(包含管理员社会化链接等)
	$options[] = array(
		'name' => __( 'Social', 'tt' ),
		'type' => 'heading'
	);


    // - 站点服务QQ
    $options[] = array(
        'name' => __( 'Site QQ', 'tt' ),
        'desc' => __( 'The QQ which is dedicated for the site', 'tt' ),
        'id' => 'tt_site_qq',
        'std' => '1990318877',
        'type' => 'text'
    );


    // - 站点服务QQ群
    $options[] = array(
        'name' => __( 'Site QQ Group ID', 'tt' ),
        'desc' => __( 'The ID key of QQ group which is dedicated for the site, visit `http://shang.qq.com` for detail', 'tt' ),
        'id' => 'tt_site_qq_group',
        'std' => 'c3d3931c2af9e1d8d16dbc9088dbfc2298df2b9e78bd0f4db09f0f4dea6052a1',
        'type' => 'text'
    );


    // - 站点服务微博
    $options[] = array(
        'name' => __( 'Site Weibo', 'tt' ),
        'desc' => __( 'The name of Weibo account which is dedicated for the site', 'tt' ),
        'id' => 'tt_site_weibo',
        'std' => 'touchumind',
        'type' => 'text'
    );


    // - 站点服务Facebook
    $options[] = array(
        'name' => __( 'Site Facebook', 'tt' ),
        'desc' => __( 'The name of Facebook account which is dedicated for the site', 'tt' ),
        'id' => 'tt_site_facebook',
        'std' => 'xueqian.wu',
        'type' => 'text'
    );


    // - 站点服务Twitter
    $options[] = array(
        'name' => __( 'Site Twitter', 'tt' ),
        'desc' => __( 'The name of Twitter account which is dedicated for the site', 'tt' ),
        'id' => 'tt_site_twitter',
        'std' => 'thundernet8',
        'type' => 'text'
    );


    // - 站点服务微信
    $options[] = array(
        'name' => __( 'Site Weixin', 'tt' ),
        'desc' => __( 'The qrcode image of Weixin account which is dedicated for the site', 'tt' ),
        'id' => 'tt_site_weixin_qr',
        'std' => THEME_ASSET . '/img/qr/weixin.png',
        'type' => 'upload'
    );


    // - 站点服务支付宝
    $options[] = array(
        'name' => __( 'Site Alipay', 'tt' ),
        'desc' => __( 'The qrcode image of Alipay account which is dedicated for the site', 'tt' ),
        'id' => 'tt_site_alipay_qr',
        'std' => THEME_ASSET . '/img/qr/alipay.png',
        'type' => 'upload'
    );


    // - 开启QQ登录
    $options[] = array(
        'name' => __( 'QQ Login', 'tt' ),
        'desc' => __( 'QQ login ', 'tt' ),
        'id' => 'tt_enable_qq_login',
        'std' => false,
        'type' => 'checkbox'
    );


	// - QQ开放平台应用ID
    $options[] = array(
        'name' => __( 'QQ Open ID', 'tt' ),
        'desc' => __( 'Your QQ open application ID', 'tt' ),
        'id' => 'tt_qq_openid',
        'std' => '',
        'type' => 'text'
    );


    // - QQ开放平台应用KEY
    $options[] = array(
        'name' => __( 'QQ Open Key', 'tt' ),
        'desc' => __( 'Your QQ open application key', 'tt' ),
        'id' => 'tt_qq_openkey',
        'std' => '',
        'type' => 'text'
    );


    // - 开启微博登录
    $options[] = array(
        'name' => __( 'Weibo Login', 'tt' ),
        'desc' => __( 'Weibo login access', 'tt' ),
        'id' => 'tt_enable_weibo_login',
        'std' => false,
        'type' => 'checkbox'
    );


    // - 微博开放平台Key
    $options[] = array(
        'name' => __( 'Weibo Open Key', 'tt' ),
        'desc' => __( 'Your weibo open application key', 'tt' ),
        'id' => 'tt_weibo_openkey',
        'std' => '',
        'type' => 'text'
    );


    // - 微博开放平台Secret
    $options[] = array(
        'name' => __( 'Weibo Open Secret', 'tt' ),
        'desc' => __( 'Your weibo open application secret', 'tt' ),
        'id' => 'tt_weibo_opensecret',
        'std' => '',
        'type' => 'text'
    );


    // - 开启微信登录
    $options[] = array(
        'name' => __( 'Weixin Login', 'tt' ),
        'desc' => __( 'Weixin login access', 'tt' ),
        'id' => 'tt_enable_weixin_login',
        'std' => false,
        'type' => 'checkbox'
    );


    // - 微信开放平台Key
    $options[] = array(
        'name' => __( 'Weixin Open Key', 'tt' ),
        'desc' => __( 'Your weixin open application key', 'tt' ),
        'id' => 'tt_weixin_openkey',
        'std' => '',
        'type' => 'text'
    );


    // - 微信开放平台Secret
    $options[] = array(
        'name' => __( 'Weixin Open Secret', 'tt' ),
        'desc' => __( 'Your weixin open application secret', 'tt' ),
        'id' => 'tt_weixin_opensecret',
        'std' => '',
        'type' => 'text'
    );

    // - 开放平台接入新用户角色
    $options[] = array(
        'name' => __('Open User Default Role', 'tt'),
        'desc' => __('Choose the role and capabilities for the new connected user from open', 'tt'),
        'id' => 'tt_open_role',
        'std' => 'contributor',
        'type' => 'select',
        'options' => array(
            'editor' => __('Editor', 'tt'),
            'author' => __('Author', 'tt'),
            'contributor' => __('Contributor', 'tt'),
            'subscriber' => __('Subscriber', 'tt'),
        )
    );



	// 主题设置 - 广告设置
	$options[] = array(
		'name' => __( 'Ad', 'tt' ),
		'type' => 'heading'
	);


	//


	// 主题设置 - 用户系统设置(包含积分和会员)
	$options[] = array(
		'name' => __( 'Membership', 'tt' ),
		'type' => 'heading'
	);


	//


	// 主题设置 - 商店设置
	$options[] = array(
		'name' => __( 'Shop', 'tt' ),
		'type' => 'heading'
	);


    // - 站点微信收款二维码
    $options[] = array(
        'name' => __( 'Site Weixin Pay QR', 'tt' ),
        'desc' => __( 'The Weixin pay qrcode image for collection money', 'tt' ),
        'id' => 'tt_weixin_pay_qr',
        'std' => THEME_ASSET . '/img/qr/weixin_pay.png',
        'type' => 'upload'
    );


    // - 站点支付宝收款二维码
    $options[] = array(
        'name' => __( 'Site Alipay Pay QR', 'tt' ),
        'desc' => __( 'The Alipay pay qrcode image for collection money', 'tt' ),
        'id' => 'tt_alipay_pay_qr',
        'std' => THEME_ASSET . '/img/qr/alipay_pay.png',
        'type' => 'upload'
    );


	// 主题设置 - 辅助设置(包含短链接、SMTP工具等)
	$options[] = array(
		'name' => __( 'Auxiliary', 'tt' ),
		'type' => 'heading'
	);


	// - Memcache/redis/...内存对象缓存
    $options[] = array(
        'name' => __( 'Object Cache', 'tt' ),
        'desc' => __( 'Object cache support, accelerate your site', 'tt' ),
        'id' => 'tt_object_cache',
        'std' => 'none',
        'type' => 'select',
        'options' => array(
            'memcache' => __( 'Memcache', 'tt' ),  //TODO: add tutorial url
            'redis' => __( 'Redis', 'tt' ),
            'none'  => __('None', 'tt')
        )
    );


    if (of_get_option('tt_object_cache')=='memcache'):
    // - Memcache Host
    $options[] = array(
        'name' => __( 'Memcache Host', 'tt' ),
        'desc' => __( 'Memcache server host', 'tt' ),
        'id' => 'tt_memcached_host',
        'std' => '127.0.0.1',
        'type' => 'text'
    );


    // - Memcache Port
    $options[] = array(
        'name' => __( 'Memcache Port', 'tt' ),
        'desc' => __( 'Memcache server port', 'tt' ),
        'id' => 'tt_memcached_port',
        'std' => 11211,
        'class' => 'mini',
        'type' => 'text'
    );
    endif;


    if (of_get_option('tt_object_cache')=='redis'):
    // - Redis Host
    $options[] = array(
        'name' => __( 'Redis Host', 'tt' ),
        'desc' => __( 'Redis server host', 'tt' ),
        'id' => 'tt_redis_host',
        'std' => '127.0.0.1',
        'type' => 'text'
    );


    // - Redis Port
    $options[] = array(
        'name' => __( 'Redis Port', 'tt' ),
        'desc' => __( 'Redis server port', 'tt' ),
        'id' => 'tt_redis_port',
        'std' => 6379,
        'class' => 'mini',
        'type' => 'text'
    );
    endif;


    // - Separator
    $options[] = array(
        'name' => __( 'Mailer Separator', 'tt' ),
        'class'=> 'option-separator',
        'type' => 'info'
    );

    // - SMTP/PHPMail
    $options[] = array(
        'name' => __( 'SMTP/PHPMailer', 'tt' ),
        'desc' => __( 'Use SMTP or PHPMail as default mailer', 'tt' ),
        'id' => 'tt_default_mailer',
        'std' => 'php',
        'type' => 'select',
        'options' => array(
            'php' => __('PHP', 'tt'),
            'smtp' => __('SMTP', 'tt')
        )
    );


    if (of_get_option('tt_default_mailer')==='smtp'):
    // - SMTP 主机
    $options[] = array(
        'name' => __( 'SMTP Host', 'tt' ),
        'desc' => __( 'Your SMTP service host', 'tt' ),
        'id' => 'tt_smtp_host',
        'std' => '',
        'placeholder' => 'e.g smtp.163.com',
        'type' => 'text'
    );


    // - SMTP 端口
    $options[] = array(
        'name' => __( 'SMTP Port', 'tt' ),
        'desc' => __( 'Your SMTP service port', 'tt' ),
        'id' => 'tt_smtp_port',
        'std' => 465,
        'class' => 'mini',
        'type' => 'text'
    );


    // - SMTP 安全
    $options[] = array(
        'name' => __( 'SMTP Secure', 'tt' ),
        'desc' => __( 'Your SMTP server secure protocol', 'tt' ),
        'id' => 'tt_smtp_secure',
        'std' => 'ssl',
        'type' => 'select',
        'options' => array(
            'auto' => __('Auto', 'tt'),
            'ssl' => __('SSL', 'tt'),
            'tls' => __('TLS', 'tt'),
            'none' => __('None', 'tt')
        )
    );


    // - SMTP 用户名
    $options[] = array(
        'name' => __( 'SMTP Username', 'tt' ),
        'desc' => __( 'Your SMTP username', 'tt' ),
        'id' => 'tt_smtp_username',
        'std' => '',
        'type' => 'text'
    );


    // - SMTP 密码
    $options[] = array(
        'name' => __( 'SMTP Password', 'tt' ),
        'desc' => __( 'Your SMTP password', 'tt' ),
        'id' => 'tt_smtp_password',
        'std' => '',
        'class' => 'mini',
        'type' => 'text'
    );


    // - 你的姓名
    $options[] = array(
        'name' => __( 'Your Name', 'tt' ),
        'desc' => __( 'Your display name as the sender', 'tt' ),
        'id' => 'tt_smtp_name',
        'std' => $blog_home,
        'class' => 'mini',
        'type' => 'text'
    );
    endif;


    if (of_get_option('tt_default_mailer')!=='smtp'):
    // - PHP Mail 发信人姓名
    $options[] = array(
        'name' => __( 'PHP Mail Sender Display Name', 'tt' ),
        'desc' => __( 'The Sender display name when using PHPMail send mail', 'tt' ),
        'id' => 'tt_mail_custom_sender',
        'std' => $blog_name,
        'class' => 'mini',
        'type' => 'text'
    );


    // - PHP Mail 发信人地址
    $options[] = array(
        'name' => __( 'PHP Mail Sender Address', 'tt' ),
        'desc' => __( 'You can use fake mail address when use PHPMail', 'tt' ),
        'id' => 'tt_mail_custom_address',
        'std' => '',
        'placeholder' => 'e.g no-reply@domain.com',
        'type' => 'text'
    );
    endif;


    // - 短链接前缀
    $options[] = array(
        'name' => __( 'Short Link Prefix', 'tt' ),
        'desc' => __( 'Use short link instead long link or even convert external link to internal link', 'tt' ),
        'id' => 'tt_short_link_prefix',
        'std' => 'go',
        'class' => 'mini',
        'type' => 'text'
    );


    // - 短链接记录
    $options[] = array(
        'name' => __( 'Short Link Records', 'tt' ),
        'desc' => __( 'One line for one record, please conform to the sample', 'tt' ),
        'id' => 'tt_short_link_records',
        'std' => 'baidu | http://www.baidu.com' . PHP_EOL,
        'type' => 'textarea'
    );






    // 主题反馈
    $options[] = array(
        'name' => __( 'Feedback', 'tt' ),
        'type' => 'heading'
    );


    //



    // 其他 - 主题调试/更新
    //TODO: 版本升级 升级日志
    $options[] = array(
        'name' => __( 'Others', 'tt' ),
        'type' => 'heading'
    );


    // - 开启调试
    $options[] = array(
        'name' => __( 'Debug Mode', 'tt' ),
        'desc' => __( 'Enable debug will force display php errors, disable theme cache, enable some private links or functions, etc.', 'tt' ),
        'id' => 'tt_theme_debug',
        'std' => false,
        'type' => 'checkbox'
    );


    // - 主题专用私有Token
    $options[] = array(
        'name' => __('Tint Token', 'tt'),
        'desc' => __('Private token for theme, maybe useful somewhere.', 'tt'),
        'id' => 'tt_private_token',
        'std' => Utils::generateRandomStr(5),
        'class' => 'mini',
        'type' => 'text'
    );


    // - 刷新固定链接链接
    $options[] = array(
        'name'  =>  __('Refresh Rewrite Rules', 'tt'),
        'desc'  =>  sprintf(__('Please Click to <a href="%1$s/m/refresh?token=%2$s" target="_blank">Refresh Rewrite Rules</a> if you have encounter some 404 errors', 'tt'), $blog_home, of_get_option('tt_private_token')),
        'type'  => 'info'
    );


    // - QQ邮我链接ID
    $options[] = array(
        'name' => __( 'QQ Mailme ID', 'tt' ),
        'desc' => __( 'The id of qq mailme, visit `http://open.mail.qq.com` for detail', 'tt' ),
        'id' => 'tt_mailme_id',
        'std' => '',
        'type' => 'text'
    );


    // - QQ邮件列表ID
    $options[] = array(
        'name' => __( 'QQ Mail list ID', 'tt' ),
        'desc' => __( 'The id of qq mailme, visit `http://list.qq.com` for detail', 'tt' ),
        'id' => 'tt_maillist_id',
        'std' => '',
        'type' => 'text'
    );


    // - Head自定义代码
    $options[] = array(
        'name' => __( 'Head Custom Code', 'tt' ),
        'desc' => __( 'Custom code loaded on page head', 'tt' ),
        'id' => 'tt_head_code',
        'std' => '',
        'type' => 'textarea'
    );


    // - Foot自定义代码
    $options[] = array(
        'name' => __( 'Foot Custom Code', 'tt' ),
        'desc' => __( 'Custom code loaded on page foot', 'tt' ),
        'id' => 'tt_foot_code',
        'std' => '',
        'type' => 'textarea'
    );

    // - Foot IDC备案文字
    $options[] = array(
        'name' => __( 'Foot Beian Text', 'tt' ),
        'desc' => __( 'IDC reference No. for regulations of China', 'tt' ),
        'id' => 'tt_beian',
        'std' => '',
        'type' => 'text'
    );





	///////////////////////////////////////////////////////////////////////////

//	// Test data
//	$test_array = array(
//		'one' => __( 'One', 'tt' ),
//		'two' => __( 'Two', 'tt' ),
//		'three' => __( 'Three', 'tt' ),
//		'four' => __( 'Four', 'tt' ),
//		'five' => __( 'Five', 'tt' )
//	);
//
//	// Multicheck Array
//	$multicheck_array = array(
//		'one' => __( 'French Toast', 'tt' ),
//		'two' => __( 'Pancake', 'tt' ),
//		'three' => __( 'Omelette', 'tt' ),
//		'four' => __( 'Crepe', 'tt' ),
//		'five' => __( 'Waffle', 'tt' )
//	);
//
//	// Multicheck Defaults
//	$multicheck_defaults = array(
//		'one' => '1',
//		'five' => '1'
//	);
//
//	// Background Defaults
//	$background_defaults = array(
//		'color' => '',
//		'image' => '',
//		'repeat' => 'repeat',
//		'position' => 'top center',
//		'attachment'=>'scroll' );
//
//	// Typography Defaults
//	$typography_defaults = array(
//		'size' => '15px',
//		'face' => 'georgia',
//		'style' => 'bold',
//		'color' => '#bada55' );
//
//	// Typography Options
//	$typography_options = array(
//		'sizes' => array( '6','12','14','16','20' ),
//		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
//		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
//		'color' => false
//	);
//
//	// Pull all the categories into an array
////	$options_categories = array();
////	$options_categories_obj = get_categories();
////	foreach ($options_categories_obj as $category) {
////		$options_categories[$category->cat_ID] = $category->cat_name;
////	}
//
//	// Pull all tags into an array
////	$options_tags = array();
////	$options_tags_obj = get_tags();
////	foreach ( $options_tags_obj as $tag ) {
////		$options_tags[$tag->term_id] = $tag->name;
////	}
//
//
//	// Pull all the pages into an array
////	$options_pages = array();
////	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
////	$options_pages[''] = 'Select a page:';
////	foreach ($options_pages_obj as $page) {
////		$options_pages[$page->ID] = $page->post_title;
////	}
//
//
//
//	$options[] = array(
//		'name' => __( 'Input Text Mini', 'tt' ),
//		'desc' => __( 'A mini text input field.', 'tt' ),
//		'id' => 'example_text_mini',
//		'std' => 'Default',
//		'class' => 'mini',
//		'type' => 'text'
//	);
//
//	$options[] = array(
//		'name' => __( 'Input Text', 'tt' ),
//		'desc' => __( 'A text input field.', 'tt' ),
//		'id' => 'example_text',
//		'std' => 'Default Value',
//		'type' => 'text'
//	);
//
//	$options[] = array(
//		'name' => __( 'Input with Placeholder', 'tt' ),
//		'desc' => __( 'A text input field with an HTML5 placeholder.', 'tt' ),
//		'id' => 'example_placeholder',
//		'placeholder' => 'Placeholder',
//		'type' => 'text'
//	);
//
//	$options[] = array(
//		'name' => __( 'Textarea', 'tt' ),
//		'desc' => __( 'Textarea description.', 'tt' ),
//		'id' => 'example_textarea',
//		'std' => 'Default Text',
//		'type' => 'textarea'
//	);
//
//	$options[] = array(
//		'name' => __( 'Input Select Small', 'tt' ),
//		'desc' => __( 'Small Select Box.', 'tt' ),
//		'id' => 'example_select',
//		'std' => 'three',
//		'type' => 'select',
//		'class' => 'mini', //mini, tiny, small
//		'options' => $test_array
//	);
//
//	$options[] = array(
//		'name' => __( 'Input Select Wide', 'tt' ),
//		'desc' => __( 'A wider select box.', 'tt' ),
//		'id' => 'example_select_wide',
//		'std' => 'two',
//		'type' => 'select',
//		'options' => $test_array
//	);
//
//	if ( $options_categories ) {
//		$options[] = array(
//			'name' => __( 'Select a Category', 'tt' ),
//			'desc' => __( 'Passed an array of categories with cat_ID and cat_name', 'tt' ),
//			'id' => 'example_select_categories',
//			'type' => 'select',
//			'options' => $options_categories
//		);
//	}
//
//	if ( $options_tags ) {
//		$options[] = array(
//			'name' => __( 'Select a Tag', 'options_check' ),
//			'desc' => __( 'Passed an array of tags with term_id and term_name', 'options_check' ),
//			'id' => 'example_select_tags',
//			'type' => 'select',
//			'options' => $options_tags
//		);
//	}
//
//	$options[] = array(
//		'name' => __( 'Select a Page', 'tt' ),
//		'desc' => __( 'Passed an pages with ID and post_title', 'tt' ),
//		'id' => 'example_select_pages',
//		'type' => 'select',
//		'options' => $options_pages
//	);
//
//	$options[] = array(
//		'name' => __( 'Input Radio (one)', 'tt' ),
//		'desc' => __( 'Radio select with default options "one".', 'tt' ),
//		'id' => 'example_radio',
//		'std' => 'one',
//		'type' => 'radio',
//		'options' => $test_array
//	);
//
//	$options[] = array(
//		'name' => __( 'Example Info', 'tt' ),
//		'desc' => __( 'This is just some example information you can put in the panel.', 'tt' ),
//		'type' => 'info'
//	);
//
//	$options[] = array(
//		'name' => __( 'Input Checkbox', 'tt' ),
//		'desc' => __( 'Example checkbox, defaults to true.', 'tt' ),
//		'id' => 'example_checkbox',
//		'std' => '1',
//		'type' => 'checkbox'
//	);
//
//	$options[] = array(
//		'name' => __( 'Advanced Settings', 'tt' ),
//		'type' => 'heading'
//	);
//
//	$options[] = array(
//		'name' => __( 'Check to Show a Hidden Text Input', 'tt' ),
//		'desc' => __( 'Click here and see what happens.', 'tt' ),
//		'id' => 'example_showhidden',
//		'type' => 'checkbox'
//	);
//
//	$options[] = array(
//		'name' => __( 'Hidden Text Input', 'tt' ),
//		'desc' => __( 'This option is hidden unless activated by a checkbox click.', 'tt' ),
//		'id' => 'example_text_hidden',
//		'std' => 'Hello',
//		'class' => 'hidden',
//		'type' => 'text'
//	);
//
//	$options[] = array(
//		'name' => __( 'Uploader Test', 'tt' ),
//		'desc' => __( 'This creates a full size uploader that previews the image.', 'tt' ),
//		'id' => 'example_uploader',
//		'type' => 'upload'
//	);
//
//	$options[] = array(
//		'name' => "Example Image Selector",
//		'desc' => "Images for layout.",
//		'id' => "example_images",
//		'std' => "2c-l-fixed",
//		'type' => "images",
//		'options' => array(
//			'1col-fixed' => $imagepath . '1col.png',
//			'2c-l-fixed' => $imagepath . '2cl.png',
//			'2c-r-fixed' => $imagepath . '2cr.png'
//		)
//	);
//
//	$options[] = array(
//		'name' =>  __( 'Example Background', 'tt' ),
//		'desc' => __( 'Change the background CSS.', 'tt' ),
//		'id' => 'example_background',
//		'std' => $background_defaults,
//		'type' => 'background'
//	);
//
//	$options[] = array(
//		'name' => __( 'Multicheck', 'tt' ),
//		'desc' => __( 'Multicheck description.', 'tt' ),
//		'id' => 'example_multicheck',
//		'std' => $multicheck_defaults, // These items get checked by default
//		'type' => 'multicheck',
//		'options' => $multicheck_array
//	);
//
//	$options[] = array(
//		'name' => __( 'Colorpicker', 'tt' ),
//		'desc' => __( 'No color selected by default.', 'tt' ),
//		'id' => 'example_colorpicker',
//		'std' => '',
//		'type' => 'color'
//	);
//
//	$options[] = array( 'name' => __( 'Typography', 'tt' ),
//		'desc' => __( 'Example typography.', 'tt' ),
//		'id' => "example_typography",
//		'std' => $typography_defaults,
//		'type' => 'typography'
//	);
//
//	$options[] = array(
//		'name' => __( 'Custom Typography', 'tt' ),
//		'desc' => __( 'Custom typography options.', 'tt' ),
//		'id' => "custom_typography",
//		'std' => $typography_defaults,
//		'type' => 'typography',
//		'options' => $typography_options
//	);
//
//	$options[] = array(
//		'name' => __( 'Text Editor', 'tt' ),
//		'type' => 'heading'
//	);
//
//	/**
//	 * For $settings options see:
//	 * http://codex.wordpress.org/Function_Reference/wp_editor
//	 *
//	 * 'media_buttons' are not supported as there is no post to attach items to
//	 * 'textarea_name' is set by the 'id' you choose
//	 */
//
//	$wp_editor_settings = array(
//		'wpautop' => true, // Default
//		'textarea_rows' => 5,
//		'tinymce' => array( 'plugins' => 'wordpress,wplink' )
//	);
//
//	$options[] = array(
//		'name' => __( 'Default Text Editor', 'tt' ),
//		'desc' => sprintf( __( 'You can also pass settings to the editor.  Read more about wp_editor in <a href="%1$s" target="_blank">the WordPress codex</a>', 'tt' ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
//		'id' => 'example_editor',
//		'type' => 'editor',
//		'settings' => $wp_editor_settings
//	);

	return $options;
}

// TODO DEBUG mode option
// TODO jQuery 1.x/3.x switch option