<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/05 20:47
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
<<<<<<< HEAD
 * @link https://www.webapproach.net/tint
=======
 * @link https://webapproach.net/tint.html
>>>>>>> dev
 */
?>
<?php
    $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]);
?>

<?=$this->e($content)?>