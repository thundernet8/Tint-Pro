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
    $theme_version = wp_get_theme()->get('Version');

    // 定义选项面板图片引用路径
    $imagepath =  THEME_URI . '/dashboard/of_inc/images/';

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
        'id' => 'tt_i18n',
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


	// 主题选项 - 样式设置
	$options[] = array(
		'name' => __( 'Style', 'tt' ),
		'type' => 'heading'
	);

	//


	// 主题设置 - 边栏设置
	$options[] = array(
		'name' => __( 'Sidebar', 'tt' ),
		'type' => 'heading'
	);


	//


	// 主题设置 - 社会化设置(包含管理员社会化链接等)
	$options[] = array(
		'name' => __( 'Social', 'tt' ),
		'type' => 'heading'
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


	//


	// 主题设置 - 辅助设置(包含短链接、SMTP工具等)
	$options[] = array(
		'name' => __( 'Auxiliary', 'tt' ),
		'type' => 'heading'
	);


	//


    // 主题反馈
    $options[] = array(
        'name' => __( 'Feedback', 'tt' ),
        'type' => 'heading'
    );


    //



    // 主题调试/更新
    //TODO: 版本升级 升级日志
    $options[] = array(
        'name' => __( 'Update', 'tt' ),
        'type' => 'heading'
    );


    // - 开启调试
    $options[] = array(
        'name' => __( 'Debug Mode', 'tt' ),
        'desc' => __( 'Enable debug will call wp_die when catch a error', 'tt' ),
        'id' => 'tt_theme_debug',
        'std' => false,
        'type' => 'checkbox'
    );




	///////////////////////////////////////////////////////////////////////////

	// Test data
	$test_array = array(
		'one' => __( 'One', 'tt' ),
		'two' => __( 'Two', 'tt' ),
		'three' => __( 'Three', 'tt' ),
		'four' => __( 'Four', 'tt' ),
		'five' => __( 'Five', 'tt' )
	);

	// Multicheck Array
	$multicheck_array = array(
		'one' => __( 'French Toast', 'tt' ),
		'two' => __( 'Pancake', 'tt' ),
		'three' => __( 'Omelette', 'tt' ),
		'four' => __( 'Crepe', 'tt' ),
		'five' => __( 'Waffle', 'tt' )
	);

	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );

	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);

	// Pull all the categories into an array
//	$options_categories = array();
//	$options_categories_obj = get_categories();
//	foreach ($options_categories_obj as $category) {
//		$options_categories[$category->cat_ID] = $category->cat_name;
//	}

	// Pull all tags into an array
//	$options_tags = array();
//	$options_tags_obj = get_tags();
//	foreach ( $options_tags_obj as $tag ) {
//		$options_tags[$tag->term_id] = $tag->name;
//	}


	// Pull all the pages into an array
