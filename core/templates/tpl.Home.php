<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * Home Page Template
 *
 * @since 2.0.0
 *
 * @author Zhiyan
 * @date 2016/08/22 22:48
 * @license GPL v3 LICENSE
 */
?>
<?php tt_get_header(); ?>

<a id="example" class="btn btn-lg btn-danger" role="button" data-toggle="msgbox" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?" data-msgtype="error" data-animation="slide-from-top">Dismissible popover</a>

<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Tooltip on left</button>

<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Tooltip on top</button>

<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">Tooltip on bottom</button>

<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Tooltip on right</button>

<section id="test" style="margin: 100px 20px;">
    <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
        Popover on left
    </button>

    <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
        Popover on top
    </button>

    <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Vivamus
sagittis lacus vel augue laoreet rutrum faucibus.">
        Popover on bottom
    </button>
    <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
        Popover on right
    </button>

    <a tabindex="0" class="btn btn-lg btn-danger" role="button" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?">Dismissible popover</a>
</section>

<?php tt_get_footer(); ?>
