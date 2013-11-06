<?php if(! WPPHUtil::canViewPage()){ return; } ?>
<?php
if(! WPPH::ready())
{
    $errors = WPPH::getPluginErrors();
    foreach($errors as $error) {
        wpph_adminNotice($error);
    }
    echo '<div id="wpph-pageWrapper" class="wrap">';
    echo '<p>'.__('We have encountered some errors during the installation of the plugin which you can find above.',WPPH_PLUGIN_TEXT_DOMAIN).'</p>';
    echo '<p>'.__('Please try to correct them and then reactivate the plugin.',WPPH_PLUGIN_TEXT_DOMAIN).'</p>';
    echo '</div>';
    return;
}
?>

<div id="wpph-pageWrapper" class="wrap">
    <h2 class="pageTitle pageTitle-eventViewer">Audit Log Viewer</h2>
    <div id="EventViewerWrapper">
        <div style="overflow: hidden; display: block; clear: both;">
            <div class="tablenav top" style="overflow: hidden; padding: 4px 0;">
                <div class="alignleft">
                    <div style="overflow: hidden;">
                        <input type="button" class="buttonRefreshEventsList button" value="<?php echo __('Refresh Security Alerts List',WPPH_PLUGIN_TEXT_DOMAIN);?>"
                               style="float: left; display: block;" data-bind="disable: loading, click: cleanRefresh"/>
                        <span class="ajaxLoaderWrapper" style="float: left; display: block; width: 20px; height: 20px; padding: 7px 7px;"><img/></span>
                    </div>
                </div>
                <div class="alignleft actions" style="overflow: hidden;">
                    <label class="alignleft" style="margin: 5px 5px 0 0;"><?php echo __('Number of security alerts per page:',WPPH_PLUGIN_TEXT_DOMAIN);?></label>
                    <select name="actionLimit1" class="actionLimit" data-bind="options: availablePageSize, value: selectedPageSize"></select>
                    <input type="button" value="Apply" class="button action" data-bind="disable: loading, click: applyPageSize">
                </div>

                <div class="tablenav-pages">
                    <span class="displaying-num" data-bind="text: totalEventsCount()+' security alerts'"></span>
                    <span class="pagination-links"><a href="#" title="Go to the first page" class="first-page" data-bind="click: firstPage, css: {disabled: offset() <= 0}">«</a>
                    <a href="#" title="Go to the previous page" class="prev-page" data-bind="click: prevPage, disable: loading, click: prevPage, css: {disabled: offset() <= 0}">‹</a>
                    <span class="paging-input">
                        <input type="text" size="1" id="fdr" title="Current page" class="current-page"
                               data-bind="value: currentPage, event: {keydown: onCurrentPageInputKeyDown} "/> of <span class="total-pages" data-bind="text: pageCount"></span>
                    </span>
                    <a href="#" title="Go to the next page" class="next-page" data-bind="click: nextPage, disable: loading, click: nextPage, css: {disabled: offset() + events().length >= totalEventsCount() - 1}">›</a>
                    <a href="#" title="Go to the last page" class="last-page" data-bind="click: lastPage, css: {disabled: offset() + events().length >= totalEventsCount() - 1}">»</a></span>
                </div>

            </div>
        </div>
        <table class="wp-list-table widefat fixed" cellspacing="0" cellpadding="0">
            <thead>
                <tr data-bind="foreach: columns">
                    <th class="manage-column column-left-align" scope="col"
                        data-bind="style: {width: columnWidth}, css: { sortable: sortable, sorted: sorted, desc: sortable && sortedDescending(), asc: sortable && !sortedDescending()}">
                        <a href="#" data-bind="disable: $root.loading, click: $data.sortable ? $root.applySorting.bind($data.columnName, $root) : function() { return false; }">
                            <span data-bind="text: columnHeader"></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr data-bind="foreach: columns">
                    <th class="manage-column column-left-align" scope="col"
                        data-bind="style: {width: columnWidth}, css: { sortable: sortable, sorted: sorted, desc: sortable && sortedDescending(), asc: sortable && !sortedDescending()}">
                        <a href="#" data-bind="disable: $root.loading, click: $data.sortable ? $root.applySorting.bind($data.columnName, $root) : function() { return false; }">
                            <span data-bind="text: columnHeader"></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </tfoot>
            <tbody id="the-list">
                <tr data-bind="if: events().length == 0"><td style="padding: 4px !important;" colspan="7"><?php echo __('No security alerts',WPPH_PLUGIN_TEXT_DOMAIN);?></td></tr>
                <!-- ko foreach: events -->
                <tr data-bind="css: {'row-0': ($index() % 2) == 0, 'row-1': ($index() % 2) != 0}">
                    <td class="column-event_number"><span data-bind="text: eventNumber"></span></td>
                    <td class="column-event_id"><span data-bind="text: eventId"></span></td>
                    <td class="column-event_date"><span data-bind="text: eventDate"></span></td>
                    <td class="column-event_category"><span data-bind="text: EventType"></span></td>
                    <td class="column-ip"><span data-bind="text: ip"></span></td>
                    <td class="column-user"><span data-bind="text: user"></span></td>
                    <td class="column-description"><span data-bind="html: description"></span></td>
                </tr>
                <!-- /ko -->
            </tbody>
        </table>
        <div style="overflow: hidden; display: block; clear: both;">
            <div class="tablenav top" style="overflow: hidden; padding: 4px 0;">
                <div class="alignleft">
                    <div style="overflow: hidden;">
                        <input type="button" class="buttonRefreshEventsList button" value="<?php echo __('Refresh security alerts List',WPPH_PLUGIN_TEXT_DOMAIN);?>"
                               style="float: left; display: block;" data-bind="disable: loading, click: cleanRefresh"/>
                        <span class="ajaxLoaderWrapper" style="float: left; display: block; width: 20px; height: 20px; padding: 7px 7px;"><img/></span>
                    </div>
                </div>
                <div class="alignleft actions" style="overflow: hidden;">
                    <label class="alignleft" style="margin: 5px 5px 0 0;"><?php echo __('Number of security alerts per page:',WPPH_PLUGIN_TEXT_DOMAIN);?></label>
                    <select name="actionLimit1" class="actionLimit" data-bind="options: availablePageSize, value: selectedPageSize"></select>
                    <input type="button" value="Apply" class="button action" data-bind="disable: loading, click: applyPageSize">
                </div>
                <div class="tablenav-pages">
                    <span class="displaying-num" data-bind="text: totalEventsCount()+' security alerts'"></span>
                    <span class="pagination-links"><a href="#" title="Go to the first page" class="first-page" data-bind="click: firstPage, css: {disabled: offset() <= 0}">«</a>
                    <a href="#" title="Go to the previous page" class="prev-page" data-bind="click: prevPage, disable: loading, click: prevPage, css: {disabled: offset() <= 0}">‹</a>
                    <span class="paging-input">
                        <input type="text" size="1" id="fdr" title="Current page" class="current-page"
                               data-bind="value: currentPage, event: {keydown: onCurrentPageInputKeyDown} "/> of <span class="total-pages" data-bind="text: pageCount"></span>
                    </span>
                    <a href="#" title="Go to the next page" class="next-page" data-bind="click: nextPage, disable: loading, click: nextPage, css: {disabled: offset() + events().length >= totalEventsCount() - 1}">›</a>
                    <a href="#" title="Go to the last page" class="last-page" data-bind="click: lastPage, css: {disabled: offset() + events().length >= totalEventsCount() - 1}">»</a></span>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    // configure ajax loader
    var __ajaxLoaderTargetElement__ = jQuery('.ajaxLoaderWrapper img');
    var AjaxLoaderCreate = function(e){
        var imgPath = "<?php echo WPPH_PLUGIN_URL.'res/img/ajax-loader.gif';?>";
        e.attr('src',imgPath);
    }(__ajaxLoaderTargetElement__);
    var AjaxLoaderShow = function(e){ e.show(); };
    var AjaxLoaderHide = function(e){ e.hide(); };

    jQuery(document).ready(function($) {
        var myViewModel = new AuditLogViewModel();
        ko.applyBindings(myViewModel, $('#wpph-pageWrapper').get(0));
        myViewModel.orderBy('EventNumber');
        myViewModel.orderByDescending(true);
        myViewModel.cleanRefresh(myViewModel);
    });
</script>