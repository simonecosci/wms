//<script>

/*!
 * jQuery Kendo JavaScript Library v3
 *
 * Released under the MIT license
 * Copyright (c) 2018 Simone Cosci
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

/**
 * @package Application
 * @version 3.0
 * @author Simone Cosci <simone.cosci@gmail.com>
 * @copyright Copyright 2018
 * @abstract
 * */

/*
 * 
 * @param object $
 * @param object window
 * @param undefined
 * @returns function
 */

(function (/*object*/ $, /*object*/window, /*undefined*/undefined) {

    window.workers = {};

    var Application,
            extend = $.extend,
            kendo = window.kendo,
            doc = window.document,
            keysOf = Object.keys,
            animation = {
                show: {
                    effects: "fadeIn",
                    duration: 300
                },
                hide: {
                    effects: "fadeOut",
                    duration: 300
                }
            },
            supports = {
                pushState: ("pushState" in window.history)
            };
    /**
     * Default ajax config
     */

    var ajaxSetup = {
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        onError: function (message, title) {
            try {
                var m = JSON.parse(message);
                Application.Warning(Application.ui.dump(m), "Error");
            } catch (e) {
                if (message)
                    Application.Warning(message, title);
            }
        },
        statusCode: {
            400: function (e) {
                var title = "An Error Occurred";
                var message = "<h1>Warning,</h1>Your request can't be processed.<br>";
                message += e.message;
                Application.Warning(message, title);
            },
            401: function (e) {
                var title = "Access denied";
                var message = "<h1>Warning,</h1>Your request can't be processed.<br>";
                message += "<b>Your session is expired</b> or you have not enough privileges<br>";
                message += "The Application will be reloaded.<br>";
                message += "Please Login if you are not and if the problem persists contact an administrator.";
                Application.Warning(message, title, function () {
                    $("body").hide();
                    window.location = "/";
                });
            },
            404: function (e) {
                var title = "Resource not found";
                var message = "<h1>Warning,</h1>Your request can't be processed.<br>";
                message += "The resource you are trying to reach seems to be unavailable.";
                Application.Warning(message, title);
            },
            422: function (e) {
                var title = "An Error Occurred";
                var message = "<h1>Warning,</h1>Your request can't be processed.<br>";
                message += e.message;
                Application.Warning(message, title);
            },
            500: function (e) {
                var re = {
                    MYSQL_DUPLICATE_ENTRY: /SQLSTATE\[23000\]/
                };
                var title = e.statusText;
                var message = e.responseText;
                var rk = keysOf(re);
                for (var i = 0; i < rk.length; i++) {
                    if (re[rk[i]].test(e.responseText)) {
                        switch (rk[i]) {
                            case "MYSQL_DUPLICATE_ENTRY" :
                                message = "<h1>Warning,</h1>Your request can't be processed.<br>";
                                message += "You are trying to insert or update a set of elements having values that probably are already present in the database<br>";
                                message += "Please check your actual data and verify if it's not present.<br>";
                                message += "If the problem persists contact an administrator.";
                                break;
                        }
                    }
                }
                this.onError(message, title);
            }
        }
    };
    var ajaxSetupSilent = {
        onError: function () {
        },
        statusCode: {
            401: function (e) {},
            404: function (e) {},
            500: function (e) {}
        }
    };
    $.ajaxSetup(ajaxSetup);

    /**
     * Application static class
     */
    Application = {
        /**
         * @param string version
         */
        version: "3.0.0",
        /**         * @param string dataType
         */
        dataType: "json",
        /**
         * @param object storage
         */
        storage: {
            data: {},
            setItem: function (key, value) {
                this.data[key] = value;
                return $.ajax({
                    url: "/admin/users/prefs",
                    type: "POST",
                    dataType: "json",
                    data: {prefs: kendo.stringify(Application.storage.data)},
                    success: function (data) {
                        Application.storage.data = data;
                    },
                    error: function (xhr, status, msg) {
                        Application.Warning(xhr.responseText, msg);
                    }
                });
            },
            getItem: function (key) {
                return this.data[key];
            },
            clear: function () {
                return $.ajax({
                    url: "/admin/users/prefs",
                    type: "POST",
                    dataType: "json",
                    data: {prefs: "{}"},
                    success: function () {
                        Application.storage.data = {};
                    },
                    error: function (xhr, status, msg) {
                        Application.Warning(xhr.responseText, msg);
                    }
                });
            },
            load: function () {
                return $.ajax({
                    url: "/admin/users/prefs",
                    dataType: "json",
                    success: function (data) {
                        Application.storage.data = data;
                    },
                    error: function (xhr, status, msg) {
                        Application.Warning(xhr.responseText, msg);
                    }
                });
            }
        },
        /**
         * @param object
         */
        default: {
            grid: {
                pageable: {
                    refresh: true
                },
                scrollable: false,
                filterable: {
                    extra: false, //do not show extra filters
                    operators: {// redefine the string operators
                        string: {
                            contains: kendo.ui.FilterCell.prototype.options.operators.string.contains,
                            startswith: kendo.ui.FilterCell.prototype.options.operators.string.startswith,
                            eq: kendo.ui.FilterCell.prototype.options.operators.string.eq
                        }
                    }
                },
                columnMenu: true,
                sortable: true,
                selectable: "row",
                navigatable: true,
                groupable: false,
                resizable: false
            },
            chooser: {
                pageable: {
                    refresh: true,
                    pageSizes: false,
                    buttonCount: 5
                },
                scrollable: false,
                navigatable: true,
                selectable: true,
                filterable: {
                    mode: "row",
                    extra: false, //do not show extra filters
                    operators: {// redefine the string operators
                        string: {
                            contains: kendo.ui.FilterCell.prototype.options.operators.string.contains,
                            startswith: kendo.ui.FilterCell.prototype.options.operators.string.startswith,
                            eq: kendo.ui.FilterCell.prototype.options.operators.string.eq
                        }
                    }
                },
                columnMenu: false,
                sortable: true,
                groupable: false,
                resizable: true
            }
        },
        /**
         *
         * @param string name
         * @param string path
         * @returns object Application.Controller
         */
        Controller: function (/*string*/name, /*string*/path) {
            this.name = name;
            this.callback = null;
            this.path = path || name;
            this.dataSource = null;
            this.grid = {};
            this.window = {};
            this.stackOnOpen = true;
            this.acl = {
                create: true,
                read: true,
                update: true,
                destroy: true,
                view: true
            };

            var self = this;

            this.detailInit = function (e) {

            };

            this._view = function (item, container) {
                $("input[type=file]", container).attr("disabled", true);
                $(".chooser-buttons", container).hide();
                $("[name]", container).each(function () {
                    var kntb = $(this).getKendoNumericTextBox();
                    if (kntb) {
                        kntb.enable(false);
                        return;
                    }
                    var kddl = $(this).getKendoDropDownList();
                    if (kddl) {
                        kddl.enable(false);
                        return;
                    }
                    if ($(this).attr("type") === 'checkbox') {
                        $(this).click(function () {
                            this.checked = !this.checked;
                        });
                        return;
                    }
                    if (self.dataSource.options.schema.model.fields[this.name]) {
                        var type = self.dataSource.options.schema.model.fields[this.name].type;
                        if (type && type === 'date') {
                            var dp = $(this).getKendoDatePicker();
                            var dtp = $(this).getKendoDateTimePicker();
                            if (dp || dtp) {
                                if (dp)
                                    dp.enable(false);
                                if (dtp)
                                    dtp.enable(false);
                                return;
                            }
                            $(this).val(kendo.toString(item[this.name], 'yyyy-MM-dd HH:mm'));
                            $(this).attr("disabled", true);
                            return;
                        }

                        var chooser = self.dataSource.options.schema.model.fields[this.name].chooser;
                        if (chooser) {
                            var path = chooser.path;
                            var field = chooser.field;
                            var fieldId = chooser.fieldId;
                            var display = chooser.display;
                            var separator = chooser.separator;
                            if (path && item[field] > 0) {
                                var action = 'get';
                                var params = {id: item[field]};
                                if (fieldId) {
                                    action = 'read';
                                    params = {
                                        filter: {
                                            logic: "and",
                                            filters: []
                                        },
                                        pageSize: 1,
                                        page: 1
                                    };
                                    var k = keysOf(fieldId);
                                    for (var i = 0; i < k.length; i++) {
                                        params.filter.filters.push({
                                            field: fieldId[k[i]],
                                            operator: "eq",
                                            value: item[k[i]]
                                        });
                                    }
                                }

                                $.ajax({
                                    url: Application.service.url + '/' + path + '/' + action,
                                    data: params,
                                    dataType: Application.dataType,
                                    success: function (data) {
                                        if (fieldId && data.data && data.data.length > 0) {
                                            var data = data.data[0];
                                        }
                                        var text = [];
                                        for (var i = 0; i < display.length; i++) {
                                            text.push(data[display[i]]);
                                        }
                                        $("." + field + "_display", container).val(text.join(separator));
                                    }
                                });
                            }
                        }
                    }
                    $(this).attr("readonly", true);
                });

                if (self.onView && $.isFunction(self.onView)) {
                    self.onView.call(self, container, item);
                }

            };

            this.getGridColumn = function (grid, column) {
                for (var i = 0; i < grid.columns.length; i++) {
                    if (grid.columns[i].field && grid.columns[i].field === column)
                        return i;
                }
                alert("Column " + column + " not found in grid");
            };

            this.setGridColumnFilters = function (grid, column, options) {
                var type;
                var i = this.getGridColumn(grid, column);
                var col = this.dataSource.options.schema.model.fields[column];
                if (col)
                    type = col.type;

                switch (type) {

                    case 'date':
                        var ui = grid.columns[i].filterable.ui;
                        grid.columns[i].filterable.ui = function (element) {
                            if (ui === "datepicker")
                                element.kendoDatePicker({
                                    format: options.format
                                });
                            if (ui === "datetimepicker")
                                element.kendoDateTimePicker({
                                    format: options.format
                                });
                        };
                        grid.columns[i].filterable.cell = {
                            template: function (args) {
                                if (ui === "datepicker")
                                    args.element.kendoDatePicker({
                                        format: options.format
                                    });
                                if (ui === "datetimepicker")
                                    args.element.kendoDateTimePicker({
                                        format: options.format
                                    });
                            }
                        };
                        break;

                    default:
                        if (!grid.columns[i].filterable)
                            grid.columns[i].filterable = {};
                        grid.columns[i].filterable.extra = false;
                        grid.columns[i].filterable.operators = {
                            string: {
                                eq: kendo.ui.FilterCell.prototype.options.operators.enums.eq,
                                neq: kendo.ui.FilterCell.prototype.options.operators.enums.neq
                            }
                        };
                        grid.columns[i].filterable.ui = function (element) {
                            var cb = function (element, ds, options) {
                                ds.filter({});
                                ds.fetch(function () {
                                    element.kendoDropDownList({
                                        dataTextField: options.dataTextField,
                                        dataValueField: options.dataValueField,
                                        dataSource: ds.data(),
                                        optionLabel: kendo.ui.FilterMenu.prototype.options.messages.selectValue,
                                        filter: "contains"
                                    });
                                });
                                element.css({
                                    minWidth: "300px"
                                });
                            };
                            if (!$.isArray(options.data))
                                Application.getController(options.data, function () {
                                    var ds = new kendo.data.DataSource($.extend(true, {}, this.dataSource.options));
                                    cb(element, ds, options);
                                });
                            else {
                                var ds = new kendo.data.DataSource({data: options.data});
                                cb(element, ds, options);
                            }
                        };

                        break;
                }
            };

            this._edit = function (id) {
                var editableWindow;
                var self = this;

                var cf = $.extend({}, (this.grid.dataSource.filter() || {}));
                if (self.grid.options.editor)
                    editableWindow = self.grid.options.editor.editable.window;
                if (self.grid.options.editable)
                    editableWindow = self.grid.options.editable.window;

                var parentClose = editableWindow.close;
                if (this.editableWindow) {
                    this.editableWindow.unbind("close");
                    this.editableWindow.wrapper.find(".k-grid-cancel").trigger("click");
                }
                this.grid.dataSource.unbind("requestEnd");
                this.grid.dataSource.filter({
                    field: this.grid.dataSource.options.schema.model.id || "id",
                    operator: "eq",
                    value: id
                });
                this.grid.unbind("dataBound");
                this.grid.bind("dataBound", function () {
                    self.grid.dataSource.fetch(function () {
                        var firstRow = $("#grid-" + self.name + " .k-grid-content tr:eq(0)");
                        if (firstRow.length > 0) {
                            editableWindow.close = function () {
                                self.grid.dataSource.filter(cf);
                                if (parentClose)
                                    editableWindow.close = parentClose;
                            };
                            var editButton = firstRow.find("a.k-grid-edit");
                            editButton.trigger("click");
                        }
                        self.grid.dataSource.bind("requestEnd", self.grid.dataSource.options.afterRequestEnd);
                    });
                    self.grid.unbind("dataBound");
                });
            };

            this.edit = function (id) {
                if (this.window.element && !this.window.element.is(":visible")) {
                    var self = this;
                    var parentActivate = this.window.options.activate;
                    self.window.unbind("activate");
                    this.window.bind("activate", function () {
                        self.window.unbind("activate");
                        if ($.isFunction(parentActivate))
                            self.window.bind("activate", parentActivate);
                        self._edit.call(self, id);
                    });
                    this.window.open();
                    return;
                }
                this._edit(id);
            };

            this.viewer = {
                view: function (item) {
                    var keys = item.fields ? keysOf(item.fields) : keysOf(item);
                    var obj = {};
                    if (item.idField)
                        obj[item.idField] = item.id;
                    for (var i = 0; i < keys.length; i++) {
                        obj[keys[i]] = item[keys[i]];
                    }
                    var dump = Application.ui.dump(obj);
                    var container = $("<div/>").css("padding", "10px").addClass("k-block").addClass("k-pane");
                    var win = $("<div/>").css({
                        minWidth: 350
                    }).html(container.html(dump)).kendoWindow({
                        title: (this.name || '') + " VIEW ID: " + item.id,
                        close: function () {
                            this.destroy();
                        }
                    }).data("kendoWindow");
                    win.center().open();
                }
            };

            this.view = function (item) {
                if (typeof item === "object") {
                    if (!this.windowDetails) {
                        this.viewer.view(item);
                        return;
                    }
                    var content = this.detailsTemplate(item);
                    var title = this.windowDetails.options.title;
                    this.windowDetails.setOptions({
                        title: title.replace(/VIEW.*/g, "VIEW ID:" + item.id)
                    });
                    this.windowDetails.content(content);
                    kendo.bind(this.windowDetails.element, item);
                    this._view(item, this.windowDetails.element);
                    this.windowDetails.center().open();
                    return;
                }
                var id = item;
                var sharedDataSource = new kendo.data.DataSource($.extend(true, {}, this.dataSourceOptions));
                var self = this;
                sharedDataSource.filter({
                    field: sharedDataSource.options.schema.model.id,
                    operator: "eq",
                    value: id
                });
                sharedDataSource.fetch(function () {
                    var item = sharedDataSource.view();
                    if (!self.windowDetails) {
                        self.viewer.view.call(self, item[0]);
                        return;
                    }
                    var content = self.detailsTemplate(item);
                    var title = self.windowDetails.options.title;
                    self.windowDetails.setOptions({
                        title: title.replace(/VIEW.*/g, "VIEW ID:" + item.id)
                    });
                    self.windowDetails.content(content);
                    kendo.bind(self.windowDetails.element, item[0]);
                    self._view(item[0], self.windowDetails.element);
                    self.windowDetails.center().open();
                });
            };

            this.addButtonDirectView = function (controller, fieldId) {
                var columns = this.columns.slice();
                if (columns && columns[columns.length - 1].command) {
                    if (columns[columns.length - 1].command[0].name !== "view") {
                        columns[columns.length - 1].command.unshift({
                            name: "view",
                            text: "",
                            title: "Details",
                            iconClass: "k-icon k-i-zoom",
                            click: function (e) {
                                var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                                Application.controllers[controller].view(dataItem[fieldId]);
                            }
                        });
                    }
                }
            };

            this.getAllData = function () {
                var data = [];
                if (this.dataSource && this.dataSource.data) {
                    data = this.dataSource.data();
                }
                var ds = new kendo.data.DataSource({data: data});
                ds.sort(this.dataSource.options.sort);
                return ds;
            };

            this.loaded = false;
            /**
             * save dataSource
             * @returns void
             */
            this.save = function () {
                if (this.dataSource)
                    this.dataSource.sync();
            };

            /**
             * options can be
             * [
             *      title           -> window title
             *      fields,         -> model fields
             *      columns,        -> grid columns
             *      id,             -> primary key
             *      onEdit,         -> edit callback
             *      onView,         -> view callback
             *      acl,            -> access control list
             *      transport,      -> dataSource transport
             *      schema,         -> dataSource schema
             *      window,         -> window config
             *      grid,           -> grid config
             *      windowDetails   -> window details config,
             *      destroyOnClose  -> destroy controller on window close,
             *      widget          -> type of kendo widget (default kendoGrid)
             *      dataSourceType  -> type of kendo dataSource (default DataSource)
             *      dataSourceOptions  -> dataSource config
             * }
             * @param {type} options
             * @returns {undefined}
             */
            this.createUI = function (options) {
                var $ctrl = this;
                var dataSourceType = options.dataSourceType || "DataSource";
                var widget = options.widget || "kendoGrid";

                if (options.onEdit)
                    this.onEdit = options.onEdit;
                if (options.onView)
                    this.onView = options.onView;

                this.columns = options.columns;

                this.acl = this.acl || {
                    view: true,
                    create: true,
                    read: true,
                    update: true,
                    destroy: true
                };
                $.extend(true, this.acl, options.acl);
                
                if (this.reopenOnSave) {
                    this.recordCreated = function (e) {
                        if (!e.responseJSON || !e.responseJSON[0])
                            return;
                        var id = e.responseJSON[0].id;;
                        if ($ctrl.pageable) {
                            setTimeout(function () {
                                $ctrl.edit(id);
                            }, 1000);
                        } else {
                            var requestEnd = $ctrl.grid.dataSource.options.requestEnd;
                            $ctrl.grid.dataSource.bind("requestEnd", function (e) {
                                if (e.type === "read") {
                                    $ctrl.grid.one("dataBound", function () {
                                        var model = $ctrl.grid.dataSource.get(id);
                                        var row = $ctrl.grid.element.find("tr[data-uid='" + model.uid + "']");
                                        $ctrl.grid.dataSource.unbind("requestEnd");
                                        setTimeout(function () {
                                            $ctrl.grid.editRow(row);
                                            $ctrl.grid.dataSource.bind("requestEnd", requestEnd);
                                        }, 1000);
                                    });
                                }
                            });
                        }
                    };
                }

                this.pageSize = (options.pageSize || 50);
                this.transport = Application.ui.getDefaultTransport(this);
                this.transport.parameterMap = function (options, type) {
                    var dateFormat = "yyyy-MM-dd HH:mm:ss";
                    if (type !== "read") {
                        var models = options.models || [options];
                        for (var i = 0; i < models.length; i++) {
                            for (var field in models[i]) {
                                var element;
                                if ($ctrl.grid &&
                                        $ctrl.grid.editable &&
                                        $ctrl.grid.editable.element &&
                                        (element = $ctrl.grid.editable.element.find("[name=" + field + "]"))
                                        ) {
                                    if (element.length === 1 && element.data("kendoDatePicker"))
                                        dateFormat = "yyyy-MM-dd";
                                }
                                if (models[i][field] instanceof Date) {
                                    models[i][field] = kendo.toString(models[i][field], dateFormat);
                                }
                            }
                        }
                        if (options.models)
                            return {models: kendo.stringify(options.models)};
                        if (options)
                            return {models: kendo.stringify([options])};
                    } else {
                        if ($ctrl.pageable) {
                            if ($ctrl.grid &&
                                    $ctrl.grid.dataSource &&
                                    $ctrl.grid.dataSource.options &&
                                    $ctrl.grid.dataSource.options.schema &&
                                    $ctrl.grid.dataSource.options.schema.model
                                    ) {
                                var fields = $ctrl.grid.dataSource.options.schema.model.fields;
                                if (options.filter) {
                                    (function (filter) {
                                        if (filter.logic) {
                                            arguments.callee.call(null, filter.filters);
                                            return;
                                        }
                                        for (var f in filter) {
                                            var name = filter[f].field;
                                            if (fields[name] && fields[name].type === "date" && filter[f].value instanceof Date) {
                                                filter[f].value = kendo.toString(kendo.parseDate(filter[f].value), dateFormat);
                                            }
                                        }
                                    })(options.filter);
                                }
                            }
                        }
                    }
                    return $ctrl.pageable ? {
                        data: options.data,
                        page: options.page,
                        filter: kendo.stringify(options.filter),
                        sort: kendo.stringify(options.sort),
                        pageSize: options.pageSize
                    } : options;
                };
                $.extend(true, this.transport, options.transport);

                this.model = {
                    id: options.id || "id",
                    fields: options.fields
                };
                this.schema = {
                    model: this.model,
                    errors: function (response) {
                        return response.error;
                    }
                };
                $.extend(true, this.schema, options.schema);

                if (this.pageable) {
                    $.extend(true, this.schema, {
                        data: "data",
                        total: "total"
                    });
                }

                this.dataSourceOptions = {
                    controller: this,
                    batch: true,
                    transport: this.transport,
                    schema: this.schema,
                    serverPaging: this.pageable,
                    serverSorting: this.pageable,
                    serverFiltering: this.pageable,
                    error: function (e) {
                        e.preventDefault();
                        if (e.xhr && e.xhr.responseText) {
                            var match = /^id=(\d+)$/.exec(e.xhr.responseText);
                            if (match) {
                                Application.controllers[$ctrl.name].edit(match[1]);
                                return;
                            }
                            if (e.xhr.responseText === "OK") {
                                Application.notification.show("Request performed correctly", "success");
                                return;
                            }
                            Application.notification.show("Error: " + e.errorThrown, "error");
                            this._destroyed = [];
                        }
                    }
                };
                this.dataSourceOptions.afterRequestEnd = function (e) {
                    e.preventDefault();
                    if (e.type) {
                        if (e.type === "update" && !e.response.Errors) {
                            app().notification.show("Record successfully updated", "success");
                        }
                        if (e.type === "create" && !e.response.Errors) {
                            app().notification.show("Record successfully created", "success");
                        }
                        if (e.type === "destroy" && !e.response.Errors) {
                            app().notification.show("Record successfully deleted", "success");
                        }
                        if (e.type !== "read") {
                            e.sender.read();
                        } else {
                            if ($ctrl.lastEdited) {
                                var self = this;
                                setTimeout(function(){
                                    var content = $ctrl.grid.element.find("div.k-grid-content");
                                    content.scrollTop($ctrl.scrollTop);
                                    content.scrollLeft($ctrl.scrollLeft);
                                    var model = self.get($ctrl.lastEdited);
                                    if (model) {
                                        $ctrl.grid.select($ctrl.grid.element.find("tr[data-uid=" + model.uid + "]") || 0);
                                    }
                                }, 1000);
                            }
                        }
                    }
                    if (e.response && e.response.Errors) {
                        Application.notification.show("An error occured", "error");
                    }
                };
                this.dataSourceOptions.requestEnd = this.dataSourceOptions.afterRequestEnd;
                if (this.pageable) {
                    $.extend(this.dataSourceOptions, {
                        pageSize: this.pageSize
                    });
                }
                $.extend(this.dataSourceOptions, options.dataSourceOptions);

                if ($("#window-" + this.name).length === 1) {
                    var window = {
                        actions: ["Maximize", "Minimize", "Close"],
                        height: "600px",
                        width: "800px",
                        title: options.title || this.name,
                        visible: false,
                        resize: Application.ui.state.windowResize,
                        dragend: Application.ui.state.windowMove,
                        close: Application.ui.state.windowClose,
                        open: Application.ui.state.windowOpen,
                        controllerName: this.name,
                        deactivate: function (e) {
                            if (options.destroyOnClose) {
                                this.destroy();
                                setTimeout(function () {
                                    delete Application.controllers[$ctrl.name];
                                }, 50);
                            } else {
                                var key = this.options.controllerName;
                                var ctrl = Application.controllers[key];
                                if (ctrl.initial) {
                                    ctrl.grid.dataSource.query($.extend(true, {}, ctrl.initial));
                                }
                            }
                        },
                        activate: function (e) {
                            var key = this.options.controllerName;
                            var ctrl = Application.controllers[key];
                            if ((ctrl.grid && ctrl.grid.dataSource) || typeof dataSource !== "undefined") {
                                if (!ctrl.initial) {
                                    var _sort = typeof dataSource !== "undefined" && dataSource.sort ? dataSource.sort : ctrl.grid.dataSource.sort();
                                    var _filter = typeof dataSource !== "undefined" && dataSource.filter ? dataSource.filter : ctrl.grid.dataSource.filter();
                                    var _page = typeof dataSource !== "undefined" && dataSource.page ? dataSource.page : ctrl.grid.dataSource.page();
                                    var _pageSize = typeof dataSource !== "undefined" && dataSource.pageSize ? dataSource.pageSize : ctrl.grid.dataSource.pageSize();

                                    if (dataSourceType === "DataSource") {
                                        var _aggregate = typeof dataSource !== "undefined" && dataSource.aggregate ? dataSource.aggregate : ctrl.grid.dataSource.aggregate();
                                        var _group = typeof dataSource !== "undefined" && dataSource.group ? dataSource.group : ctrl.grid.dataSource.group();
                                    }

                                    var sort = $.isArray(_sort) ? _sort.slice() : $.extend(true, {}, _sort);
                                    var filter = $.extend(true, {}, _filter);
                                    if (_group)
                                        var group = $.isArray(_group) ? _group.slice() : $.extend(true, {}, _group);
                                    if (_aggregate)
                                        var aggregate = $.extend(true, {}, _aggregate);

                                    ctrl.initial = {
                                        filter: filter,
                                        sort: sort,
                                        page: _page,
                                        pageSize: _pageSize
                                    };
                                    if (dataSourceType === "DataSource") {
                                        ctrl.initial.aggreagte = aggregate;
                                        ctrl.initial.group = group;
                                    }
                                }
                                ctrl.grid.dataSource.query($.extend(true, {}, ctrl.initial));
                            }
                            if (ctrl.grid)
                                resizeGrid(ctrl.grid.element);
                        }
                    };

                    $.extend(true, window, options.window);
                    this.window = $("#window-" + this.name).kendoWindow(window).data("kendoWindow");

                }

                var command = [];
                if (this.acl.view) {
                    command.push({
                        name: "view",
                        text: " ",
                        title: "Details",
                        width: "80px",
                        iconClass: "k-icon k-i-zoom",
                        click: function (e) {
                            $ctrl.showDetails(e);
                        }
                    });
                }
                if (this.acl.update) {
                    command.push({
                        text: " ",
                        name: "edit"
                    });
                }
                if (this.acl.destroy) {
                    command.push({
                        text: " ",
                        name: "destroy"
                    });
                }
                if (command.length > 0) {
                    var obj = {
                        command: command,
                        width: (command.length * 40) + "px",
                        title: " "
                    };
                    var commandPlaceMethod = "push";
                    if (options.commandPlace === "first") {
                        commandPlaceMethod = "unshift";
                        for (var c in this.columns) {
                            if (this.columns[c].locked) {
                                obj.locked = true;
                            }
                            if (this.columns[c].lockable === false) {
                                obj.lockable = false;
                            }
                        }
                    }
                    this.columns[commandPlaceMethod](obj);
                }
                var toolbar = [];
                if (this.acl.create)
                    toolbar.push("create");
                if (options.toolbar)
                    toolbar = toolbar.concat(options.toolbar);

                var grid = {
                    pageSize: this.pageSize,
                    autoBind: false,
                    dataSource: new kendo.data[dataSourceType](this.dataSourceOptions),
                    columnMenu: true,
                    columns: this.columns,
                    selectable: true,
                    sortable: {
                        mode: "multiple",
                        allowUnsort: true
                    },
                    navigatable: this.acl.update,
                    groupable: true,
                    resizable: true,
                    toolbar: toolbar,
                    filterable: {
                        mode: "menu,row"
                    },
                    columnHide: Application.ui.grid.columnHide,
                    columnShow: Application.ui.grid.columnShow
                };
                if (this.pageable) {
                    $.extend(grid, {
                        pageable: {
                            refresh: true,
                            pageSizes: [5, 50, 500, "all"]
                        }
                    });
                }

                var command = [];
                if (this.acl.update) {
                    grid.editable = {
                        mode: "popup",
                        confirmation: "Do you really want to delete this record ?",
                        template: kendo.template($("#editForm-popup-template-" + this.name).html()),
                        window: {
                            width: "800px",
                            maxHeight: "800px",
                            resizable: true
                        }
                    };
                    grid.edit = function (e) {
                        var title = (options.title || $ctrl.name) + " " + (!e.model.isNew() ? "<b>EDIT</b>" : "<b>NEW</b>");
                        if (!e.model.isNew())
                            title += " ID: " + e.model.id;
                        $(".k-window-title", e.container.parent()).html(title);
                        var container = e.container;
                        var model = e.model;
                        container.data("kendoValidator").bind("validate", function (e) {
                            if (!e.valid) {
                                var fieldName = Object.keys(e.sender._errors)[0];
                                var closest = container.find("[name=" + fieldName + "]").closest("div[role=tabpanel]");
                                var tabstrip = container.find("[data-role=tabstrip]");
                                tabstrip.find(">[role=tabpanel]").each(function (i) {
                                    if (closest.attr("id") !== this.id)
                                        return;
                                    tabstrip.data("kendoTabStrip").select(i);
                                });
                            }
                        });
                        Application.onEdit($ctrl.name, container, model);
                    };
                }
                $.extend(true, grid, options.grid);

                if (this.acl.view) {
                    var htmlDetails = $("#editForm-popup-template-" + $ctrl.name).clone().html();
                    this.detailsTemplate = kendo.template(htmlDetails);
                    if ($("#view-" + this.name).length === 0 && $("#window-" + this.name).length === 1)
                        $("#window-" + this.name).append("<div id='view-" + this.name + "'/>");

                    var windowDetails = {
                        title: (options.title || this.name) + " VIEW",
                        modal: true,
                        visible: false,
                        resizable: true,
                        width: 800
                    };

                    $.extend(true, windowDetails, options.windowDetails);

                    this.windowDetails = $("#view-" + this.name).kendoWindow(windowDetails).data("kendoWindow");

                    this.showDetails = function (e) {
                        e.preventDefault();
                        var row = $(e.currentTarget).closest("tr");
                        var grid = Object.keys($ctrl.grid).length > 0 ? $ctrl.grid : $(e.currentTarget).closest("[data-role=grid]").data("kendoGrid");
                        var dataItem = grid.dataItem(row);
                        $ctrl.view(dataItem);
                    };
                }
                if ($("#window-" + this.name).length === 1) {
                    var node = this.window.element.find("#grid-" + this.name);
                    var _grid = node[widget](grid);
                    this.grid = _grid.data(widget);
                    this.dataSource = this.grid.dataSource;
                } else {
                    this.dataSource = new kendo.data[dataSourceType](this.dataSourceOptions);
                }
            };

            /**
             * Initialize controller by getting the ui and appending it to the DOM
             * @param function callback
             * @param bool waitDS
             * @returns void
             */
            this.init = function (/*function*/callback, /*bool*/waitDS) {
                var self = this;
                $(this).on("loaded", function (e) {
                    if (self.grid && self.grid.table) {
                        self.grid.bind("edit", function (e) {
                            var uid = e.model.uid;
                            var row = self.grid.table.find("tr[data-uid='" + uid + "']");
                            if (self.grid.options.selectable) {
                                self.grid.select(row);
                            }
                        });
                        self.grid.table.bind("keydown", function (e) {
                            var evts = {
                                DESTROY: kendo.keys.DELETE, // canc
                                ARROWUP: kendo.keys.UP,
                                ARROWDOWN: kendo.keys.DOWN
                            };
                            var code = e.keyCode || e.which;
                            var selected = self.grid.select();
                            if (code) {
                                switch (code) {
                                    case evts.DESTROY:
                                        if (self.acl.destroy)
                                            selected.find("a.k-grid-delete").trigger("click");
                                        break;
                                    case evts.ARROWUP:
                                        if (selected.prev().length > 0) {
                                            var prev = selected.prev();
                                            self.grid.select(prev[0]);
                                        }
                                        break;
                                    case evts.ARROWDOWN:
                                        if (selected.next().length > 0) {
                                            var next = selected.next();
                                            self.grid.select(next[0]);
                                        }
                                        break;
                                }
                            }
                            e.stopPropagation();
                        });
                    }
                    if (self.window && self.window.options && self.window.options.controllerName) {
                        if ($("#context-menu-" + self.window.options.controllerName).length === 0) {
                            var menu = $("<ul />");
                            menu.attr("id", "context-menu-" + self.window.options.controllerName);
                            menu.kendoContextMenu({
                                orientation: "vertical",
                                target: self.window.wrapper,
                                filter: ".k-window-titlebar",
                                animation: {
                                    open: {effects: "fadeIn"},
                                    duration: 500
                                },
                                select: function (e) {
                                    var wnd = $(e.sender.target.context).data("kendoWindow");
                                    if ($(e.item).hasClass("cm-close")) {
                                        wnd.close();
                                    }
                                    if ($(e.item).hasClass("cm-create-shortcut")) {
                                        Application.ui.createShortcut(wnd);
                                    }
                                    if ($(e.item).hasClass("cm-remove-shortcut")) {
                                        if (confirm("Remove this link from desktop ?"))
                                            Application.ui.removeShortcut(wnd);
                                    }
                                }
                            });
                            var cmConfig = [];
                            cmConfig.push({text: "Close", cssClass: "cm-close"});
                            cmConfig.push({text: "Create shortcut", cssClass: "cm-create-shortcut"});
                            cmConfig.push({text: "Remove shortcut", cssClass: "cm-remove-shortcut"});
                            menu.data("kendoContextMenu").append(cmConfig);
                        }
                    }
                });
                $.ajax({
                    url: Application.service.url + "/" + this.path,
                    dataType: "html",
                    data: {controllerName: this.name},
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(xhr, textStatus + " " + errorThrown);
                        kendo.ui.progress($("body"), false);
                    },
                    success: function (data) {
                        if (!data) {
                            var msg = "WARNING<br>Can't load resource " + self.name + "<br>Check the Access Control List (ACL) Permissions for your role";
                            Application.Warning(msg, "WARNING");
                            throw msg;
                            return;
                        }
                        $("#placeholder").append(data);
                        if (self.run && $.isFunction(self.run)) {
                            try {
                                self.run();
                            } catch (err) {
                                console.log(err);
                                Application.Warning(err.message, "Error");
                            }
                        }
                        if (self.dataSource && waitDS && !self.loaded) {
                            self.dataSource.bind("requestEnd", function () {
                                kendo.ui.progress($("body"), false);
                                self.dataSource.unbind("requestEnd");
                                if (self.dataSource.options.afterRequestEnd)
                                    self.dataSource.bind("requestEnd", self.dataSource.options.afterRequestEnd);
                                if (callback && $.isFunction(callback))
                                    callback.call(self);
                                $(self).trigger("loaded");
                                self.loaded = true;
                            });
                            self.dataSource.read();
                            return;
                        }
                        if (callback && $.isFunction(callback))
                            callback.call(self);
                        $(self).trigger("loaded");
                        self.loaded = true;
                        kendo.ui.progress($("body"), false);
                    }
                });
            };

            this.getSelected = function () {
                var selected;
                /* GRID */
                if (this.grid && this.grid.editable)
                    selected = this.grid.editable.options.model;
                /* TREELIST */
                else if (this.grid && this.grid.editor && this.grid.editor.editable)
                    selected = this.grid.editor.editable.options.model;
                else
                    throw Error("Unsupported editable mode");
                return selected;
            };

            this.setRequiredElements = function (container) {
                var fields = this.dataSource.options.schema.model.fields;
                var names = keysOf(fields);
                for (var i = 0; i < names.length; i++) {
                    var name = names[i];
                    if (fields[name].validation && fields[name].validation.required) {
                        container.find("[name='" + name + "']").attr("required", "required");
                    }
                }
            };

            /**
             *
             * @param object options
             * @returns {undefined}
             *
             * @description var options = {
             *  controller: 'article-document', controller n-m
             *  path: 'service/article-document', path n-m
             *  textField: 'article_id', key between controller n-m and self controller
             *  valueField: article_id, value of foreign_key_name, for filtering
             *  field: current field name
             *  container: container
             * };
             *
             */
            this.createDropDownList = function (/*object*/ options) {
                var self = this;
                var model = options.model;
                if (options.data) {
                    var conf = {
                        dataTextField: options.textField,
                        dataValueField: options.valueField,
                        dataSource: {
                            data: options.data
                        }
                    };
                    if (options.template) {
                        conf.valueTemplate = options.template;
                    }
                    if (options.change && $.isFunction(options.change))
                        conf.change = options.change;
                    var nullable = self.dataSource.options.schema.model.fields[options.field].validation && self.dataSource.options.schema.model.fields[options.field].validation.nullable;
                    if (nullable) {
                        conf.valuePrimitive = true;
                        conf.optionLabel = "...";
                    }
                    var element = $("[name=" + options.field + "]", options.container || self.window.element);
                    var dropdown = element.kendoDropDownList(conf).data("kendoDropDownList");
                    if (model && $.isFunction(model.isNew) && model.isNew() && !nullable) {
                        dropdown.select(0);
                        if ($.isFunction(model.set)) {
                            model.set(options.field, dropdown.value());
                        } else {
                            model[options.field] = dropdown.value();
                        }
                    }
                    element.trigger("ready");
                    return;
                }
                var controller = {
                    name: options.controller,
                    path: options.path || options.controller
                };
                delete options.model;
                Application.getController(controller, function () {
                    if (!this.dataSourceOptions) {
                        console.log(this);
                        throw this.name + " has no dataSource";
                    }
                    var dsConf = $.extend(true, {}, this.dataSourceOptions);
                    if (options.filter) {
                        dsConf.filter = options.filter;
                        if ($.isFunction(options.filter))
                            dsConf.filter = options.filter.call(this);
                    }
                    if (options.sort) {
                        dsConf.sort = options.sort;
                        if ($.isFunction(options.sort))
                            dsConf.sort = options.sort.call(this);
                    }
                    var ds = new kendo.data.DataSource(dsConf);
                    ds.fetch(function () {
                        var conf = {
                            dataTextField: options.textField,
                            dataValueField: options.valueField,
                            dataSource: this.view()
                        };
                        if (options.template) {
                            conf.valueTemplate = options.template;
                        }
                        if (options.change && $.isFunction(options.change))
                            conf.change = options.change;
                        var nullable = self.dataSource.options.schema.model.fields[options.field].validation && self.dataSource.options.schema.model.fields[options.field].validation.nullable;
                        if (nullable) {
                            conf.valuePrimitive = true;
                            conf.optionLabel = "...";
                        }
                        var element = $("[name=" + options.field + "]", options.container || self.window.element);
                        var dropdown = element.kendoDropDownList(conf).data("kendoDropDownList");
                        if (model && $.isFunction(model.isNew) && model.isNew() && !nullable) {
                            dropdown.select(0);
                            if ($.isFunction(model.set)) {
                                model.set(options.field, dropdown.value());
                            } else {
                                model[options.field] = dropdown.value();
                            }
                        }
                        element.trigger("ready");
                    });
                }, true);
            };
            /**
             *
             * @param object options
             * @returns {object}
             */
            this.createGridConfig = function (/*object*/ options) {
                var template = $("#editForm-popup-template-" + this.name).length > 0 ? $("#editForm-popup-template-" + this.name) : null;
                var defaults = {
                    autoBind: false,
                    dataSource: options.dataSource || this.dataSource,
                    toolbar: this.acl.create ? options.toolbar : false,
                    columns: options.columns || this.columns,
                    editable: {
                        mode: "popup",
                        confirmation: "Do you really want to delete this record ?",
                        window: {
                            width: "800px",
                            resizable: true
                        }
                    },
                    dataBound: function (e) {
                        $(".k-button-icontext", e.sender.element).removeClass("k-button-icontext");
                    }
                };
                if (template) {
                    defaults.editable.template = kendo.template(template.html());
                }
                var config = {};
                config = $.extend(true, config, Application.default.grid);
                config = $.extend(true, config, defaults);
                config = $.extend(true, config, options.grid);
                return config;
            };

            /**
             *
             * @param object options
             * @returns {undefined}
             */
            this.createGrid = function (/*object*/ options) {
                var config = this.createGridConfig(options);
                var selector = "#" + (options.id || "grid-" + this.name);
                var grid = $(selector, options.container).kendoGrid(config).data("kendoGrid");
                if (!grid)
                    throw Error("Grid " + selector + " not found in DOM");
                grid.dataSource.bind("requestEnd", function (e) {
                    if (e.type && e.type !== "read" && !e.response.Errors) {
                        grid.dataSource.read();
                    }
                });
                return grid;
            };
        }
        ,
        onEdit: function (controllerName, container, model) {
            Application.createWidgets(controllerName, container, model);
            var ctrl = Application.controllers[controllerName];
            if (ctrl.grid && ctrl.grid.element) {
                var content = ctrl.grid.element.find("div.k-grid-content");
                if (content.length > 0) {
                    ctrl.scrollTop = content[0].scrollTop;
                    ctrl.scrollLeft = content[0].scrollLeft;
                    ctrl.lastEdited = model.id;
                }
            }
            var onEdit = ctrl.onEdit;
            if ($.isFunction(onEdit)) {
                onEdit.call(ctrl, container, model);
            }
            var validator = container.data("kendoValidator");
            if (validator) {
                validator.bind("validate", function (e) {
                    if (!e.valid) {
                        setTimeout(function () {
                            validator.hideMessages();
                        }, 3000);
                    }
                });
            }
        }
        ,
        createGrids: function (options) {
            for (var i = 0; i < options.grids.length; i++) {
                var _grid = options.grids[i];
                Application.getController({name: _grid.controller.name, path: _grid.controller.path || _grid.controller.name}, function () {
                    this.dataSource.filter({field: _grid.field, operator: 'eq', value: options.model.id});
                    this.grid = this.createGrid({
                        container: options.container,
                        toolbar: options.toolbar,
                        columns: _grid.columns || this.columns,
                        grid: {
                            edit: function (ev) {
                                ev.model[_grid.field] = options.model.id;
                                var title = !ev.model.isNew() ? _grid.title.replace("%s", "EDIT") + ": " + ev.model[_grid.display] : _grid.title.replace("%s", "NEW");
                                $(".k-window-title", ev.container.parent()).html(title);
                                var container = ev.container;
                                var model = ev.model;
                                var toolbar = !model.isNew() ? ["create"] : false;
                                Application.onEdit(_grid.controller.name, container, model);
                                if (_grid.grids) {
                                    Application.createGrids({
                                        model: model,
                                        container: container,
                                        toolbar: toolbar,
                                        grids: _grid.grids
                                    });
                                }
                            }
                        }
                    });
                }, false);
            }
        }
        ,
        createWidgets: function (controllerName, container, model) {
            var chooser;
            var ctrl = Application.controllers[controllerName];
            var fields = ctrl.dataSourceOptions.schema.model.fields;
            var fieldNames = keysOf(fields);
            for (var i = 0; i < fieldNames.length; i++) {
                var field = fieldNames[i];
                if (fields[field].chooser) {
                    var options = fields[field].chooser;
                    options.controllerName = controllerName;
                    options.field = field;
                    if (!options.path)
                        options.path = options.controller;
                    if (options.multiple && model.isNew()) {
                        chooser = new Application.MultipleChooser(options, container, model);
                    } else {
                        chooser = new Application.Chooser(options, container, model);
                    }
                    chooser.render();
                }
                if (fields[field].select) {
                    var options = fields[field].select;
                    options.container = container;
                    options.field = field;
                    options.model = model;
                    ctrl.createDropDownList(options);
                }
            }

        }
        ,
        MultipleChooser: function (options, container, model) {
            this.options = options;
            var self = this;
            this.render = function () {
                var columns;
                $(".k-grid-update", container).remove();
                if (self.options.columns)
                    columns = self.options.columns.slice();
                else
                    columns = Application.controllers[self.options.controllerName].columns.slice(0, -1);
                columns.push({
                    command: [{
                            text: "",
                            name: "Info",
                            iconClass: "k-icon k-i-zoom",
                            width: 40,
                            click: function (e) {
                                var model = this.dataItem($(e.currentTarget).closest("tr"));
                                Application.getController({name: self.options.controller, path: self.options.path}, function () {
                                    this.view(model);
                                });
                            }
                        }
                    ],
                    title: "| ACTIONS |",
                    width: "100px"
                });
                var t = '<button class="k-button addSelected k-button-icontext k-primary k-grid-update">Add selected</button> selected <span class="selected">0</span> items, CTRL+CLICK for multi-selection';
                var conf = {
                    pageable: {
                        refresh: true,
                        pageSizes: Application.controllers[self.options.controllerName].grid.options.pageable.pageSizes,
                        buttonCount: 5
                    },
                    navigatable: true,
                    selectable: "row,multiple",
                    filterable: {
                        mode: "row",
                        extra: false, //do not show extra filters
                        operators: {// redefine the string operators
                            string: {
                                contains: kendo.ui.FilterCell.prototype.options.operators.string.contains,
                                startswith: kendo.ui.FilterCell.prototype.options.operators.string.startswith,
                                eq: kendo.ui.FilterCell.prototype.options.operators.string.eq
                            }
                        }
                    },
                    columnMenu: true,
                    sortable: true,
                    groupable: false,
                    columns: columns,
                    toolbar: kendo.template(t),
                    change: function (e) {
                        var selected = this.select();
                        for (var i = 0; i < selected.length; i++) {
                            var data = this.dataItem(selected[i]).toJSON();
                            if (!this.selected[data.id]) {
                                this.selected[data.id] = $.extend(true, {}, data);
                            }
                        }
                        var self = this;
                        $("tr", this.tbody).each(function () {
                            var data = self.dataItem(this);
                            if (self.selected[data.id] && !$(this).hasClass("k-state-selected")) {
                                delete self.selected[data.id];
                            }
                        });
                        $(".selected", this.element).text(keysOf(this.selected).length);
                    },
                    dataBound: function (e) {
                        var self = this;
                        $("tr", this.tbody).each(function () {
                            var data = self.dataItem(this);
                            if (self.selected[data.id]) {
                                $(this).attr('aria-selected', true);
                                $(this).addClass('k-state-selected');
                            }
                        });
                    }
                };
                Application.controllers[self.options.controllerName][self.options.field + '_chooser'] = $("." + self.options.field + "_chooser>div", container).kendoGrid(conf).data("kendoGrid");
                var chooserGrid = Application.controllers[self.options.controllerName][self.options.field + '_chooser'];
                if (!chooserGrid) {
                    console.log($("." + self.options.field + "_chooser>div", container), conf);
                    throw self.options.controllerName + " -> " + self.options.field + " unable to create chooser ";
                }
                if (self.options.float) {
                    $("." + self.options.field + "_chooser").addClass("k-content").css({
                        position: "absolute",
                        zIndex: container.closest('#window-' + self.options.controllerName).css('z-index') + 1,
                        boxShadow: "3px 3px 3px #666"
                    });
                }
                chooserGrid.selected = {};
                $(".addSelected", chooserGrid.element).click(function () {
                    var keys = keysOf(chooserGrid.selected);
                    Application.controllers[self.options.controllerName].dataSource.cancelChanges();
                    for (var i = 0; i < keys.length; i++) {
                        var m = $.extend(true, {}, model);
                        m[self.options.field] = chooserGrid.selected[keys[i]].id;
                        Application.controllers[self.options.controllerName].dataSource.add(m);
                    }
                    Application.controllers[self.options.controllerName].dataSource.sync();
                });
                chooserGrid.table.bind("keydown", function (e) {
                    var evts = {
                        SELECT: kendo.keys.ENTER, // invio
                        ARROWUP: kendo.keys.UP,
                        ARROWDOWN: kendo.keys.DOWN
                    };
                    var code = e.keyCode || e.which;
                    var selected = chooserGrid.select();
                    if (code) {
                        switch (code) {
                            case evts.SELECT:
                                $(".addSelected", chooserGrid.element).trigger("click");
                                break;
                            case evts.ARROWUP:
                                if (!selected)
                                    return;
                                if (selected.eq(0).prev().length > 0) {
                                    var prev = selected.eq(0).prev();
                                    chooserGrid.select(prev[0]);
                                }
                                break;
                            case evts.ARROWDOWN:
                                if (!selected)
                                    return;
                                if (selected.eq(0).next().length > 0) {
                                    var next = selected.eq(0).next();
                                    chooserGrid.select(next[0]);
                                }
                                break;
                        }
                    }
                    e.stopPropagation();
                });
                $("." + self.options.field + "_chooser", container).show();
                $("." + self.options.field + "_chooser_toggler", container).hide();
                $("." + self.options.field + "_chooser_view_button", container).hide();
                $("." + self.options.field + "_display", container).hide();
                Application.getController({name: self.options.controller, path: self.options.path}, function () {
                    if (self.options.gridColumnsFilters) {
                        var cfKeys = keysOf(self.options.gridColumnsFilters);
                        for (var i = 0; i < cfKeys.length; i++) {
                            var col = cfKeys[i];
                            this.setGridColumnFilters(conf, col, self.options.gridColumnsFilters[col]);
                        }
                        chooserGrid.setOptions({columns: conf.columns});
                    }
                    var opts = $.extend(true, {}, this.dataSourceOptions);
                    opts.pageSize = self.options.pageSize || 15;
                    if (self.options.filter) {
                        if ($.isFunction(self.options.filter))
                            opts.filter = self.options.filter.call(self);
                        else
                            opts.filter = self.options.filter;
                    }
                    if (chooserGrid.chooserFilters) {
                        if ($.isFunction(chooserGrid.chooserFilters))
                            opts.filter = chooserGrid.chooserFilters.call(self);
                        else
                            opts.filter = chooserGrid.chooserFilters;
                    }
                    var ds = new kendo.data.DataSource(opts);
                    chooserGrid.setDataSource(ds);
                }, true);
            };
        }
        ,
        Chooser: function (settings, container, model) {
            var defaults = {
                separator: " ",
                float: false
            };
            this.options = $.extend(defaults, settings);
            var self = this;
            this.reset = function () {
                if (self.grid.dataSource) {
                    self.grid.dataSource.filter({});
                    self.grid.dataSource.sort({});
                }
            };
            this.render = function () {
                var columns;
                if (self.options.columns) {
                    columns = self.options.columns.slice();
                } else {
                    columns = Application.controllers[self.options.controllerName].columns.slice(0, -1);
                }
                columns.push({
                    command: [{
                            text: "",
                            name: "Info",
                            iconClass: "k-icon k-i-zoom",
                            width: 40,
                            click: function (e) {
                                var model = this.dataItem($(e.currentTarget).closest("tr"));
                                Application.getController({name: self.options.controller, path: self.options.path}, function () {
                                    this.view(model);
                                });
                            }
                        }, {
                            text: "",
                            name: "Select",
                            width: 40,
                            iconClass: "k-icon k-i-plus",
                            click: self.options.onSelect || function (e) {
                                this.select($(e.target).parentsUntil("tr").parent());
                                var selected = model || Application.controllers[self.options.controllerName].getSelected();
                                var _model = this.dataItem(this.select());
                                var fieldName = self.options.field;
                                var field = $("[name=" + fieldName + "]", container);
                                //console.log("CHOOSER CLICK");
                                var validator = container.data("kendoValidator");
                                if (!_model)
                                    return false;
                                if (self.options.fieldId) {
                                    var k = keysOf(self.options.fieldId);
                                    for (var i = 0; i < k.length; i++) {
                                        selected.set(k[i], _model[self.options.fieldId[k[i]]]);
                                        selected[k[i]] = _model[self.options.fieldId[k[i]]];
                                    }
                                } else {
                                    selected.set(fieldName, _model.id);
                                    selected[fieldName] = _model.id;
                                }
                                if (field.length > 0) {
                                    field.val(_model.id);
                                    if (validator)
                                        validator.validate();
                                    field.trigger("chooserchanged", _model);
                                    field.data("choosed", _model);
                                }
                                var text = [];
                                if (self.options.display) {
                                    for (var i = 0; i < self.options.display.length; i++) {
                                        var fieldToDisplay = self.options.display[i];
                                        if ($.isPlainObject(fieldToDisplay)) {
                                            var callback = fieldToDisplay.callback;
                                            var field = fieldToDisplay.field;
                                            if (_model[field]) {
                                                text.push(callback(_model[field]));
                                            }
                                        } else {
                                            if (_model[fieldToDisplay])
                                                text.push(_model[fieldToDisplay]);
                                        }
                                    }
                                }
                                $("." + fieldName + "_display", container).val(text.join(self.options.separator));
                                $("." + fieldName + "_chooser_toggler", container).trigger("click");
                                return false;
                            }
                        }],
                    title: "| ACTIONS |",
                    width: "100px"
                });

                var validation = self.options.validation || Application.controllers[self.options.controllerName].dataSource.options.schema.model.fields[self.options.field].validation;
                var nullable = validation ? validation.nullable : true;
                var required = validation ? validation.required : false;
                //console.log(required);
                var conf = $.extend(true, {}, {
                    autoBind: true,
                    columns: columns
                }, Application.default.chooser);
                if (nullable) {
                    conf.toolbar = kendo.template('<button class="k-button" stype="button">None</button>');
                }
                Application.controllers[self.options.controllerName][self.options.field + '_chooser'] = $("." + self.options.field + "_chooser>div ", container).kendoGrid(conf).data("kendoGrid");
                var chooserGrid = Application.controllers[self.options.controllerName][self.options.field + '_chooser'];
                if (!chooserGrid) {
                    throw self.options.controllerName + " -> " + self.options.field + " unable to create chooser ";
                }
                self.grid = chooserGrid;
                if (self.options.float) {
                    $("." + self.options.field + "_chooser").addClass("k-content").css({
                        position: "absolute",
                        zIndex: container.closest('#window-' + self.options.controllerName).css('z-index') + 1,
                        boxShadow: "3px 3px 3px #666"
                    });
                }

                if (nullable) {
                    if (!self.options.onUnSelect) {
                        chooserGrid.element.find(".k-grid-toolbar button").click(function () {
                            var m = model || Application.controllers[self.options.controllerName].getSelected();
                            m.set(self.options.field, null);
                            var parent = $(this).closest('.k-window');
                            if ($('[name=' + self.options.field + ']', parent).length > 0) {
                                $('[name=' + self.options.field + ']', parent).trigger('chooserchanged', null);
                                $("[name=" + self.options.field + "]", container).data("choosed", null);
                            }
                            $('.' + self.options.field + '_display', parent).val('');
                            $('.' + self.options.field + '_chooser', parent).slideUp();
                            var html = $('<span/>');
                            html.addClass('k-icon').addClass('k-i-arrow-60-down');
                            $('.' + self.options.field + '_chooser_toggler', parent).html(html);
                        });
                    } else {
                        chooserGrid.element.find(".k-grid-toolbar button").click(self.options.onUnSelect);
                    }
                }
                chooserGrid.table.bind("keydown", function (e) {
                    var evts = {
                        SELECT: kendo.keys.ENTER, // invio
                        ARROWUP: kendo.keys.UP,
                        ARROWDOWN: kendo.keys.DOWN
                    };
                    var code = e.keyCode || e.which;
                    var selected = chooserGrid.select();
                    if (code) {
                        switch (code) {
                            case evts.SELECT:
                                selected.find("a.k-grid-Select").trigger("click");
                                $("." + self.options.field + "_chooser_toggler", container).trigger("click");
                                break;
                            case evts.ARROWUP:
                                if (selected.prev().length > 0) {
                                    var prev = selected.prev();
                                    chooserGrid.select(prev[0]);
                                }
                                break;
                            case evts.ARROWDOWN:
                                if (selected.next().length > 0) {
                                    var next = selected.next();
                                    chooserGrid.select(next[0]);
                                }
                                break;
                        }
                    }
                    e.stopPropagation();
                });

                $("input." + self.options.field + "_display", container).attr("readonly", true);
                if (required) {
                    //console.log(self.options.field);
                    $("[name=" + self.options.field + "]", container).attr("required", "required");
                }

                $("span." + self.options.field + "_chooser_view_button", container)
                        .addClass('k-icon')
                        .wrap("<button type='button' class='" + self.options.field + "_chooser_view_button_wrapper'></button>");

                $("button." + self.options.field + "_chooser_view_button_wrapper", container).css({
                    minWidth: 30,
                    minHeight: 20
                }).attr({
                    title: "Details"
                }).kendoButton({
                    text: " ",
                    icon: "zoom"
                }).data("kendoButton").bind("click", function (ev) {
                    var id = this.element.find("span").text();
                    if (id > 0) {
                        Application.getController({name: self.options.controller, path: self.options.path}, function () {
                            if (this.acl.read) {
                                var data = $("[name=" + self.options.field + "]", container).data("choosed");
                                var view = data || id;
                                this.view(view);
                            }
                        }, true);
                    }
                });

                if (self.options.edit) {
                    $("button." + self.options.field + "_chooser_view_button_wrapper", container).after("<button type='button' title='Edit' class='" + self.options.field + "_chooser_edit_button'><span class='k-icon k-i-edit'></span></button>");
                    var editButton = $("button." + self.options.field + "_chooser_edit_button", container).css({
                        minWidth: 30,
                        minHeight: 20
                    }).kendoButton({
                        text: ""
                    }).data("kendoButton");
                    editButton.bind("click", function (ev) {
                        var id = $("[name=" + self.options.field + "]", container).val();
                        if (id > 0) {
                            Application.getController({name: self.options.controller, path: self.options.path}, function () {
                                if (this.acl.update)
                                    this.edit(id);
                            }, true);
                        }
                    });
                    $("." + self.options.field + "-chooser-buttons", container).addClass("chooser-buttons-w-edit");
                }

                $("." + self.options.field + "_chooser_toggler", container).attr({title: "Choose"}).html("<span class='k-icon k-i-arrow-s'></span>").kendoButton().data("kendoButton").bind("click", function (ev) {
                    //console.log("CHOOSER CLICK", ev, this);
                    if ($("." + self.options.field + "_chooser", container).is(":visible")) {
                        $("." + self.options.field + "_chooser", container).slideUp();
                        this.element.html("<span class='k-icon k-i-arrow-s'></span>");
                    } else {
                        this.element.html("<span class='k-icon k-loading'></span>");
                        var that = this;
                        if (!Application.controllers[self.options.controllerName][self.options.field + "_chooser"].dataSource._filter) {
                            Application.getController({name: self.options.controller, path: self.options.path}, function () {
                                if (self.options.gridColumnsFilters) {
                                    var cfKeys = keysOf(self.options.gridColumnsFilters);
                                    for (var i = 0; i < cfKeys.length; i++) {
                                        var col = cfKeys[i];
                                        this.setGridColumnFilters(conf, col, self.options.gridColumnsFilters[col]);
                                    }
                                    chooserGrid.setOptions({columns: conf.columns});
                                }
                                var opts = $.extend(true, {}, this.dataSourceOptions);
                                opts.pageSize = self.options.pageSize || 5;
                                opts.transport.parameterMap = function (options, type) {
                                    if (type !== "read") {
                                        if (options.models)
                                            return {models: kendo.stringify(options.models)};
                                        if (options)
                                            return {models: kendo.stringify([options])};
                                    }
                                    return {
                                        data: options.data,
                                        page: options.page,
                                        filter: kendo.stringify(options.filter),
                                        sort: kendo.stringify(options.sort),
                                        pageSize: opts.pageSize
                                    };
                                };
                                if (self.options.filter) {
                                    if ($.isFunction(self.options.filter))
                                        opts.filter = self.options.filter.call(self);
                                    else
                                        opts.filter = self.options.filter;
                                }
                                if (chooserGrid.chooserFilters) {
                                    if ($.isFunction(chooserGrid.chooserFilters))
                                        opts.filter = chooserGrid.chooserFilters.call(self);
                                    else
                                        opts.filter = chooserGrid.chooserFilters;
                                }
                                var ds = new kendo.data.DataSource(opts);
                                Application.controllers[self.options.controllerName][self.options.field + "_chooser"].setDataSource(ds);
                                $("." + self.options.field + "_chooser", container).slideDown();
                                that.element.html("<span class='k-icon k-i-arrow-n'></span>");
                            }, true);
                        } else {
                            $("." + self.options.field + "_chooser", container).slideDown(function () {
                                Application.controllers[self.options.controllerName][self.options.field + "_chooser"].dataSource.read();
                                that.element.html("<span class='k-icon k-i-arrow-n'></span>");
                            });
                        }
                    }
                });
                var bGet = false;
                if (model && model[self.options.field]) {
                    if (isNaN(model[self.options.field]) && model[self.options.field].length > 0)
                        bGet = true;
                    if (!isNaN(model[self.options.field]) && model[self.options.field] > 0)
                        bGet = true;
                }

                if (bGet) {
                    var action = 'get';
                    var params = {id: model[self.options.field]};
                    if (self.options.fieldId) {
                        action = 'read';
                        params = {
                            filter: {
                                logic: "and",
                                filters: []
                            },
                            pageSize: 1,
                            page: 1
                        };
                        var k = keysOf(self.options.fieldId);
                        for (var i = 0; i < k.length; i++) {
                            params.filter.filters.push({
                                field: self.options.fieldId[k[i]],
                                operator: "eq",
                                value: model[k[i]]
                            });
                        }
                    }
                    $.ajax({
                        url: Application.service.url + '/' + self.options.path + '/' + action,
                        dataType: Application.dataType,
                        data: params,
                        success: function (data) {
                            if (self.options.fieldId && data.data && data.data.length > 0) {
                                var data = data.data[0];
                            }
                            var text = [];
                            if (self.options.display) {
                                for (var i = 0; i < self.options.display.length; i++) {
                                    var fieldToDisplay = self.options.display[i];
                                    if ($.isPlainObject(fieldToDisplay)) {
                                        var callback = fieldToDisplay.callback;
                                        var field = fieldToDisplay.field;
                                        if (data[field]) {
                                            text.push(callback(data[field]));
                                        }
                                    } else {
                                        if (data[fieldToDisplay])
                                            text.push(data[fieldToDisplay]);
                                    }
                                }
                            }
                            $("." + self.options.field + "_display", container).val(text.join(self.options.separator));
                            $("[name=" + self.options.field + "]", container).trigger("chooserchanged", data);
                            if (self.options.fieldId) {
                                var k = keysOf(self.options.fieldId);
                                for (var i = 0; i < k.length; i++) {
                                    if ($.isFunction(model.set))
                                        model.set(k[i], data[self.options.fieldId[k[i]]]);
                                }
                            }
                            $("[name=" + self.options.field + "]", container).data("choosed", data);
                        }
                    });
                }
                if ($("." + self.options.field + "_chooser", container).is(":visible"))
                    $("." + self.options.field + "_chooser", container).slideUp();
                $("." + self.options.field + "_display", container).val('');
            };
        }
        ,
        /**
         * object
         */
        notification: {
        }
        ,
        /**
         * objects collection
         */
        controllers: {
        }
        ,
        /**
         * #param object shortcuts
         */
        shortcuts: {
            items: {},
            clean: function () {
                $("#shortcuts a").each(function () {
                    $(this).remove();
                });
            }
            ,
            load: function () {
                var shortcuts = Application.storage.getItem("shortcuts") || '{}';
                Application.shortcuts.items = JSON.parse(shortcuts);
                var scKeys = keysOf(Application.shortcuts.items);
                for (var i = 0; i < scKeys.length; i++) {
                    var button = $("<a/>").attr({
                        id: "sc-" + scKeys[i],
                        href: '#'
                    });
                    if (Application.shortcuts.items[scKeys[i]].icon)
                        button.append("<span class='" + Application.shortcuts.items[scKeys[i]].icon + "'></span>");
                    button.appendTo($("#shortcuts"));
                    button.addClass("shortcut-item");
                    button.css(Application.shortcuts.items[scKeys[i]].position);
                    if (Application.shortcuts.items[scKeys[i]].color) {
                        button.css("color", Application.shortcuts.items[scKeys[i]].color);
                    }
                    if (Application.shortcuts.items[scKeys[i]].background) {
                        button.css("background-color", Application.shortcuts.items[scKeys[i]].background);
                    }
                    button.focus(function () {
                        $(this).css("opacity", 1);
                        $(this).unbind('mouseenter mouseleave');
                    });
                    button.blur(function () {
                        $(this).css("opacity", .75);
                        $(this).hover(function () {
                            $(this).css("opacity", .9);
                        }, function () {
                            $(this).css("opacity", .75);
                        });
                    });
                    button.trigger("blur");
                    button.append("<div>" + Application.shortcuts.items[scKeys[i]].text + "</div>");
                    button.click(function () {
                        if (!$(this).attr("clicked")) {
                            $(this).trigger("focus");
                            $(".shortcut-item").not(this).trigger("blur");
                            $(this).attr("clicked", "clicked");
                            var self = this;
                            setTimeout(function () {
                                $(self).removeAttr("clicked");
                            }, 300);
                        } else {
                            var controller = $(this).attr("id").substr(3);
                            Application.menu._callback["menu-" + controller].call();
                            $(this).removeAttr("clicked");
                        }
                    });
                    button.show();
                }
                var menu = $("<ul/>");
                menu.kendoContextMenu({
                    orientation: "vertical",
                    target: "#shortcuts",
                    filter: ".shortcut-item",
                    animation: {
                        open: {effects: "fadeIn"},
                        duration: 500
                    },
                    select: function (e) {
                        var controller = $(e.target).attr("id").substr(3);
                        if ($(e.item).hasClass("cm-remove-shortcut")) {
                            if (confirm("Remove this link from desktop ?"))
                                Application.ui.removeShortcut(controller);
                        }
                        if ($(e.item).hasClass("cm-open-shortcut")) {
                            Application.menu._callback["menu-" + controller].call();
                        }
                    }
                });
                var cmConfig = [];
                cmConfig.push({text: "Open", cssClass: "cm-open-shortcut"});
                cmConfig.push({text: "Remove", cssClass: "cm-remove-shortcut"});
                menu.data("kendoContextMenu").append(cmConfig);
            }
            ,
            save: function () {
                var shortcuts = JSON.stringify(Application.shortcuts.items);
                Application.storage.setItem("shortcuts", shortcuts);
            }
            ,
            add: function (controller, item) {
                Application.shortcuts.items[controller] = item;
                Application.shortcuts.save();
                Application.shortcuts.refresh();
            }
            ,
            refresh: function () {
                Application.shortcuts.clean();
                Application.shortcuts.load();
            }
            ,
            remove: function (controller) {
                if (!Application.shortcuts.items[controller])
                    return;
                delete Application.shortcuts.items[controller];
                Application.shortcuts.save();
                Application.shortcuts.refresh();
            }
        },
        /**
         * arrays collection
         */
        dependencies: {},
        /**
         * object
         */
        service: {
            url: "/",
            menu: "/admin/admin-menus/read"
        },
        /**
         * Retrive or load a controller
         * @param string|object options
         * @param function callback
         * @param bool waitDS
         * @return void
         */
        getController: function (/*mixed*/options, /*function*/callback, /*bool*/waitDS) {

            var dependencies, name, path;

            waitDS = waitDS === false ? false : true;

            if (typeof options === "string") {
                name = path = options;
            }
            if (typeof options === "object") {
                name = options.name;
                if (options.path)
                    path = options.path;
                if (options.waitDS)
                    waitDS = options.waitDS;
            }

            if (Application.controllers[name]) {
                if (callback && $.isFunction(callback))
                    callback.apply(Application.controllers[name]);
                $(Application.controllers[name]).trigger("loaded");
                return;
            }
            Application.controllers[name] = new Application.Controller(name, path);
            kendo.ui.progress($("body"), true);

            if (Application.dependencies[name] && $.isArray(Application.dependencies[name]) && Application.dependencies[name].length > 0) {

                /**
                 * Dependencies found so create a copy
                 * then instantiate a loader object
                 * which will be delegated to load all
                 * dependent controllers
                 */
                dependencies = Application.dependencies[name].slice();
                var loader = new Application.Loader();
                loader.load(dependencies, function () {
                    /**
                     * All dependecies are loaded so now we can initialize the controller
                     */
                    Application.controllers[name].init(callback, waitDS);
                });
                return;
            }
            Application.controllers[name].init(callback, waitDS);
        },
        /**
         * Loader to load controllers
         * @return object Application.Loader
         */
        Loader: function () {
            this.dependencies = [];
            /**
             *
             * @param array dependencies
             * @param function callback
             * @return void
             */
            this.load = function (/*array*/dependencies, /*function*/callback) {
                this.callback = callback;
                this.dependencies = dependencies.reverse();
                this._load();
            };

            var loader = this;

            /**
             * Internal pop() controllers and call getController foreach one
             * when finished apply the callback
             * @return void
             */
            this._load = function () {
                if (loader.dependencies.length === 0) {
                    loader.callback.call();
                    return;
                }

                var controller = loader.dependencies.pop();
                Application.getController(controller, function () {
                    $(this).one("loaded", loader._load);
                }, !!controller.waitDS);
            };
        },
        /**
         * object User Interface handler
         */
        ui: {
            wallpaper: null,
            skin: 'default',
            removeOverlay: function () {
                $("#overlay").remove();
                $("#menu-container").css('z-index', 1);
            },
            openedWindows: function () {
                var wins = [];
                var cKeys = Object.keys(Application.controllers);
                for (var i = 0; i < cKeys.length; i++) {
                    var cn = cKeys[i];
                    if (Application.controllers[cn] &&
                            Application.controllers[cn].window &&
                            Application.controllers[cn].window.wrapper &&
                            (!Application.controllers[cn].window || Application.controllers[cn].window.wrapper.is(":visible"))) {
                        wins.push(Application.controllers[cn].window);
                    }
                }
                return wins;
            },
            createShortcut: function (window) {
                if ($("#wnd-create-shortcut").length === 0) {
                    $.ajax({
                        url: Application.service.url + "/app/css/flaticon.css",
                        dataType: "text",
                        error: function () {
                            alert("unable to load resource flaticon.css");
                        },
                        success: function (text) {
                            var data = [];
                            var re = /\.flaticon\-(.*)\:before/g;
                            var matches = text.match(re);
                            for (var i = 0; i < matches.length; i++) {
                                var match = re.exec(matches[i]);
                                if (!match)
                                    continue;
                                data.push({
                                    icon: "flaticon-" + match[1]
                                });
                            }
                            $("<div id='wnd-create-shortcut'/>").appendTo("#placeholder").kendoWindow({
                                title: "Create a shortcut",
                                modal: true,
                                width: 570,
                                height: 520,
                                resizable: false,
                                open: function () {
                                    var listView = $("<div id='icon'/>").css({
                                        height: 360,
                                        overflow: "auto"
                                    }).kendoListView({
                                        height: "70%",
                                        selectable: true,
                                        dataSource: {
                                            data: data
                                        },
                                        template: "<div style=\"display: inline-block; width: 32px; height: 32px; padding-top: 5px;\"><span style='margin-left: -14px;' class='#:icon#'></span></div>"
                                    }).data("kendoListView");
                                    this.element.empty();
                                    this.element.append(listView.element);
                                    this.element.append("<div id='shortcut-settings'>");
                                    var container = this.element.find('#shortcut-settings');
                                    container.css({
                                        padding: "10px",
                                        lineHeight: "3px"
                                    });
                                    container.append("<div style='margin:3px'><label class='form-label'>Label:</label><input type='text' id='label'/></div>");
                                    $("#label", this.element).addClass("k-textbox").css("width", "350px").val(window.options.title);

                                    container.append("<div style='margin:3px'><label class='form-label'>Color:</label><input type='text' id='color'/></div>");
                                    $("#color", this.element).addClass("k-textbox").val("#ffffff");
                                    $("#color", this.element).kendoColorPicker({
                                        value: "#000000",
                                        buttons: false
                                    });
                                    container.append("<div style='margin:3px'><label class='form-label'>Background:</label><input type='text' id='background'/></div>");
                                    $("#background", this.element).addClass("k-textbox").val("#000000");
                                    $("#background", this.element).kendoColorPicker({
                                        value: "#ffffff",
                                        buttons: false
                                    });
                                    var btns = $("<div/>").css({
                                        "text-align": "center"
                                    });
                                    btns.addClass("k-block");
                                    btns.append('<a class="k-grid-update k-primary"><span class="k-icon k-update"></span>OK</a>&nbsp;<a class="k-grid-cancel"><span class="k-icon k-i-cancel"></span>Cancel</a>');
                                    container.append(btns);
                                    var self = this;
                                    this.element.find(".k-grid-update").kendoButton({
                                        width: "30%",
                                        click: function () {

                                            var icon, selected = $("#icon", self.element).data("kendoListView").select();
                                            if (selected)
                                                icon = selected.find("span").attr("class");

                                            var position = {
                                                top: 70,
                                                left: 30
                                            };

                                            var shortcuts = $(".shortcut-item");
                                            var c = 0;
                                            while (true) {
                                                var found = false;
                                                for (var i = 0; i < shortcuts.length; i++) {
                                                    var ePos = shortcuts.eq(i).position();
                                                    if (ePos.top === position.top && ePos.left === position.left) {
                                                        found = true;
                                                        c++;
                                                        break;
                                                    }
                                                }
                                                if (!found)
                                                    break;
                                                position.top += 70;
                                                if (c % 10 === 0) {
                                                    position.left += 300;
                                                    position.top = 70;
                                                    c = 0;
                                                }
                                            }
                                            var item = {
                                                icon: icon,
                                                position: position,
                                                text: $("#label", self.element).val(),
                                                color: $("#color", self.element).data("kendoColorPicker").value(),
                                                background: $("#background", self.element).data("kendoColorPicker").value()
                                            };
                                            Application.shortcuts.add(window.options.controllerName, item);
                                            $("#wnd-create-shortcut").data("kendoWindow").close();
                                        }
                                    });
                                    this.element.find(".k-grid-cancel").kendoButton({
                                        click: function () {
                                            $("#wnd-create-shortcut").data("kendoWindow").close();
                                        }
                                    });
                                }
                            });
                            var wnd = $("#wnd-create-shortcut").data("kendoWindow");
                            wnd.center().open();
                        }
                    });
                } else {
                    var wnd = $("#wnd-create-shortcut").data("kendoWindow");
                    wnd.center().open();
                }
            },
            removeShortcut: function (window) {
                if (typeof window === "string")
                    Application.shortcuts.remove(window);
                else
                    Application.shortcuts.remove(window.options.controllerName);
            },
            state: {
                controllers: {},
                windowOpen: function (e) {
                    Application.ui.state.save(e, "open");
                },
                windowResize: function (e) {
                    if (this.windowResizing) {
                        return;
                    }
                    this.windowResizing = true;
                    var promise = Application.ui.state.save(e, "resize");
                    resizeGrid(e.sender.element.find("[id^=grid-]"));
                    if (promise) {
                        return promise.done(function () {
                            this.windowResizing = false;
                        });
                    }
                    this.windowResizing = false;
                },
                windowMove: function (e) {
                    Application.ui.state.save(e, "move");
                },
                windowClose: function (e) {
                    Application.ui.state.save(e, "close");
                },
                save: function (e, op) {
                    var isMaximized = false;
                    var isMinimized = false;
                    if (!e || !e.sender) {
                        return;
                    }
                    if (e.sender.wrapper) {
                        isMaximized = e.sender.wrapper.hasClass("k-window-maximized");
                        isMinimized = e.sender.element.data("kendoWindow").options.isMinimized;
                        if (isMaximized) {
                            $("#menu-sensor").css({
                                zIndex: Application.ui.getMaxZIndex() + 1
                            }).show();
                            e.sender.element.on("click", Application.ui.removeOverlay);
                            $("#overlay").on("click", Application.ui.removeOverlay);
                        } else {
                            $("#menu-sensor").css('z-index', 1);
                            Application.ui.removeOverlay();
                        }
                    }

                    if (op === 'open') {
                        e.sender.wrapper.addClass("main-window");
                        e.sender.trigger("resize");
                    }
                    var ss = Application.storage.getItem("savestate");
                    if (ss > 0) {
                        var controller = e.sender.options.controllerName;
                        switch (op) {
                            case 'close':
                                if (!Application.ui.state.controllers[controller])
                                    break;
                                Application.ui.state.controllers[controller].opened = false;
                                break;

                            case 'move':
                                if (!Application.ui.state.controllers[controller])
                                    break;
                                var c = Application.ui.state.controllers[controller] || {};
                                c.isMaximized = isMaximized;
                                c.isMinimized = isMinimized;
                                if (!isMaximized) {
                                    c.position = e.sender.options.position;
                                }
                                break;

                            case 'resize':
                                if (!Application.ui.state.controllers[controller])
                                    break;
                                var c = Application.ui.state.controllers[controller] || {};
                                c.isMaximized = isMaximized;
                                c.isMinimized = isMinimized;
                                if (!isMaximized) {
                                    c.width = e.width;
                                    c.height = e.height;
                                    c.position = e.sender.options.position;
                                }
                                break;

                            case 'open':
                                var c = Application.ui.state.controllers[controller] || {};
                                c.opened = true;
                                c.isMaximized = isMaximized;
                                c.isMinimized = isMinimized;
                                c.path = Application.controllers[controller].path;
                                if (!isMaximized) {
                                    c.width = e.sender.options.width;
                                    c.height = e.sender.options.height;
                                    c.position = e.sender.options.position;
                                }
                                Application.ui.state.controllers[controller] = c;
                                break;
                        }
                        var serialized = JSON.stringify(Application.ui.state.controllers);
                        var done = Application.storage.setItem('controllers', serialized);
                        e.sender.element.trigger("windowstatechanged");
                        return done;
                    }
                }
            },
            changeCulture: function (culture, then) {
                if (Application.ui.culture === culture) {
                    return;
                }
                var after = function () {
                    var done = function (url) {
                        $("head script.culture").attr("src", url);
                        Application.ui.culture = culture;
                        kendo.culture(culture);
                        if ($.isFunction(then))
                            then.call();
                    };
                    $.ajaxSetup(ajaxSetupSilent);
                    var url = "/js/cultures/kendo.culture." + culture + ".min.js";
                    $.getScript(url).done(function () {
                        $.ajaxSetup(ajaxSetup);
                        done(url);
                    }).fail(function (jqxhr, settings, exception) {
                        $.ajaxSetup(ajaxSetup);
                        if (jqxhr.readyState !== 0) {
                            if (Application.kendoVersion)
                                url = "http://kendo.cdn.telerik.com/" + Application.kendoVersion + url;
                            $.getScript(url).done(function () {
                                done(url);
                            }).fail(function (jqxhr, settings, exception) {
                                console.log(jqxhr, settings, exception);
                                return alert("Error calling URL: ", url + "\n" + exception.toString());
                            });
                        } else {
                            console.log(jqxhr, settings, exception);
                            return alert("Error calling URL: ", url + "\n" + exception.toString());
                        }
                    });
                };
                Application.storage.setItem("kendoCulture", culture).done(after);
            },
            changeLanguage: function (language, then) {
                if (Application.ui.language === language) {
                    return;
                }
                var after = function () {
                    var l = [
                        language,
                        (language === "en") ? 'GB' : language.toUpperCase()
                    ];
                    var done = function (url) {
                        $("head script.messages").attr("src", url);
                        $.getScript("/app/lang/lang." + language + ".js").done(function () {
                            $("head script.language").attr("src", "/app/lang/lang." + language + ".js");
                            Application.ui.language = language;
                            if ($.isFunction(then))
                                then.call();
                        });
                    };
                    $.ajaxSetup(ajaxSetupSilent);
                    var url = "/js/messages/kendo.messages." + l[0] + "-" + l[1] + ".min.js";
                    $.getScript(url).done(function () {
                        $.ajaxSetup(ajaxSetup);
                        done(url);
                    }).fail(function (jqxhr, settings, exception) {
                        $.ajaxSetup(ajaxSetup);
                        if (jqxhr.readyState !== 0) {
                            if (Application.kendoVersion)
                                url = "http://kendo.cdn.telerik.com/" + Application.kendoVersion + url;
                            $.getScript(url).done(function () {
                                done(url);
                            }).fail(function (jqxhr, settings, exception) {
                                console.log(jqxhr, settings, exception);
                                return alert("Error calling URL: ", url + "\n" + exception.toString());
                            });
                        } else {
                            console.log(jqxhr, settings, exception);
                            return alert("Error calling URL: ", url + "\n" + exception.toString());
                        }
                    });
                };
                Application.storage.setItem("kendoLanguage", language).done(after);
            },
            changeBackgroundColor: function (color) {
                Application.storage.setItem("kendoBackgroundColor", color);
                Application.ui.backgroundColor = color;
                if (color === "" || color === "null" || color === null)
                    $("html").css("background-color", "none");
                else {
                    $("html").css("background-color", color);
                }
            },
            changeWallpaper: function (wallpaperName) {
                //console.log("WALLPAPER CHANGED TO:", wallpaperName);
                Application.storage.setItem("kendoWallpaper", wallpaperName);
                Application.ui.wallpaper = wallpaperName;
                if (wallpaperName === "" || wallpaperName === "null" || wallpaperName === null)
                    $("html").css("background-image", "none"); //"url(" + wallpaperName + ") no-repeat center center fixed");
                else {
                    $("html").css("background-image", "url('" + wallpaperName + "')"); //"url(" + wallpaperName + ") no-repeat center center fixed");
                }
            },
            theme: {
                skinRegex: /styles\/kendo\.[\w\-]+(\.min)?\.(.+)/i,
                skinRegexExt: /extend\.[\w\-]+.(.+)/i,
                preloadStylesheet: function (file, callback) {
                    var element = $("<link rel='stylesheet' media='print' href='" + file + "' />").appendTo("head");

                    setTimeout(function () {
                        callback();
                        element.remove();
                    }, 100);
                },
                getCurrentCommonLink: function () {
                    return $("head link").filter(function () {
                        return (/kendo\.common/gi).test(this.href);
                    });
                },
                getCurrentExtensionLink: function () {
                    return $("head link").filter(function () {
                        return (/extend\./gi).test(this.href);
                    });
                },
                getCurrentThemeLink: function () {
                    return $("head link").filter(function () {
                        return (/kendo\./gi).test(this.href) && !(/common|rtl|dataviz/gi).test(this.href);
                    });
                },
                getCommonUrl: function (common) {
                    var currentCommonUrl = Application.ui.theme.getCurrentCommonLink().attr("href");
                    return currentCommonUrl.replace(Application.ui.theme.skinRegex, "styles\/kendo." + common + "$1.$2");
                },
                getThemeUrl: function (themeName) {
                    var currentThemeUrl = Application.ui.theme.getCurrentThemeLink().attr("href");
                    return currentThemeUrl.replace(Application.ui.theme.skinRegex, "styles\/kendo." + themeName + "$1.$2");
                },
                getExtensionUrl: function (themeName) {
                    var currentExtensionUrl = Application.ui.theme.getCurrentExtensionLink().attr("href");
                    //console.log(Application.ui.theme.skinRegexExt, currentExtensionUrl, Application.ui.theme.skinRegexExt.test(currentExtensionUrl));
                    return currentExtensionUrl.replace(Application.ui.theme.skinRegexExt, "extend." + themeName + ".$1");
                },
                replaceWebTheme: function (themeName) {
                    var newThemeUrl = Application.ui.theme.getThemeUrl(themeName),
                            newExtensionUrl = Application.ui.theme.getExtensionUrl(themeName),
                            oldThemeName = $(doc).data("kendoSkin"),
                            themeLink = Application.ui.theme.getCurrentThemeLink(),
                            extensionLink = Application.ui.theme.getCurrentExtensionLink();

                    Application.ui.theme.updateLink(themeLink, newThemeUrl);
                    Application.ui.theme.updateLink(extensionLink, newExtensionUrl);
                    $(doc.documentElement).removeClass("k-" + oldThemeName).addClass("k-" + themeName);
                },
                updateLink: function (link, url) {
                    var newLink,
                            less = window.less,
                            isLess = /\.less$/.test(link.attr("href"));

                    if (kendo.support.browser.msie) {
                        if (doc.createStyleSheet)
                            newLink = $(doc.createStyleSheet(url));
                        else {
                            var linkTag = doc.createElement("link");
                            linkTag.href = url;
                            linkTag.rel = "stylesheet";
                            var head = document.getElementsByTagName("head")[0];
                            head.appendChild(linkTag);
                        }
                    } else {
                        newLink = link.eq(0).clone().attr("href", url);
                        link.eq(0).before(newLink);
                    }

                    link.remove();

                    if (isLess) {
                        $("head style[id^='less']").remove();

                        less.sheets = $("head link[href$='.less']").map(function () {
                            return this;
                        });

                        less.refresh(true);
                    }
                },
                replaceTheme: function (themeName) {
                    Application.ui.theme.replaceWebTheme(themeName);
                    $("body").trigger("kendo:skinChange");
                    Application.ui.skin = themeName;
                },
                changeTheme: function (themeName, animate) {
                    if (Application.ui.theme.getThemeUrl(themeName) === Application.ui.theme.getCurrentThemeLink().attr("href")) {
                        return;
                    }
                    Application.storage.setItem("kendoSkin", themeName);
                    if (animate) {
                        Application.ui.theme.preloadStylesheet(Application.ui.theme.getThemeUrl(themeName), function () {
                            var body = $("body");
                            body.kendoStop().kendoAnimate(extend({}, animation.hide, {complete: function (element) {
                                    if (element[0] === body[0]) {
                                        body.css("visibility", "hidden"); // Hide the element with restored opacity.
                                        Application.ui.theme.replaceTheme(themeName);
                                        setTimeout(function () {
                                            body
                                                    .css("visibility", "visible")
                                                    .kendoStop()
                                                    .kendoAnimate(animation.show);
                                        }, 100);
                                    }
                                }
                            }));
                        });
                    } else {
                        Application.ui.theme.replaceTheme(themeName);
                    }
                }
            },
            getDefaultTransport: function ($ctrl) {
                return {
                    read: {
                        url: Application.service.url + "/" + $ctrl.path + "/read",
                        dataType: "json"
                    },
                    update: {
                        url: Application.service.url + "/" + $ctrl.path + "/update",
                        dataType: "json",
                        type: "POST"
                    },
                    destroy: {
                        url: Application.service.url + "/" + $ctrl.path + "/destroy",
                        dataType: "json",
                        type: "POST"
                    },
                    create: {
                        url: Application.service.url + "/" + $ctrl.path + "/create",
                        dataType: "json",
                        type: "POST",
                        complete: $ctrl.reopenOnSave ? $ctrl.recordCreated : function() {}
                    }
                };
            },
            getMaxZIndex: function (container, selector) {
                var sel = selector || "*";
                var doc = container || document.body;
                var zmax = 0;
                $(sel, doc).each(function () {
                    var cur = parseInt($(this).css('z-index'));
                    zmax = cur > zmax ? cur : zmax;
                });
                return zmax;
            },
            addExtensionClass: function (extension) {
                switch (extension) {
                    case '.jpg':
                    case '.img':
                    case '.png':
                    case '.gif':
                        return "img-file";
                    case '.doc':
                    case '.docx':
                        return "doc-file";
                    case '.xls':
                    case '.xlsx':
                        return "xls-file";
                    case '.pdf':
                        return "pdf-file";
                    case '.zip':
                    case '.rar':
                        return "zip-file";
                    default:
                        return "default-file";
                }
            },
            dump: function (/*object*/o, /*string*/name, /*function*/wrapNode, /*function*/wrapLeaf) {
                if (typeof o === 'undefined')
                    return '';
                if (!$.isFunction(wrapNode))
                    wrapNode = function (o) {
                        return o;
                    };
                if (!$.isFunction(wrapLeaf))
                    wrapLeaf = function (o) {
                        return o;
                    };
                if (typeof o !== 'object')
                    return name ? '<input type="text" name="' + name + '" value="' + o + '"/>' : wrapLeaf(o);
                var id = name ? name.replace(/\[/g, '_').replace(/\]/g, '') : ('o_' + Math.random()).replace('.', '');
                var d = '<ul id="' + id + '">';
                for (var i in o) {
                    if (!o.hasOwnProperty(i))
                        continue;
                    d += '<li><b>' + wrapNode(i) + '</b>: ' + (name ? '<br/>' + Application.ui.dump(o[i], name + '[' + i + ']', wrapNode, wrapLeaf) : Application.ui.dump(o[i], name, wrapNode, wrapLeaf)) + '</li>';
                }
                d += '</ul>';
                return d;
            },
            editor: {
                EMPTY: {
                    stylesheets: [
//                        "/app/css/editor.css",
//                        "/app/css/pdf-export-styles.css"
                    ],
                    tools: []
                },
                FULL: {
                    serialization: {
                        scripts: true
                    },
                    tools: [
                        "bold",
                        "italic",
                        "underline",
                        "strikethrough",
                        "justifyLeft",
                        "justifyCenter",
                        "justifyRight",
                        "justifyFull",
                        "insertUnorderedList",
                        "insertOrderedList",
                        "indent",
                        "outdent",
                        "createLink",
                        "unlink",
                        "insertImage",
                        "insertFile",
                        "subscript",
                        "superscript",
                        "createTable",
                        "addRowAbove",
                        "addRowBelow",
                        "addColumnLeft",
                        "addColumnRight",
                        "deleteRow",
                        "deleteColumn",
                        "viewHtml",
                        "formatting",
                        "fontName",
                        "fontSize",
                        "foreColor",
                        "backColor",
                        "cleanFormatting",
                        "pdf",
                        {
                            name: "fullscreen",
                            template:
                                    '<a class="k-button" onclick="onFullScreen($(this).closest(\'table\').find(\'textarea\').data(\'kendoEditor\'))">' +
                                    '<span class="k-icon k-i-maximize k-tool-icon"></span> Toggle fullscreen' +
                                    '</a>'
                        }
                    ],
                    stylesheets: [
//                        "/app/css/editor.css",
//                        "/app/css/pdf-export-styles.css"
                    ],
                    imageBrowser: {
                        messages: {
                            dropFilesHere: "Drop files here"
                        },
                        transport: {
                            read: {
                                url: "/admin/filesystem/read",
                                type: "GET"
                            },
                            destroy: {
                                url: "/admin/filesystem/destroy",
                                type: "POST"
                            },
                            create: {
                                url: "/admin/filesystem/create",
                                type: "POST"
                            },
                            thumbnailUrl: "/admin/filesystem/thumbnail",
                            uploadUrl: "/admin/filesystem/upload",
                            imageUrl: function (e) {
                                return "/storage/" + unescape(e);
                            }
                        }
                    },
                    fileBrowser: {
                        messages: {
                            dropFilesHere: "Drop files here"
                        },
                        transport: {
                            read: {
                                url: "/admin/filesystem/read",
                                type: "GET"
                            },
                            destroy: {
                                url: "/admin/filesystem/destroy",
                                type: "POST"
                            },
                            create: {
                                url: "/admin/filesystem/create",
                                type: "POST"
                            },
                            uploadUrl: "/admin/filesystem/upload",
                            fileUrl: function (e) {
                                return "/storage/" + unescape(e);
                            }
                        }
                    },
                    pdf: {
                        fileName: "NewDocument.pdf",
                        proxyURL: "http://demos.telerik.com/kendo-ui/service/export",
                        paperSize: "a4",
                        margin: {
                            bottom: 20,
                            left: 20,
                            right: 20,
                            top: 20
                        }
                    }
                }
            },
            grid: {
                columnHide: function (e) {
                    var name = e.sender.dataSource.options.controller.name;
                    if (!Application.ui.state.controllers[name])
                        return;
                    var c = Application.ui.state.controllers[name];
                    if (!c.hiddenColumns)
                        c.hiddenColumns = [];
                    c.hiddenColumns.push(e.column.field);
                    Application.ui.state.controllers[name] = c;
                    var serialized = JSON.stringify(Application.ui.state.controllers);
                    Application.storage.setItem('controllers', serialized);
                },
                columnShow: function (e) {
                    var name = e.sender.dataSource.options.controller.name;
                    if (!Application.ui.state.controllers[name])
                        return;
                    var c = Application.ui.state.controllers[name] || {};
                    if (!c.hiddenColumns)
                        c.hiddenColumns = [];
                    var i = c.hiddenColumns.indexOf(e.column.field);
                    if (i >= 0)
                        c.hiddenColumns.splice(i, 1);
                    Application.ui.state.controllers[name] = c;
                    var serialized = JSON.stringify(Application.ui.state.controllers);
                    Application.storage.setItem('controllers', serialized);
                }
            }
        }
        ,
        /**
         * Initialize the application
         * @return void
         */
        init: function () {


            Application.notification = $("#notification").kendoNotification({
                allowHideAfter: 1000,
                autoHideAfter: 3000,
                button: true,
                hideOnClick: false,
                position: {
                    top: 20,
                    right: 20
                },
                stacking: "down",
                show: function (e) {
                    e.element.parent().css({
                        zIndex: Application.ui.getMaxZIndex() + 1
                    });
                },
                hide: function (e) {
                    setTimeout(function () {
                        e.element.parent().css({
                            zIndex: 0
                        });
                    }, this.options.autoHideAfter);
                }
            }).data("kendoNotification");

            if (supports.pushState) {
                Application.href = location.href;
                try {
                    history.replaceState({href: location.href}, null, location.href);
                } catch (err) {
                    throw err;
                }
            }

            var kendoSkin = Application.storage.getItem("kendoSkin") || Application.ui.skin;
            Application.ui.theme.changeTheme(kendoSkin);

            var kendoWallpaper = Application.storage.getItem("kendoWallpaper") || Application.ui.wallpaper;
            Application.ui.changeWallpaper(kendoWallpaper);

            var kendoBackgroundColor = Application.storage.getItem("kendoBackgroundColor") || Application.ui.backgroundColor;
            Application.ui.changeBackgroundColor(kendoBackgroundColor);

        },
        /**
         * run the application by constructing the menu object
         * @return void
         */
        run: function () {

            var kendoCulture = Application.storage.getItem("kendoCulture") || Application.ui.culture;
            Application.ui.changeCulture(kendoCulture);

            var kendoLanguage = Application.storage.getItem("kendoLanguage") || Application.ui.language;
            Application.ui.changeLanguage(kendoLanguage);

            Application.menu.setup(function () {
                $("#menu-sensor").hover(function () {
                    var mzi = Application.ui.getMaxZIndex();
                    var czi = $("#menu-container").css("z-index");
                    if (mzi > czi) {
                        $("#menu-container").css({zIndex: mzi + 1});
                    }
                });

                $("#shortcuts").kendoDraggable({
                    filter: ".shortcut-item", //specify which items will be draggable
                    hint: function (element) { //create a UI hint, the `element` argument is the dragged item
                        return element.clone().css({
                            "opacity": 0.6,
                            "background-color": "#0cf"
                        });
                    }
                });
                $(doc.body).kendoDropTargetArea({
                    filter: "#shortcuts",
                    drop: function (e) { //apply changes to the data after an item is dropped
                        var draggableElement = e.draggable.currentTarget;
                        var pos = {
                            top: e.draggable.hintOffset.top,
                            left: e.draggable.hintOffset.left
                        };
                        draggableElement.css(pos);
                        var controller = draggableElement.attr("id").substr(3);
                        Application.shortcuts.items[controller].position = pos;
                        Application.shortcuts.save();
                    }
                });
                Application.shortcuts.load();

                var ss = parseInt(Application.storage.getItem("savestate"));
                if (ss === 0)
                    return;

                var cstr = Application.storage.getItem('controllers') || "{}";
                var controllers = JSON.parse(cstr);
                for (var cn in controllers) {
                    if (!controllers[cn] || !controllers[cn].path || !controllers[cn].opened)
                        return;
                    Application.getController({name: cn, path: controllers[cn].path}, function () {
                        if (this.grid && controllers[this.name]) {
                            if (controllers[this.name].hiddenColumns) {
                                for (var x in controllers[this.name].hiddenColumns) {
                                    this.grid.hideColumn(controllers[this.name].hiddenColumns[x]);
                                }
                                if (!Application.ui.state.controllers[this.name])
                                    Application.ui.state.controllers[this.name] = {};
                                Application.ui.state.controllers[this.name].hiddenColumns = controllers[this.name].hiddenColumns;
                            }
                        }
                        if (this.window && this.window.open) {
                            if (this.window.setOptions) {
                                this.window.setOptions({
                                    height: controllers[this.name].height,
                                    width: controllers[this.name].width,
                                    position: controllers[this.name].position
                                });
                            }
                            this.window.open();
                            if (controllers[this.name].isMaximized)
                                this.window.maximize();
                            if (controllers[this.name].isMinimized)
                                this.window.minimize();
                        }
                    });
                }

                $("body").css("visibility", "visible");
                $(Application).trigger("ready");
            });
            Application.initialized = true;
        },
        /**
         * Menu builder utils
         * @type object
         */
        menu: {
            callback: function (name, path) {
                return function () {
                    Application.getController({
                        name: name,
                        path: path || name
                    }, function () {
                        var offset = {
                            between: 30,
                            top: 40,
                            left: 20
                        };
                        if (this.window && this.window.open) {
                            if (!this.loaded && this.stackOnOpen) {
                                for (var p = 0; p < 10; p++) {
                                    var found = false;
                                    var pos = {left: p * offset.between + offset.left, top: p * offset.between + offset.top};
                                    var controllers = keysOf(Application.controllers);
                                    for (var i = 0; i < controllers.length; i++) {
                                        var cn = controllers[i];
                                        if (Application.controllers[cn].window && Application.controllers[cn].window.element && Application.controllers[cn].window.element.is(":visible")) {
                                            var cpos = Application.controllers[cn].window.options.position;
                                            if (cpos.left === pos.left && cpos.top === pos.top) {
                                                found = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (!found) {
                                        this.window.setOptions({position: pos});
                                        break;
                                    }
                                }
                            }
                            var ss = parseInt(Application.storage.getItem("savestate"));
                            if (ss > 0) {
                                var cstr = Application.storage.getItem('controllers') || "{}";
                                var controllers = JSON.parse(cstr);
                                if (this.grid && controllers[this.name]) {
                                    if (controllers[this.name].hiddenColumns) {
                                        for (var x in controllers[this.name].hiddenColumns) {
                                            this.grid.hideColumn(controllers[this.name].hiddenColumns[x]);
                                        }
                                        if (!Application.ui.state.controllers[this.name])
                                            Application.ui.state.controllers[this.name] = {};
                                        Application.ui.state.controllers[this.name].hiddenColumns = controllers[this.name].hiddenColumns;
                                    }
                                }
                            }
                            this.window.open();
                        }
                    });
                };
            },
            _callback: {},
            items: {},
            build: function (items) {
                items = items || Application.menu.items;
                var html = $("<ul></ul>");
                for (var label in items)
                    html.append(Application.menu.buildItem(label, items[label]));
                return html;
            },
            buildItem: function (label, item) {
                if (item.icon) {
                    label = '<span class="k-icon k-i-' + item.icon + '"></span> ' + label;
                }
                var html = $("<li>" + label + "</li>");
                if (item.id)
                    html.attr("id", item.id);
                if (item.id && item.callback) {
                    Application.menu._callback[item.id] = item.callback;
                }
                if (item.items) {
                    html.append(Application.menu.build(item.items));
                }
                return html;
            },
            prepare: function (data) {
                var menu = {};
                for (var i = 0; i < data.length; i++) {
                    var label = data[i].label || data[i].name;
                    if (data[i].languages) {
                        for (var x = 0; x < data[i].languages.length; x++) {
                            if (data[i].languages[x].language.code === Application.ui.language) {
                                label = data[i].languages[x].title;
                                break;
                            }
                        }
                    }
                    menu[label] = {
                        icon: data[i].icon
                    };
                    if (data[i].items) {
                        if (data[i].controller) {
                            menu[label].id = "menu-" + data[i].controller;
                        }
                        if (data[i].items.length === 0) {
                            if (data[i].controller) {
                                if (data[i].callback) {
                                    eval("menu[label].callback = function(){" + data[i].callback + "}");
                                } else {
                                    menu[label].callback = Application.menu.callback(data[i].controller, data[i].path ? data[i].path : null);
                                }
                            } else {
                                delete menu[label];
                            }
                        } else {
                            menu[label].items = Application.menu.prepare(data[i].items);
                        }
                    }
                }
                return menu;
            },
            select: function (e) {
                var id = $(e.item).attr("id");
                if (id) {
                    var cb = Application.menu._callback[id];
                    if (cb) {
                        cb.call();
                    }
                }
            },
            setup: function (cb) {
                $.ajax({
                    url: Application.service.menu,
                    data: {_: $.now()},
                    dataType: Application.dataType,
                    success: function (data) {
                        Application.menu.items = Application.menu.prepare(data);
                        var $menu = Application.menu.build();
                        $menu.attr("id", "menu");
                        $("#menu-container").append($menu);
                        $("#menu").kendoMenu({
                            openOnClick: true,
                            hoverDelay: 100,
                            select: Application.menu.select,
                            open: function (e) {
                                $("div.k-window").each(function () {
                                    if ($(this).hasClass("k-window-maximized")) {
                                        $("[data-role=window]", this).data("kendoWindow").toggleMaximization();
                                    }
                                });
                                var mzi = Application.ui.getMaxZIndex() + 1;
                                if (parseInt($("#menu-container").css('z-index')) < mzi) {
                                    $("#menu-container").css('z-index', mzi + 1);
                                }
                            },
                            deactivate: function (e) {
                                var v = false;
                                e.sender.element.children("li").each(function () {
                                    if ($(this).css("z-index") !== 'auto')
                                        v = true;
                                });
                                if (!v) {
                                    $("#menu-container").css('z-index', 1);
                                    e.sender.element.children("li").each(function () {
                                        $(this).css("z-index", 'auto');
                                    });
                                    $("#overlay").remove();
                                }
                            }
                        });
                        $(doc.body).keydown(function (e) {
                            if (e.altKey && e.keyCode === 87) {
                                $("#menu").focus();
                            }
                        });
                        cb.call();
                    },
                    error: function (xhr, status, msg) {
                        Application.Warning(xhr.responseText, status);
                    }
                });
            }
        },
        Warning: function (msg, title, callback) {
            var html = '<div class="k-state-error"><blockquote style="max-height: 500px; min-height: 200px; overflow: auto;">'
                    + msg
                    + '</blockquote></div><div class="k-block" style="text-align: center"><button class="k-button">Close</button>';
            var ew = $("<div></div>").html(html).css({
                display: "none",
                zIndex: Application.ui.getMaxZIndex()
            }).kendoWindow({
                modal: true,
                resizable: true,
                actions: ["Close"],
                width: 600,
                title: title,
                close: function () {
                    this.destroy();
                }
            }).data("kendoWindow");
            ew.element.find("button").kendoButton({
                click: function () {
                    if (callback && $.isFunction(callback)) {
                        callback.call();
                    }
                    ew.close();
                }
            });
            ew.center();
            ew.open();
        },
        Confirm: function (message, title, done, fail) {
            var title = title || "Confirm";
            var win = $("#confirm").data("kendoDialog");
            if (win)
                win.destroy();
            $("<div id='confirm'></div>").appendTo($("#placeholder"));
            win = $("#confirm").kendoDialog({
                title: title,
                content: message,
                actions: [{
                        text: "OK",
                        action: function (e) {
                            if ($.isFunction(done))
                                done.call();
                            return true;
                        },
                        primary: true
                    }, {
                        text: "Cancel",
                        action: function (e) {
                            if ($.isFunction(fail))
                                fail.call();
                            return true;
                        }
                    }]
            }).data("kendoDialog");
            win.open();
        },
        Alert: function (message, title, done) {
            var title = title || "Alert";
            var win = $("#alert").data("kendoDialog");
            if (win)
                win.destroy();
            $("<div id='alert'></div>").appendTo($("#placeholder"));
            win = $("#alert").kendoDialog({
                title: title,
                content: '<blockquote>' + message + '</blockquote>',
                actions: [{
                        text: "OK",
                        action: function (e) {
                            if ($.isFunction(done))
                                done.call();
                            return true;
                        },
                        primary: true
                    }]
            }).data("kendoDialog");
            win.open();
        },

        modelProperty: {

            showInput: function (what, field, value) {
                if (field === "note" || field === "notes" || ($.type(value) === "string" && -1 !== value.indexOf("\n")))
                    return '<textarea name="' + what + '.' + field.replace(/[\ -]/g, "_") + '" data-bind="value:' + field.replace(/[\ -]/g, "_") + '"></textarea>';
                return '<input class="k-textbox" name="' + what + '.' + field.replace(/[\ -]/g, "_") + '" data-bind="value:' + field.replace(/[\ -]/g, "_") + '" value="' + value + '">';
            },

            show: function (what, container, model, readonly, order) {
//                console.log(model);
                var table = container.find("table#" + what);
                table.empty();
                var fields = model[what] ? JSON.parse(model[what]) : {};
                var item = new kendo.data.ObservableObject(fields);
                var t = "<tr><th><label for='#=what#.#=field#'>#=field#</label></th><td>#=input#</td></tr>";

                if (!order)
                    order = Object.keys(fields);

                for (var x = 0; x < order.length; x++) {
                    var input, field = order[x];
                    var template = kendo.template(t);
                    //console.log(what, fields[field], $.isPlainObject(fields[field]));
                    if ($.isPlainObject(fields[field])) {
                        input = "<table class='tableEdit'>";
                        for (var _field in fields[field]) {
                            input += template({
                                what: what,
                                field: field + "." + _field.replace(/[\ -]/g, "_"),
                                input: Application.modelProperty.showInput(what, field + "." + _field.replace(/[\ -]/g, "_"), fields[field][_field.replace(/[\ -]/g, "_")])
                            });
                        }
                        input += "</table>";
                    } else {
                        input = Application.modelProperty.showInput(what, field, fields[field]);
                    }
                    table.append(template({
                        what: what,
                        field: field,
                        input: input
                    }));
                }
                kendo.unbind(table);
                kendo.bind(table, item);
                item.bind("change", function (e) {
                    model.set(what, kendo.stringify(item.toJSON()));
                });
                if (!!readonly)
                    table.find("[name]").attr("readonly", "readonly");
            }
        },

        tabstrip: function (selector, container, options) {
            return $(selector, container).kendoTabStrip(options).data("kendoTabStrip");
        }

    };

    /**
     * Initialize
     */
    Application.window = window;
    Application.service.url = window.location.protocol + "//" + window.location.host;
    Application.service.menu = Application.service.url + "/app/Application.menu.js";
    Application.dataType = "json";
    Application.ui.skin = "blueopal";
    Application.ui.wallpaper = "";
    Application.ui.culture = "en-GB";
    Application.ui.language = "en";
    Application.ui.backgroundColor = "#ccc";

    function resizeGrid(gridElement) {
        if (!gridElement)
            return;
        var dataArea = gridElement.find(".k-grid-content"),
                gridHeight = gridElement.innerHeight(),
                otherElements = gridElement.children().not(".k-grid-content"),
                otherElementsHeight = 0;
        otherElements.each(function () {
            otherElementsHeight += $(this).outerHeight();
        });
        dataArea.height(gridHeight - otherElementsHeight);
    }

    $(window).resize(function () {
        $("[id^=window-]").each(function () {
            var wnd = $(this).getKendoWindow();
            if (wnd && $(this).is(":visible")) {
                wnd.trigger("resize");
                var grd = wnd.element.find(".k-grid");
                grd.each(function () {
                    resizeGrid($(this));
                });
            }
        });
    });

    $(window).keypress(function (e) {
        if (e.key === "F11") {
            $(window).trigger("resize");
        }
    });

    /**
     * Attach Application to window
     */
    extend(window, {
        app: function () {
            return Application;
        },
        controller: function (name, callback) {
            var path = "admin/" + name.replace(/_/g, '-');
            return Application.getController({
                name: name,
                path: path
            }, callback, false);
        }
    });

})(jQuery, window);