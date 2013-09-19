
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
    <h2 class="pageTitle pageTitle-support"><?php echo __('Support');?></h2>
    <div>
        <p><?php echo
            sprintf(__('Thank you for showing interest and using our plugin. If you encounter any issues running this plugin, or have suggestions or queries, please get in touch with us on %s.'),
            '<a href="mailto:plugins@wpwhitesecurity.com">plugins@wpwhitesecurity.com</a>');?></p>
    </div>
</div>
