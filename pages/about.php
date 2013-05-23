<?php /**
 * @kyos
 * About us page
 */ if(! WPPH::canRun()){ return; } ?>
<div id="wpph-pageWrapper" class="wrap">
    <h2 class="pageTitle pageTitle-about"><?php echo __('About us');?></h2>
    <div>
        <p><?php echo sprintf(
                __('WP Security Audit Log is a WordPress security plugin developed by %s.'),
                    '<a href="http://www.wpprohelp.com">WPProHelp.com</a>');?></p>
    </div>
</div>