//	$options_pages = array();
//	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
//	$options_pages[''] = 'Select a page:';
//	foreach ($options_pages_obj as $page) {
//		$options_pages[$page->ID] = $page->post_title;
//	}



	$options[] = array(
		'name' => __( 'Input Text Mini', 'tt' ),
		'desc' => __( 'A mini text input field.', 'tt' ),
		'id' => 'example_text_mini',
		'std' => 'Default',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Input Text', 'tt' ),
		'desc' => __( 'A text input field.', 'tt' ),
		'id' => 'example_text',
		'std' => 'Default Value',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Input with Placeholder', 'tt' ),
		'desc' => __( 'A text input field with an HTML5 placeholder.', 'tt' ),
		'id' => 'example_placeholder',
		'placeholder' => 'Placeholder',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Textarea', 'tt' ),
		'desc' => __( 'Textarea description.', 'tt' ),
		'id' => 'example_textarea',
		'std' => 'Default Text',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __( 'Input Select Small', 'tt' ),
		'desc' => __( 'Small Select Box.', 'tt' ),
		'id' => 'example_select',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $test_array
	);

	$options[] = array(
		'name' => __( 'Input Select Wide', 'tt' ),
		'desc' => __( 'A wider select box.', 'tt' ),
		'id' => 'example_select_wide',
		'std' => 'two',
		'type' => 'select',
		'options' => $test_array
	);

	if ( $options_categories ) {
		$options[] = array(
			'name' => __( 'Select a Category', 'tt' ),
			'desc' => __( 'Passed an array of categories with cat_ID and cat_name', 'tt' ),
			'id' => 'example_select_categories',
			'type' => 'select',
			'options' => $options_categories
		);
	}

	if ( $options_tags ) {
		$options[] = array(
			'name' => __( 'Select a Tag', 'options_check' ),
			'desc' => __( 'Passed an array of tags with term_id and term_name', 'options_check' ),
			'id' => 'example_select_tags',
			'type' => 'select',
			'options' => $options_tags
		);
	}

	$options[] = array(
		'name' => __( 'Select a Page', 'tt' ),
		'desc' => __( 'Passed an pages with ID and post_title', 'tt' ),
		'id' => 'example_select_pages',
		'type' => 'select',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => __( 'Input Radio (one)', 'tt' ),
		'desc' => __( 'Radio select with default options "one".', 'tt' ),
		'id' => 'example_radio',
		'std' => 'one',
		'type' => 'radio',
		'options' => $test_array
	);

	$options[] = array(
		'name' => __( 'Example Info', 'tt' ),
		'desc' => __( 'This is just some example information you can put in the panel.', 'tt' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => __( 'Input Checkbox', 'tt' ),
		'desc' => __( 'Example checkbox, defaults to true.', 'tt' ),
		'id' => 'example_checkbox',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Advanced Settings', 'tt' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Check to Show a Hidden Text Input', 'tt' ),
		'desc' => __( 'Click here and see what happens.', 'tt' ),
		'id' => 'example_showhidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Hidden Text Input', 'tt' ),
		'desc' => __( 'This option is hidden unless activated by a checkbox click.', 'tt' ),
		'id' => 'example_text_hidden',
		'std' => 'Hello',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Uploader Test', 'tt' ),
		'desc' => __( 'This creates a full size uploader that previews the image.', 'tt' ),
		'id' => 'example_uploader',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => "Example Image Selector",
		'desc' => "Images for layout.",
		'id' => "example_images",
		'std' => "2c-l-fixed",
		'type' => "images",
		'options' => array(
			'1col-fixed' => $imagepath . '1col.png',
			'2c-l-fixed' => $imagepath . '2cl.png',
			'2c-r-fixed' => $imagepath . '2cr.png'
		)
	);

	$options[] = array(
		'name' =>  __( 'Example Background', 'tt' ),
		'desc' => __( 'Change the background CSS.', 'tt' ),
		'id' => 'example_background',
		'std' => $background_defaults,
		'type' => 'background'
	);

	$options[] = array(
		'name' => __( 'Multicheck', 'tt' ),
		'desc' => __( 'Multicheck description.', 'tt' ),
		'id' => 'example_multicheck',
		'std' => $multicheck_defaults, // These items get checked by default
		'type' => 'multicheck',
		'options' => $multicheck_array
	);

	$options[] = array(
		'name' => __( 'Colorpicker', 'tt' ),
		'desc' => __( 'No color selected by default.', 'tt' ),
		'id' => 'example_colorpicker',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array( 'name' => __( 'Typography', 'tt' ),
		'desc' => __( 'Example typography.', 'tt' ),
		'id' => "example_typography",
		'std' => $typography_defaults,
		'type' => 'typography'
	);

	$options[] = array(
		'name' => __( 'Custom Typography', 'tt' ),
		'desc' => __( 'Custom typography options.', 'tt' ),
		'id' => "custom_typography",
		'std' => $typography_defaults,
		'type' => 'typography',
		'options' => $typography_options
	);

	$options[] = array(
		'name' => __( 'Text Editor', 'tt' ),
		'type' => 'heading'
	);

	/**
	 * For $settings options see:
	 * http://codex.wordpress.org/Function_Reference/wp_editor
	 *
	 * 'media_buttons' are not supported as there is no post to attach items to
	 * 'textarea_name' is set by the 'id' you choose
	 */

	$wp_editor_settings = array(
		'wpautop' => true, // Default
		'textarea_rows' => 5,
		'tinymce' => array( 'plugins' => 'wordpress,wplink' )
	);

	$options[] = array(
		'name' => __( 'Default Text Editor', 'tt' ),
		'desc' => sprintf( __( 'You can also pass settings to the editor.  Read more about wp_editor in <a href="%1$s" target="_blank">the WordPress codex</a>', 'tt' ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
		'id' => 'example_editor',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);

	return $options;
}

// TODO DEBUG mode option
// TODO jQuery 1.x/3.x switch option