<?php

/**
 * Copyright 2016, Zhiyanblog.com
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/5/27 17:01
 * @license GPL v3 LICENSE
 */
 
?>

<?php

/* 集成option_tree框架 */
add_filter('ot_show_pages', '__return_false');
add_filter('ot_show_new_layout', '__return_false');
add_filter('ot_theme_mode', '__return_true');
add_filter('ot_show_settings_import', '__return_true');
add_filter('ot_show_settings_export', '__return_true');
add_filter('ot_show_docs', '__return_false');

load_template(THEME_DIR.'/dashboard/option/ot-loader.php');
