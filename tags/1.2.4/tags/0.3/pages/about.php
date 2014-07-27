<?php if(! WPPH::canRun()){ return; } ?>
<?php
    if(! WPPH::ready())
    {
        $errors = WPPH::getPLuginErrors();
        foreach($errors as $k =>$v) { call_user_func(array('WPPHAdminNotices',$k),$v); }

        echo '<div id="wpph-pageWrapper" class="wrap">';
        echo '<p>We have encountered some errors during the installation of the plugin which you can find above.</p>';
        echo '<p>Please try to correct them and then reactivate the plugin.</p>';
        echo '</div>';
        return;
    }
?>
<div id="wpph-pageWrapper" class="wrap">
    <h2 class="pageTitle pageTitle-about"><?php echo __('About us');?></h2>
    <div>
        <p><?php echo sprintf(
                __('WP Security Audit Log is a WordPress security plugin developed by %s.'),
                    '<a href="http://www.wpwhitesecurity.com">WP White Security</a>');?></p>
    </div>
</div>