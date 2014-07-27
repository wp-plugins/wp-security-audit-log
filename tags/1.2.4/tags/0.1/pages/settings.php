<?php /**
 * @kyos
 * Options page
 */if(! WPPH::canRun()){ return; }

//#! defaults
$opt = WPPH::getPluginSettings();
$daysInput = 0;
$eventsNumber = 0;
$showEventsViewList = 50;

if(!empty($opt->daysToKeep)){
    $daysInput = $opt->daysToKeep;
}
if(! empty($opt->eventsToKeep)){
    $eventsNumber = $opt->eventsToKeep;
}
if(! empty($opt->showEventsViewList)){
    $showEventsViewList = $opt->showEventsViewList;
}

//#! end defaults

$validationMessage = array();
//#! If post : section #1
if ( !empty($_POST['wpph_update_settings_field']) )
{
    if(isset($_POST['wpph_update_settings_field'])){
        if(!wp_verify_nonce($_POST['wpph_update_settings_field'],'wpph_update_settings')){
            wp_die(__('Invalid request.'));
        }
    }
    else {wp_die(__('Invalid request.'));}

    // validate fields
    $section = intval($_POST['sectionInputField']);
    if(! in_array($section, array(1,2))){
        $validationMessage['error'] = __('Error: Invalid form. Please try again.');
    }

    //#! get settings
    $daysInput = $eventsNumber = 0;
    $eventsNumber = 10000; // default
    $opt = WPPH::getPluginSettings();
    if($section == 1)
    {
        if(empty($_POST['daysInput'])){
            $validationMessage['error'] = __('Error: Invalid form. Please try again.');
            $hasErrors = true;
        }
        else
        {
            $daysInput = intval($_POST['daysInput']);

            if($daysInput == 0){
                $validationMessage['error'] = __('Please input the number of days.');
                $hasErrors = true;
            }
            elseif($daysInput > 365){
                $validationMessage['error'] = __('Incorrect number of days. Please specify a value between 1 and 365.');
                $hasErrors = true;
            }

            if(! $hasErrors)
            {
                // reset events number
                if(isset($opt->eventsToKeep)){
                    $opt->eventsToKeep = 0;
                }
                $opt->daysToKeep = $daysInput;
            }
        }
    }
    elseif($section == 2)
    {
        if(empty($_POST['eventsNumberInput'])){
            $validationMessage['error'] = __('Error: Invalid form. Please try again.');
            $hasErrors = true;
        }
        else
        {
            $eventsNumber = intval($_POST['eventsNumberInput']);

            if($eventsNumber == 0){
                $validationMessage['error'] = __('Please input the number of events to keep.');
                $hasErrors = true;
            }
            elseif($eventsNumber > 10000){
                $validationMessage['error'] = __('Incorrect number of events. Please specify a value between 1 and 10,000.');
                $hasErrors = true;
            }

            if(! $hasErrors)
            {
                // reset days
                if(isset($opt->daysToKeep)){
                    $opt->daysToKeep = 0;
                }
                $opt->eventsToKeep = $eventsNumber;
            }
        }
    }
    else { $validationMessage['error'] = __('Error: Invalid form. Please try again.'); }


    if(! $hasErrors)
    {
        $opt->cleanupRan = 0;
        WPPH::updatePluginSettings($opt,null,null,true);
        $validationMessage['success'] = __('Your settings have been saved.');

        //#! get updated settings
        $opt = WPPH::getPluginSettings();
        $daysInput = $opt->daysToKeep;
        $eventsNumber = $opt->eventsToKeep;
    }
}
//#! end $post
?>
<div id="wpph-pageWrapper" class="wrap">
    <h2 class="pageTitle pageTitle-settings"><?php echo __('Settings');?></h2>

    <div style="width:48%; margin: 30px 0 0 0; float: left;" class="inner-sidebar1 postbox">
        <h3 class="hndle" style="padding: 5px 5px; font-size: 15px;"><span><strong><?php echo __('Events Auto Deletion');?></strong></span></h3>
        <div class="inside">
            <?php if(! empty($validationMessage)) : ?>
                <?php
                    if(!empty($validationMessage['error'])){
                        echo '<div id="errMessage" class="error-info-icon" style="display: block;">'.$validationMessage['error'].'</div>';
                    }
                    else { echo '<div id="errMessage" class="success-info-icon" style="display: block;">'.$validationMessage['success'].'</div>'; }
                ?>
            <?php else : ?>
                <div id="errMessage" class="error-info-icon" style="display: none;"></div>
            <?php endif;?>
            <div style="margin: 5px 10px 0 10px; background: #fafafa; padding: 1px 10px;">
                <p><?php echo __('From this section you can configure the retention of the WordPress event logs. If no option is configured, all the event logs will be kept.');?></p>
            </div>
            <div style="padding: 10px 10px">
                <form id="updateOptionsForm" method="post">
                    <?php wp_nonce_field('wpph_update_settings','wpph_update_settings_field'); ?>
                    <div id="section1" class="form-section">
                        <input type="radio" id="option1" class="radioInput" name="options[]" value="e1" style="margin-top: 0;" checked="checked"/>
                        <label for="option1"><?php echo __('Delete events older than');?></label>
                        <input type="text" id="daysInput" name="daysInput" maxlength="3"
                               placeholder="<?php echo __('(1 to 365)');?>"
                               value="<?php if(! empty($daysInput)) { echo $daysInput; } ;?>"/>
                        <span> <?php echo __('(1 to 365 days)');?></span>
                    </div>
                    <div id="section2" class="form-section">
                        <input type="radio" id="option2" class="radioInput" name="options[]" value="e2" style="margin-top: 0;"/>
                        <label for="option2"><?php echo __('Keep up to');?></label>
                        <input type="text" id="eventsNumberInput" name="eventsNumberInput" maxlength="6"
                               placeholder="<?php echo __('1 to 10,000');?>"
                               value="<?php if(! empty($eventsNumber)) { echo $eventsNumber; } ;?>"/>
                        <span> <?php echo __('(1 to 10,000 events)');?></span>
                    </div>
                    <div class="form-section"><input type="submit" id="submitButton" class="button" value="<?php echo __('Save settings');?>"/></div>
                    <input type="hidden" id="sectionInputField1" name="sectionInputField"/>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                var showErrorMessage = function(msg){
                    var errWrapper =  $('#errMessage');
                    errWrapper.html("Error: "+msg).show();
                };
                var hideErrorMessage = function(){ $('#errMessage').hide(); };
                var setFocusOn = function($e){
                    $e.focus();
                    $e.select();
                };

                $('#updateOptionsForm :input').click(function(){ hideErrorMessage(); });

                //#! select the radio input to check
                <?php if(! empty($daysInput)){ ?>
                $('#option1').attr('checked', 'checked');
                <?php } elseif(! empty($eventsNumber)){ ?>
                $('#option2').attr('checked', 'checked');
                <?php };?>

                // select radio on input click
                $('#daysInput').click(function(){ $('#option1').attr('checked', 'checked'); });
                $('#eventsNumberInput').click(function(){ $('#option2').attr('checked', 'checked'); });

                $('#updateOptionsForm').submit(function()
                {
                    var section = 0;
                    if ($('#option1').attr('checked') == 'checked'){section = 1;}
                    else { section = 2; }

                    // validate fields
                    if(section == 1)
                    {
                        var $daysInput = $('#daysInput'),
                            daysInputVal = $daysInput.val();

                        if(daysInputVal.length == 0){
                            showErrorMessage("<?php echo __('Please input the number of days.');?>");
                            setFocusOn($daysInput);
                            return false;
                        }
                        if(daysInputVal == 0){
                            showErrorMessage("<?php echo __('Please input a number greater than 0.');?>");
                            setFocusOn($daysInput);
                            return false;
                        }
                        if(!/^\d+$/.test(daysInputVal)){
                            showErrorMessage("<?php echo __('Only numbers greater than 0 allowed.');?>");
                            setFocusOn($daysInput);
                            return false;
                        }
                        if(daysInputVal > 365){
                            showErrorMessage("<?php echo __('Incorrect number of days. Please specify a value between 1 and 365.');?>");
                            setFocusOn($daysInput);
                            return false;
                        }
                    }
                    else if(section == 2)
                    {
                        var $eventsNumberInput = $('#eventsNumberInput'),
                            eniVal = $eventsNumberInput.val();

                        if(eniVal.length == 0){
                            showErrorMessage("<?php echo __('Please input the number of events.');?>");
                            setFocusOn($eventsNumberInput);
                            return false;
                        }
                        if(eniVal == 0){
                            showErrorMessage("<?php echo __('Please input a number greater than 0.');?>");
                            setFocusOn($eventsNumberInput);
                            return false;
                        }
                        if(!/^\d+$/.test(eniVal)){
                            showErrorMessage("<?php echo __('Only numbers greater than 0 allowed.');?>");
                            setFocusOn($eventsNumberInput);
                            return false;
                        }
                        if(eniVal > 500000){
                            showErrorMessage("<?php echo __('Incorrect number of events. Please specify a value between 1 and 10,000.');?>");
                            setFocusOn($eventsNumberInput);
                            return false;
                        }
                    }
                    $('#sectionInputField1').val(section);

                    //#! clear the other section
                    if(section == 1){ $('#eventsNumberInput').val(''); }
                    else if(section == 2){ $('#daysInput').val(''); }

                    return true;
                });
            });
        </script>
    </div>
<br class="clear"/>
</div>