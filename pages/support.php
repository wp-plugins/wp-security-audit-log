<?php /**
 * @kyos
 * Support page
 */ if(! WPPH::canRun()){ return; } ?>
<div id="wpph-pageWrapper" class="wrap">
    <h2 class="pageTitle pageTitle-support"><?php echo __('Support');?></h2>
    <div>
        <p><?php echo
            sprintf(__('If you encounter any issues running this plugin, or have suggestions, please get in touch with us on %s.'),
            '<a href="mailto:plugins@wpprohelp.com">plugins@wpprohelp.com</a>');?></p>
    </div>
</div>
