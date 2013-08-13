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

                if (viewModel.offset() == 0)
                    viewModel.currentPage(1);
                else
                    viewModel.currentPage(1 + viewModel.offset() / viewModel.pageSize());

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
            {columnHeader: 'IP Address', columnName: 'UserIP', sortable: true, columnWidth: '130px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'User', columnName: 'UserID', sortable: true, columnWidth: '240px', sorted: ko.observable(false), sortedDescending: ko.observable(false)},
            {columnHeader: 'Description', columnName: 'EventDescription', sortable: false, columnWidth: 'auto', sorted: ko.observable(false), sortedDescending: ko.observable(false)}]);

        this.loading = ko.observable(false);
        this.events = ko.observableArray([]);
        this.totalEventsCount = ko.observable(0);
        this.offset = ko.observable(0);
        this.availablePageSize = ko.observableArray([25, 50, 100]);

        var _initialPageSize = parseInt($.cookie('wpph_ck_page_size'));
        if (this.availablePageSize.indexOf(_initialPageSize) < 0) {
            _initialPageSize = this.availablePageSize()[1];
        }

        this.pageSize = ko.observable(_initialPageSize);
        this.selectedPageSize = ko.observable(_initialPageSize);
        this.pageCount = ko.computed(function() {
            return Math.ceil(this.totalEventsCount() / this.pageSize());
        }, this);

        this. _currentPageIndex = 1;
        var vm = this;
        this.currentPage = ko.computed({
            read: function() {
                return this._currentPageIndex;
            },
            write: function(value) {
                value = parseInt(value);
                if (isNaN(value) || value < 1 || value > this.pageCount()) {
                    return;
                }
                this._currentPageIndex = value;
                this.currentPage.notifySubscribers();
                $('#fdr').val(this._currentPageIndex);
            },
            owner: vm
        });

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

    AuditLogViewModel.prototype.onCurrentPageInputKeyDown = function(viewModel, event) {
        if (event.keyCode === 13) {
            var value = parseInt(event.currentTarget.value);
            if (isNaN(value) || value < 1 || value > viewModel.pageCount()) {
                viewModel.currentPage(viewModel._currentPageIndex);
                viewModel.currentPage.notifySubscribers();
                return;
            }
            viewModel.currentPage(value);
            viewModel.refreshEvents(viewModel, ( viewModel._currentPageIndex - 1) * viewModel.pageSize());
            return false;
        }
        return true;
    };



    AuditLogViewModel.prototype.applyPageSize = function(viewModel){
        var newPageSize = parseInt(viewModel.selectedPageSize());
        viewModel.pageSize(newPageSize);
        var secureCookie = false;
        if (window.location.href.indexOf('https://') > -1) {
            secureCookie = true;
        }
        $.cookie('wpph_ck_page_size', newPageSize, {secure: secureCookie});
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

    //TODO
    AuditLogViewModel.prototype.firstPage = function(viewModel) {
        if (viewModel.offset() > 0)
            viewModel.refreshEvents(viewModel, 0);
    };

    //TODO
    AuditLogViewModel.prototype.lastPage = function(viewModel) {
        var offset = Math.min(
            viewModel.totalEventsCount(),
            viewModel.pageSize() * (viewModel.pageCount() - 1)
        );
        if (viewModel.offset() != offset)
            viewModel.refreshEvents(viewModel, offset);
    };


    AuditLogViewModel.prototype.refreshEvents = function(viewModel, offset) {
        loadRemoteData(viewModel, offset);
    };

    AuditLogViewModel.prototype.cleanRefresh = function(viewModel) {
        loadRemoteData(viewModel, 0);
    };

    return AuditLogViewModel;

})(jQuery);

