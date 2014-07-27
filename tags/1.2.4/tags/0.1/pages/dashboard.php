<?php /**
 * @kyos
 * Dashboard page
 */ if(! WPPH::canRun()){ return; }?>
<div id="wpph-pageWrapper" class="wrap">
    <h2 class="pageTitle pageTitle-eventViewer"><?php echo __('Audit Log Viewer');?></h2>
    <div id="EventViewerWrapper">
        <div style="overflow: hidden; display: block; clear: both;">
            <div class="tablenav top" style="overflow: hidden; padding: 4px 0;">
                <div class="alignleft">
                    <div style="overflow: hidden;">
                        <input type="button" class="buttonRefreshEventsList button" value="<?php echo __('Refresh Events List');?>"
                               style="float: left; display: block;" data-bind="disable: loading, click: cleanRefresh"/>
                        <span class="ajaxLoaderWrapper" style="float: left; display: block; width: 20px; height: 20px; padding: 7px 7px;"><img/></span>
                    </div>
                </div>
                <div class="alignleft actions" style="overflow: hidden;">
                    <label class="alignleft" style="margin: 5px 5px 0 0;"><?php echo __('Number of events per page:');?></label>
                    <select name="actionLimit1" class="actionLimit" data-bind="options: availablePageSize, value: selectedPageSize"></select>
                    <input type="button" value="Apply" class="button action" data-bind="disable: loading, click: applyPageSize">
                </div>
                <div class="paginationWrapper" data-bind="visible: totalEventsCount">
                    <span class="showPages"><span class="span1" data-bind="text: totalEventsCount() > 0 ? 1 + offset() : 0"></span>-
                        <span class="span2" data-bind="text: offset() + events().length"></span> <?php echo __('of');?>
                        <span class="span3" data-bind="text: totalEventsCount"></span></span>
                    <div class="buttonsWrapper">
                        <button class="pageButton buttonNext" title="Next" href="#" data-bind="disable: loading, click: nextPage, css: {wpphButtonDisabled: offset() + events().length >= totalEventsCount() - 1}"><span>&gt;</span></button>
                        <button class="pageButton buttonPrevious" title="Previous" href="#" data-bind="disable: loading, click: prevPage, css: {wpphButtonDisabled: offset() <= 0}"><span>&lt;</span></button>
                    </div>
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
                <tr data-bind="if: events().length == 0"><td style="padding: 4px !important;" colspan="7"><?php echo __('No events');?></td></tr>
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
                        <input type="button" class="buttonRefreshEventsList button" value="<?php echo __('Refresh Events List');?>"
                               style="float: left; display: block;" data-bind="disable: loading, click: cleanRefresh"/>
                        <span class="ajaxLoaderWrapper" style="float: left; display: block; width: 20px; height: 20px; padding: 7px 7px;"><img/></span>
                    </div>
                </div>
                <div class="alignleft actions" style="overflow: hidden;">
                    <label class="alignleft" style="margin: 5px 5px 0 0;"><?php echo __('Number of events per page:');?></label>
                    <select name="actionLimit1" class="actionLimit" data-bind="options: availablePageSize, value: selectedPageSize"></select>
                    <input type="button" value="Apply" class="button action" data-bind="disable: loading, click: applyPageSize">
                </div>
                <div class="paginationWrapper" data-bind="visible: totalEventsCount">
                    <span class="showPages"><span class="span1" data-bind="text: totalEventsCount() > 0 ? 1 + offset() : 0"></span>-
                        <span class="span2" data-bind="text: offset() + events().length"></span> <?php echo __('of');?>
                        <span class="span3" data-bind="text: totalEventsCount"></span></span>
                    <div class="buttonsWrapper">
                        <button class="pageButton buttonNext" title="<?php echo __('Next');?>" href="#" data-bind="disable: loading, click: nextPage, css: {wpphButtonDisabled: offset() >= totalEventsCount() - 1}"><span>&gt;</span></button>
                        <button class="pageButton buttonPrevious" title="<?php echo __('Previous');?>" href="#" data-bind="disable: loading, click: prevPage, css: {wpphButtonDisabled: offset() <= 0}"><span>&lt;</span></button>
                    </div>
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