var AuditLogViewModel = (function($) {

    function loadRemoteData(viewModel, offset) {
        var data = {
            'action': 'wpph_get_events',

            'orderBy': viewModel.orderBy(),
            'sort': viewModel.orderByDescending() ? 'desc' : 'asc',

            'offset': offset,
            'count': viewModel.pageSize()
        };

        AjaxLoaderShow(__ajaxLoaderTargetElement__);

        $.ajax({
            url: ajaxurl,
            cache: false,
            type: 'POST',
            data: data,
            beforeSend: function() {
                viewModel.loading(true)
            },
            success: function(response) {
                viewModel.loading(false);
                var json = $.parseJSON(response);

                if (json.error.length > 0) {
                    AjaxLoaderHide(__ajaxLoaderTargetElement__);
                    return;
                }

                AjaxLoaderHide(__ajaxLoaderTargetElement__);

                viewModel.events(json.dataSource.events);
                viewModel.totalEventsCount(json.dataSource.eventsCount);
                viewModel.offset(offset);

                if (viewModel.totalEventsCount() < viewModel.offset()) {
                    viewModel.offset(0);
                }
            },
            error: function() {
                viewModel.loading(false);
            }
        });
    }

    function AuditLogViewModel()
    {
        this.columns = ko.observableArray([
            {columnHeader: 'Event', columnName: 'EventNumber', sortable: true, columnWidth: '80px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'ID', columnName: 'EventID', sortable: true, columnWidth: '80px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'Date', columnName: 'EventDate', sortable: true, columnWidth: '170px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'Type', columnName: 'EventType', sortable: true, columnWidth: '100px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'IP Address', columnName: 'UserIP', sortable: true, columnWidth: '100px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'User', columnName: 'UserID', sortable: true, columnWidth: '240px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'Description', columnName: 'EventDescription', sortable: false, columnWidth: 'auto', sorted: ko.observable(false), sortedDescending: ko.observable(false)}]);

        this.loading = ko.observable(false);
        this.events = ko.observableArray([]);
        this.totalEventsCount = ko.observable(0);
        this.offset = ko.observable(0);

        this.pageSize = ko.observable(50);
        this.selectedPageSize = ko.observable(50);

        this.availablePageSize = ko.observableArray([25, 50, 100]);
        this.orderBy = ko.computed({
            read: function() {
                var columnInfo = ko.utils.arrayFirst(this.columns(), function(item) { return item.sorted(); })
                return columnInfo && columnInfo.columnName || '';
            },
            write: function(value) {
                var columnInfo = ko.utils.arrayFirst(this.columns(), function(item) {
                    return item.columnName === value;
                });
                if (columnInfo) {
                    ko.utils.arrayForEach(this.columns(), function(item) {
                        item.sorted(false);
                        item.sortedDescending(false);
                    });
                    columnInfo.sorted(value)
                }
            }
        }, this);

        this.orderByDescending = ko.computed({
            read: function() {
                var columnInfo = ko.utils.arrayFirst(this.columns(), function(item) { return item.sorted(); })
                return columnInfo && columnInfo.sortedDescending();
            },
            write: function(value) {
                var columnInfo = ko.utils.arrayFirst(this.columns(), function(item) { return item.sorted(); })
                columnInfo && columnInfo.sortedDescending(value);
            }
        }, this);
    }

    AuditLogViewModel.prototype.applyPageSize = function(viewModel){
        viewModel.pageSize(parseInt(viewModel.selectedPageSize()));
        viewModel.refreshEvents(viewModel, 0);
    };

    AuditLogViewModel.prototype.applySorting = function(viewModel, columnInfo) {
        if (viewModel.orderBy() == columnInfo.columnName) {
            viewModel.orderByDescending(! viewModel.orderByDescending());
        }
        else {
            viewModel.orderBy(columnInfo.columnName);
            viewModel.orderByDescending(false);
        }
        viewModel.refreshEvents(viewModel, 0);
    };

    AuditLogViewModel.prototype.nextPage = function(viewModel) {
        var currentOffset = viewModel.offset();
        var newOffset = currentOffset + viewModel.pageSize();

        if (newOffset < viewModel.totalEventsCount()) {
            viewModel.refreshEvents(viewModel, newOffset);
        }
    };

    AuditLogViewModel.prototype.prevPage = function(viewModel) {
        var currentOffset = viewModel.offset();
        var newOffset = currentOffset - viewModel.pageSize();

        if (newOffset >= 0) {
            viewModel.refreshEvents(viewModel, newOffset);
        }
    };

    AuditLogViewModel.prototype.refreshEvents = function(viewModel, offset) {
        loadRemoteData(viewModel, offset);
    };

    AuditLogViewModel.prototype.cleanRefresh = function(viewModel) {
        loadRemoteData(viewModel, 0);
    };

    return AuditLogViewModel;

})(jQuery);

