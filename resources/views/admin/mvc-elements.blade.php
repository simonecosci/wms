<div id="window-{{ $controllerName }}">
    <div id="grid-{{ $controllerName }}"></div>
    <div id="view-{{ $controllerName }}"></div>
</div>
<div style="display: none" id="editInputChooser-template-{{ $controllerName }}" type="x-kendo/template">
    <table class="tableEdit">
        <tr>
            <th>
                <label for="controller">Controller</label>
            </th>
            <td>
                <input placeholder="eg. users" type="text" class="k-textbox" name="controller" required="required" data-bind="value:controller">
            </td>
        </tr>
        <tr>
            <th>
                <label for="path">Path</label>
            </th>
            <td>
                <input placeholder="eg. admin/users" type="text" class="k-textbox" name="path" required="required" data-bind="value:path">
            </td>
        </tr>
        <tr>
            <th>
                <label for="display">Display fields</label>
            </th>
            <td>
                <input placeholder="eg. name, surname, email" type="text" class="k-textbox" name="display" required="required" data-bind="value:display">
            </td>
        </tr>
    </table>
</div>

<div style="display: none" id="editInputSelect-template-{{ $controllerName }}" type="x-kendo/template">
    <table class="tableEdit">
        <tr>
            <th>
                <label for="controller">Controller</label>
            </th>
            <td>
                <input placeholder="eg. products" type="text" class="k-textbox" name="controller" required="required" data-bind="value:controller">
            </td>
        </tr>
        <tr>
            <th>
                <label for="path">Path</label>
            </th>
            <td>
                <input placeholder="eg. admin/products" type="text" class="k-textbox" name="path" required="required" data-bind="value:path">
            </td>
        </tr>
        <tr>
            <th>
                <label for="textField">Field to Show</label>
            </th>
            <td>
                <input placeholder="eg. name" type="text" class="k-textbox" name="textField" required="required" data-bind="value:textField">
            </td>
        </tr>
        <tr>
            <th>
                <label for="valueField">Value Field</label>
            </th>
            <td>
                <input placeholder="eg. id" type="text" class="k-textbox" name="valueField" required="required" data-bind="value:valueField">
            </td>
        </tr>
    </table>
</div>

<div style="display: none" id="editFields-popup-template-{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
        <table class="tableEdit">
            <tr>
                <th>
                    <label for="name">Field Name</label>
                </th>
                <td>
                    <input placeholder="eg. name" type="text" class="k-textbox" name="name" required="required" data-bind="value:name">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="label">Form Label</label>
                </th>
                <td>
                    <input placeholder="eg. Name" type="text" class="k-textbox" name="label" required="required" data-bind="value:label">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="dbtype">DB Type</label>
                </th>
                <td>
                    <select name="dbtype" data-bind="value:dbtype" required></select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="length">Length</label>
                </th>
                <td>
                    <input type="text" name="length" data-bind="value:length">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="decimals">Decimals</label>
                </th>
                <td>
                    <input type="text" name="decimals" data-bind="value:decimals">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="vartype">Var Type</label>
                </th>
                <td>
                    <select name="vartype" data-bind="value:vartype" required></select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="input">Input</label>
                </th>
                <td>
                    <select name="input" data-bind="value:input"></select>
                    <div id="input-options"></div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="fillable">Fillable</label>
                </th>
                <td>
                    <input type="checkbox" name="fillable" data-bind="checked:fillable">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="required">Required</label>
                </th>
                <td>
                    <input type="checkbox" name="required" data-bind="checked:required">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="nullable">Nullable</label>
                </th>
                <td>
                    <input type="checkbox" name="nullable" data-bind="checked:nullable">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="unique">Unique</label>
                </th>
                <td>
                    <input type="checkbox" name="unique" data-bind="checked:unique">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="index">Index</label>
                </th>
                <td>
                    <input type="checkbox" name="index" data-bind="checked:index">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="primary">Primary</label>
                </th>
                <td>
                    <input type="checkbox" name="primary" data-bind="checked:primary">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="autoincrement">Autoincrement</label>
                </th>
                <td>
                    <input type="checkbox" name="autoincrement" data-bind="checked:autoincrement">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="default">Default (surround with "" if is a string)</label>
                </th>
                <td>
                    <input type="text" class="k-textbox" name="default" data-bind="value:default">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="validators">Validators (Separate'em with | (Pipe))</label>
                </th>
                <td>
                    <input type="text" class="k-textbox" name="validators" data-bind="value:validators">
                </td>
            </tr>
        </table>
    </div>
</div>

<div style="display: none" id="editRelations-popup-template-{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
        <table class="tableEdit">
            <tr>
                <th>
                    <label for="foreign">Field Name</label>
                </th>
                <td>
                    <input placeholder="eg. product_id" type="text" name="foreign" required="required" data-bind="value:foreign">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="on">On Table</label>
                </th>
                <td>
                    <input placeholder="eg. products" type="text" name="on" required="required" data-bind="on">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="references">Reference Field</label>
                </th>
                <td>
                    <input placeholder="eg. id" type="text" name="references" required="required" data-bind="references">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="onDelete">On Delete</label>
                </th>
                <td>
                    <input placeholder="eg. cascade" type="text" name="onDelete" required="required" data-bind="onDelete">
                </td>
            </tr>
        </table>
    </div>
</div>

<div style="display: none" id="editNested-popup-template-{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
        <table class="tableEdit">
            <tr>
                <th>
                    <label for="controller">Controller</label>
                </th>
                <td>
                    <input placeholder="eg. orders-products" type="text" name="controller" required="required" data-bind="value:controller">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="foreign">Foreign key</label>
                </th>
                <td>
                    <input placeholder="eg. order_id" type="text" name="foreign" required="required" data-bind="value:foreign">
                </td>
            </tr>
        </table>
    </div>
</div>

<div style="display: none" id="editColumns-popup-template-{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
        <table class="tableEdit">
            <tr>
                <th>
                    <label for="title">Title</label>
                </th>
                <td>
                    <input type="text" class="k-textbox" name="title" required="required" data-bind="value:title">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="field">Field</label>
                </th>
                <td>
                    <input type="text" name="field" required="required" data-bind="value:field">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="width">Width</label>
                </th>
                <td>
                    <input type="text" class="k-textbox" name="width" data-bind="value:width">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="template">Template</label>
                </th>
                <td>
                    <input type="text" class="k-textbox" name="template" data-bind="value:template">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="sortable">Sortable</label>
                </th>
                <td>
                    <input type="checkbox" name="sortable" data-bind="checked:sortable">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="filterable">Filterable</label>
                </th>
                <td>
                    <input type="checkbox" name="filterable" data-bind="checked:filterable">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="columnMenu">Menu</label>
                </th>
                <td>
                    <input type="checkbox" name="columnMenu" data-bind="checked:columnMenu">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="groupable">Groupable</label>
                </th>
                <td>
                    <input type="checkbox" name="groupable" data-bind="checked:groupable">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="resizable">Resizable</label>
                </th>
                <td>
                    <input type="checkbox" name="resizable" data-bind="checked:resizable">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="hidden">Hidden</label>
                </th>
                <td>
                    <input type="checkbox" name="hidden" data-bind="checked:hidden">
                </td>
            </tr>
        </table>
    </div>
</div>

<div style="display: none" id="editForm-popup-template-{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
        <div id="tabstrip" class="tabstrip">
            <ul>
                <li class="k-state-active">General</li>
                <li>Data Model</li>
                <li>Relations</li>
                <li>Grid</li>
                <li>Nested</li>
                <li>Operations</li>
            </ul>
            <div>
                <table class="tableEdit">
                    <tr>
                        <th>
                            <label for="view_pageable">Pageable (more then 500 records ?)</label>
                        </th>
                        <td>
                            <input type="checkbox" name="view_pageable" data-bind="checked:view.pageable">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="view_pageSize">Page Size</label>
                        </th>
                        <td>
                            <input type="text" name="view_pageSize" data-bind="value:view.pageSize">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="view_windowed">Has a window</label>
                        </th>
                        <td>
                            <input onclick="$(this).next().slideToggle(this.checked);" type="checkbox" name="view_windowed" data-bind="checked:view.windowed">
                            <div data-bind="style: {display: view.wiwndowed}">
                                <table class="tableEdit">
                                    <tr>
                                        <th>
                                            <label for="view_window_title">Title</label>
                                        </th>
                                        <td>
                                            <input placeholder="eg. My Items" type="text" class="k-textbox" name="view_window_title" data-bind="value:view.window.title">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="view_window_width">Width (pt,px,%,default:auto)</label>
                                        </th>
                                        <td>
                                            <input type="text" class="k-textbox" name="view_window_width" data-bind="value:view.window.width">
                                        </td>
                                    </tr>
                                    <tr>
                                        <tr>
                                            <th>
                                                <label for="view_window_height">Height (pt,px,%,default:auto)</label>
                                            </th>
                                            <td>
                                                <input type="text" class="k-textbox" name="view_window_height" data-bind="value:view.window.height">
                                            </td>
                                        </tr>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="view_table">Database table name</label>
                        </th>
                        <td>
                            <input placeholder="eg. MyItems" type="text" class="k-textbox" name="view_table" required="required" data-bind="value:model.table">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="name">JS Controller Name (dashed lower case)</label>
                        </th>
                        <td>
                            <input placeholder="eg. my-items" type="text" class="k-textbox" name="name" required="required" data-bind="value:name">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="view_name">View Name (dashed lower case)</label>
                        </th>
                        <td>
                            <input placeholder="eg. my-items" type="text" class="k-textbox" name="view_name" required="required" data-bind="value:view.name">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="controller_name.name">Controller Name</label>
                        </th>
                        <td>
                            <input placeholder="eg. MyItemsController" type="text" class="k-textbox" name="controller_name" required="required" data-bind="value:controller.name">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="model_name">Model Name</label>
                        </th>
                        <td>
                            <input placeholder="eg. MyItem" type="text" class="k-textbox" name="model_name" required="required" data-bind="value:model.name">
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="tableEdit">
                    <tr>
                        <td colspan="2">
                            <input type="checkbox" name="model_timestamps" data-bind="checked:model.timestamps"> append created_at, updated_at columns as TIMESTAMP
                        </td>
                    </tr>
                </table>
                <h2>Fields</h2>
                <div id="grid-fields"></div>
            </div>
            <div>
                <h2>Relations</h2>
                <div id="grid-relations"></div>
            </div>
            <div>
                <h2>Grid Behavior</h2>
                <table class="tableEdit">
                    <tr>
                        <td>
                            <input type="checkbox" name="view_grid_destroyOnClose" data-bind="checked:view.grid.destroyOnClose"> Destroy controller on window close
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="view_grid_reopenOnSave" data-bind="checked:view.grid.reopenOnSave"> Reopen edit window after create
                        </td>
                    </tr>
                </table>
                <h2>Grid Columns</h2>
                <div id="grid-columns"></div>
            </div>
            <div>
                <div>
                    <h2>Nested controllers</h2>
                    <div id="grid-nested"></div>
                </div>
            </div>
            <div>
                <style>
                    .actions div {
                        padding: 5px;
                        border-bottom: 1px solid silver; 
                        margin-bottom: 10px;
                    }
                    .actions button {
                        width: 100%;
                    }
                    .actions label {
                        display: block;
                        margin-bottom: 5px;
                    }
                    .actions \#result {
                        border: 1px solid silver; 
                        height: 450px; 
                        width: 100%; 
                        overflow: auto; 
                        font-family: monospace, courier;
                        font-size: 150%;
                    }
                </style>
                <table class="actions">
                    <tr>
                        <td valign="top">
                            <div>
                                <label for="createMigration">Create The Migration Code</label>
                                <button type="button" id="createMigration">Create Migration</button>
                                <button type="button" id="runMigration" style="display:none">Run Migration</button>
                            </div>
                            <div>
                                <label for="createModel">Create The Model Code</label>
                                <button type="button" id="createModel">Create Model</button>
                            </div>
                            <div>
                                <label for="createController">Create The Controller Code</label>
                                <button type="button" id="createController">Create Controller</button>
                            </div>
                            <div>
                                <label for="createView">Create The View Code</label>
                                <button type="button" id="createView">Create View</button>
                            </div>
                        </td>
                        <td>
                            <pre id="result"></pre>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script id="script-{{ $controllerName }}">
    app().controllers['{{ $controllerName }}'].run = function () {
        this.name = '{{ $controllerName }}';
        this.pageable = false;
        this.acl.view = false;
        var $ctrl = this;
        var fields = {
            id: {
                type: "number",
                editable: false,
                nullable: true
            },
            name: {
                type: "string",
                validation: {
                    required: true
                }
            },
            model: {
                defaultValue: {
                    name: "",
                    table: "",
                    fields: [],
                    relations: [],
                    timestamps: true
                }
            },
            view: {
                defaultValue: {
                    windowed: true,
                    window: {
                        title: "",
                        width: "",
                        height: ""
                    },
                    grid: {
                        destroyOnClose: true,
                        reopenOnSave: false
                    },
                    name: "",
                    columns: [],
                    pageable: false,
                    pageSize: 50
                }
            },
            controller: {
                defaultValue: {
                    name: "",
                    nested: []
                }
            }
        };

        var columns = [{
                field: "id",
                title: "ID",
                width: 200,
                hidden: true,
                filterable: {
                    ui: function (element) {
                        element.kendoNumericTextBox({
                            format: "n0",
                            decimals: 0,
                            step: 1
                        });
                    },
                    cell: {
                        template: function (args) {
                            args.element.kendoNumericTextBox({
                                format: "n0",
                                decimals: 0,
                                step: 1
                            });
                        }
                    }
                }
            }, {
                field: "name",
                title: "Name"
            }];

        this.createUI({
            title: "Mvc Elements",
            fields: fields,
            columns: columns,
            grid: {
                editable: {
                    window: {
                        width: "70%",
                        height: 600
                    }
                }
            },
            transport: {
                parameterMap: function (options, type) {
                    if (type !== "read") {
                        var data = [options];
                        if (options.models)
                            data = options.models;
                        if (type !== "destroy") {
                            var fields = $ctrl.grid.editable.element.find("#grid-fields")
                                    .data("kendoGrid").dataSource
                                    .data().toJSON();
                            var relations = $ctrl.grid.editable.element.find("#grid-relations")
                                    .data("kendoGrid").dataSource
                                    .data().toJSON();
                            var columns = $ctrl.grid.editable.element.find("#grid-columns")
                                    .data("kendoGrid").dataSource
                                    .data().toJSON();
                            data[0].model.fields = fields;
                            data[0].model.relations = relations;
                            data[0].view.columns = columns;
                        }
                        return {models: kendo.stringify(data)};
                    }
                    return $ctrl.pageable ? {
                        data: options.data,
                        page: options.page,
                        filter: kendo.stringify(options.filter),
                        sort: kendo.stringify(options.sort),
                        pageSize: options.pageSize
                    } : options;
                }
            },
            dataSourceOptions: {
                change: function (e) {
                    if (e.field === "name") {
                        e.items[0].view.set("name", e.items[0].name);

                        var controllerName = e.items[0].name.replace(/-([a-z])/g, function (g) {
                            return g[1].toUpperCase();
                        });
                        controllerName = controllerName.charAt(0).toUpperCase() + controllerName.substr(1);
                        e.items[0].controller.set("name", controllerName + "Controller");

                        var modelName = controllerName.substr(-3, 3) === "ies" ? controllerName.substr(0, controllerName.length - 3) + "y" : controllerName.substr(0, controllerName.length - 1);
                        e.items[0].model.set("name", modelName);
                    }
                }
            },
            onView: function (container, model) {
                app().tabstrip(".tabstrip", container);
                app().createWidgets($ctrl.name, container, model);
            },
            onEdit: function (container, model) {
                model.dirty = true;
                app().tabstrip(".tabstrip", container);
                
                container.find("[name=view_pageSize]").kendoNumericTextBox({
                    min: 0,
                    format: "n0"
                });
                
                container.find("#createMigration").kendoButton({
                    click: function() {
                        if (!confirm("Create the migration code ?")) {
                            return;
                        }
                        $.ajax({
                            url: app().service.url + "/" + $ctrl.path + "/create-migration",
                            dataType: "text",
                            type: "POST",
                            data: { model: kendo.stringify(model.toJSON()) },
                            success: function (code) {
                                app().Alert("Migration created and placed in\ndatabase/migrations/[date]_create_" + model.model.table + "_table.php<br>You can now run the migrate command");
                                container.find("#result").text('<' + "?php\n" + code);
                                container.find("#runMigration").show();
                            },
                            error: function (xhr, status, msg) {
                                app().Warning(xhr.responseText, msg);
                                console.log(xhr, status, msg);
                            }
                        });
                    }
                });
                container.find("#runMigration").kendoButton({
                    click: function() {
                        if (!confirm("Run the migration code ?")) {
                            return;
                        }
                        $.ajax({
                            url: app().service.url + "/" + $ctrl.path + "/run-migration",
                            dataType: "text",
                            success: function () {
                                app().Alert("Migration done");
                            },
                            error: function (xhr, status, msg) {
                                app().Warning(xhr.responseText, msg);
                                console.log(xhr, status, msg);
                            }
                        });
                    }
                });                
                container.find("#createModel").kendoButton({
                    click: function() {
                        if (!confirm("Create the Model code ?")) {
                            return;
                        }
                        $.ajax({
                            url: app().service.url + "/" + $ctrl.path + "/create-model",
                            dataType: "text",
                            type: "POST",
                            data: { model: kendo.stringify(model.toJSON()) },
                            success: function (code) {
                                app().Alert("Model created and placed in\napp/Models/" + model.model.name + ".php");
                                container.find("#result").text('<' + "?php\n" + code);
                            },
                            error: function (xhr, status, msg) {
                                app().Warning(xhr.responseText, msg);
                                console.log(xhr, status, msg);
                            }
                        });
                    }
                });
                
                container.find("#createController").kendoButton({
                    click: function() {
                        if (!confirm("Create the Controller code ?")) {
                            return;
                        }
                        $.ajax({
                            url: app().service.url + "/" + $ctrl.path + "/create-controller",
                            dataType: "text",
                            type: "POST",
                            data: { model: kendo.stringify(model.toJSON()) },
                            success: function (code) {
                                app().Alert("Controller created and placed in\napp/Http/Controllers/Admin/" + model.controller.name + ".php");
                                container.find("#result").text('<' + "?php\n" + code);
                            },
                            error: function (xhr, status, msg) {
                                app().Warning(xhr.responseText, msg);
                                console.log(xhr, status, msg);
                            }
                        });
                    }
                });
                
                container.find("#createView").kendoButton({
                    click: function() {
                        if (!confirm("Create the View code ?")) {
                            return;
                        }
                        $.ajax({
                            url: app().service.url + "/" + $ctrl.path + "/create-view",
                            dataType: "text",
                            type: "POST",
                            data: { model: kendo.stringify(model.toJSON()) },
                            success: function (code) {
                                app().Alert("View created and placed in\resources/views/admin/" + model.view.name + ".blade.php");
                                container.find("#result").text(code);
                            },
                            error: function (xhr, status, msg) {
                                app().Warning(xhr.responseText, msg);
                                console.log(xhr, status, msg);
                            }
                        });
                    }
                });
                
                var fieldsDataSource = new kendo.data.DataSource({
                    data: model.model.fields,
                    transport: {
                        read: function (options) {
                            options.success(model.model.fields);
                        },
                        create: function (options) {
                            var items = fieldsDataSource.data().toJSON();
                            model.model.set("fields", items);
                            options.success(options.data);
                        },
                        update: function (options) {
                            var items = fieldsDataSource.data().toJSON();
                            model.model.set("fields", items);
                            options.success(options.data);
                        },
                        destroy: function (options) {
                            var items = fieldsDataSource.data().toJSON();
                            model.model.set("fields", items);
                            options.success(options.data);
                        }
                    },
                    schema: {
                        model: {
                            id: "name",
                            fields: {
                                name: {
                                    validation: {
                                        required: true
                                    }
                                },
                                label: {
                                    validation: {
                                        required: true
                                    }
                                },
                                vartype: {
                                    defaultValue: "string",
                                    validation: {
                                        required: true
                                    }
                                },
                                dbtype: {
                                    defaultValue: "string",
                                    validation: {
                                        required: true
                                    }
                                },
                                length: {
                                    type: "numeric",
                                    defaultValue: 0,
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                },
                                decimals: {
                                    type: "numeric",
                                    defaultValue: 0,
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                },
                                input: {
                                    defaultValue: "text",
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                },
                                inputOptions: {
                                    defaultValue: null,
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                },
                                fillable: {
                                    type: "boolean",
                                    defaultValue: true
                                },
                                required: {
                                    type: "boolean",
                                    defaultValue: false
                                },
                                nullable: {
                                    type: "boolean",
                                    defaultValue: false
                                },
                                unique: {
                                    type: "boolean",
                                    defaultValue: false
                                },
                                index: {
                                    type: "boolean",
                                    defaultValue: false
                                },
                                primary: {
                                    type: "boolean",
                                    defaultValue: false
                                },
                                autoincrement: {
                                    type: "boolean",
                                    defaultValue: false
                                },
                                default: {
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                },
                                validators: {
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                }
                            }
                        }
                    }
                });
                var relationsDataSource = new kendo.data.DataSource({
                    data: model.model.relations,
                    transport: {
                        read: function (options) {
                            options.success(model.model.relations);
                        },
                        create: function (options) {
                            var items = relationsDataSource.data().toJSON();
                            model.model.set("relations", items);
                            options.success(options.data);
                        },
                        update: function (options) {
                            var items = relationsDataSource.data().toJSON();
                            model.model.set("relations", items);
                            options.success(options.data);
                        },
                        destroy: function (options) {
                            var items = relationsDataSource.data().toJSON();
                            model.model.set("relations", items);
                            options.success(options.data);
                        }
                    },
                    schema: {
                        model: {
                            id: "foreign",
                            fields: {
                                foreign: {
                                    validation: {
                                        required: true
                                    }
                                },
                                references: {
                                    defaultValue: "id",
                                    validation: {
                                        required: true
                                    }
                                },
                                on: {
                                    validation: {
                                        required: true
                                    }
                                },
                                onDelete: {
                                    defaultValue: "cascade",
                                    validation: {
                                        required: true
                                    }
                                }
                            }
                        }
                    }
                });
                var columnsDataSource = new kendo.data.DataSource({
                    data: model.view.columns,
                    transport: {
                        read: function (options) {
                            options.success(model.view.columns);
                        },
                        create: function (options) {
                            var items = columnsDataSource.data().toJSON();
                            model.view.set("columns", items);
                            options.success(options.data);
                        },
                        update: function (options) {
                            var items = columnsDataSource.data().toJSON();
                            model.view.set("columns", items);
                            options.success(options.data);
                        },
                        destroy: function (options) {
                            var items = columnsDataSource.data().toJSON();
                            model.view.set("columns", items);
                            options.success(options.data);
                        }
                    },
                    schema: {
                        model: {
                            id: "title",
                            fields: {
                                title: {
                                    validation: {
                                        required: true
                                    }
                                },
                                field: {
                                    validation: {
                                        required: true
                                    }
                                },
                                width: {
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                },
                                template: {
                                    validation: {
                                        required: false,
                                        nullable: true
                                    }
                                },
                                sortable: {
                                    type: "boolean",
                                    defaultValue: true
                                },
                                filterable: {
                                    type: "boolean",
                                    defaultValue: true
                                },
                                columnMenu: {
                                    type: "boolean",
                                    defaultValue: true
                                },
                                groupable: {
                                    type: "boolean",
                                    defaultValue: true
                                },
                                resizable: {
                                    type: "boolean",
                                    defaultValue: true
                                },
                                hidden: {
                                    type: "boolean",
                                    defaultValue: false
                                }
                            }
                        }
                    }
                });
                var nestedDataSource = new kendo.data.DataSource({
                    data: model.controller.nested,
                    transport: {
                        read: function (options) {
                            options.success(model.controller.nested);
                        },
                        create: function (options) {
                            var items = nestedDataSource.data().toJSON();
                            model.controller.set("nested", items);
                            options.success(options.data);
                        },
                        update: function (options) {
                            var items = nestedDataSource.data().toJSON();
                            model.controller.set("nested", items);
                            options.success(options.data);
                        },
                        destroy: function (options) {
                            var items = nestedDataSource.data().toJSON();
                            model.controller.set("nested", items);
                            options.success(options.data);
                        }
                    },
                    schema: {
                        model: {
                            id: "controller",
                            fields: {
                                controller: {
                                    validation: {
                                        required: true
                                    }
                                },
                                foreign: {
                                    validation: {
                                        required: true
                                    }
                                }
                            }
                        }
                    }
                });
                
                if (model.isNew()) {
                    fieldsDataSource.add({
                        name: "id",
                        label: "Id",
                        vartype: "number",
                        dbtype: "unsignedInteger",
                        length: null,
                        decimals: null,
                        input: null,
                        inputOptions: null,
                        fillable: false,
                        required: false,
                        nullable: true,
                        unique: true,
                        index: true,
                        primary: true,
                        default: null,
                        autoincrement: true
                    });
                    columnsDataSource.add({
                        title: "Id",
                        field: "id",
                        width: null,
                        template: null,
                        sortable: true,
                        filterable: true,
                        columnMenu: true,
                        groupable: false,
                        resizable: true,
                        hidden: true
                    });
                }
                container.find("#grid-fields").kendoGrid({
                    dataSource: fieldsDataSource,
                    toolbar: ["create"],
                    height: 350,
                    scrollable: true,
                    columns: [{
                            field: "name",
                            title: "Name"
                        }, {
                            field: "label",
                            title: "Label",
                        }, {
                            field: "vartype",
                            title: "Var Type",
                        }, {
                            field: "dbtype",
                            title: "DB Type",
                        }, {
                            field: "input",
                            title: "Input",
                        }, {
                            field: "fillable",
                            title: "Fillable"
                        }, {
                            field: "required",
                            title: "Required"
                        }, {
                            field: "nullable",
                            title: "Nullable"
                        }, {
                            field: "primary",
                            title: "Primary"
                        }, {
                            field: "autoincrement",
                            title: "Autoincrement"
                        }, {
                            command: ["edit", "destroy"],
                            width: 160
                        }],
                    batch: true,
                    edit: function (e) {
                        var ddInput = e.container.find("[name=input]")
                                .kendoDropDownList({
                                    valuePrimitive: true,
                                    dataSource: {
                                        data: ["text", "textarea", "editor", "date", "datetime", "number", "checkbox", "select", "chooser", "hidden"]
                                    },
                                    change: function (ev) {
                                        var input = this.value();
                                        var opts = e.model.inputOptions;
                                        switch (input) {
                                            case "select":
                                                e.model.set("inputOptions", {
                                                    controller: opts && opts.controller ? opts.controller : "",
                                                    path: opts && opts.path ? opts.path : "",
                                                    textField: opts && opts.textField ? opts.textField : "",
                                                    valueField: opts && opts.valueField ? opts.valueField : ""
                                                });
                                                var form = $("#editInputSelect-template-" + $ctrl.name).html();
                                                e.container.find("#input-options").html(form);
                                                kendo.bind(e.container.find("#input-options"), e.model.inputOptions);
                                                break;
                                                
                                            case "chooser":
                                                e.model.set("inputOptions", {
                                                    controller: opts && opts.controller ? opts.controller : "",
                                                    path: opts && opts.path ? opts.path : "",
                                                    display: opts && opts.display ? opts.display : ""
                                                });
                                                var form = $("#editInputChooser-template-" + $ctrl.name).html();
                                                e.container.find("#input-options").html(form);
                                                kendo.bind(e.container.find("#input-options"), e.model.inputOptions);
                                                break;
                                                
                                            default: 
                                                e.container.find("#input-options").empty();
                                                e.model.set("inputOptions", null);
                                                break;
                                        }
                                    }
                                }).data("kendoDropDownList");

                        e.container.find("[name=vartype]")
                                .kendoDropDownList({
                                    valuePrimitive: true,
                                    dataSource: {
                                        data: ["string", "number", "date", "boolean"]
                                    }
                                });
                                
                        e.container.find("[name=dbtype]")
                                .kendoDropDownList({
                                    valuePrimitive: true,
                                    dataSource: {
                                        data: [
                                            "bigInteger",
                                            "binary",
                                            "boolean",
                                            "char",
                                            "date",
                                            "dateTime",
                                            "dateTimeTz",
                                            "decimal",
                                            "double",
                                            "float",
                                            "integer",
                                            "json",
                                            "longText",
                                            "mediumInteger",
                                            "mediumText",
                                            "point",
                                            "smallInteger",
                                            "string",
                                            "text",
                                            "time",
                                            "timeTz",
                                            "timestamp",
                                            "timestampTz",
                                            "tinyInteger",
                                            "unsignedInteger"
                                        ]
                                    }
                                });
                                
                        e.container.find("[name=length],[name=decimals]").kendoNumericTextBox({
                            format: "n0"
                        });
                        
                        ddInput.trigger("change");
                    },
                    editable: {
                        mode: "popup",
                        template: $("#editFields-popup-template-" + $ctrl.name).html()
                    }
                });
                
                container.find("#grid-relations").kendoGrid({
                    dataSource: relationsDataSource,
                    toolbar: ["create"],
                    height: 350,
                    scrollable: true,
                    columns: [{
                            field: "foreign",
                            title: "Foreign key"
                        }, {
                            field: "references",
                            title: "Refernce Field",
                        }, {
                            field: "on",
                            title: "On Table",
                        }, {
                            field: "onDelete",
                            title: "On Delete"
                        }, {
                            command: ["edit", "destroy"],
                            width: 160
                        }],
                    batch: true,
                    edit: function (e) {
                        var fields = [];
                        for (var i = 0; i < model.model.fields.length; i++) {
                            fields.push(model.model.fields[i].name);
                        }
                        var tables = [];
                        var items = $ctrl.dataSource.data();
                        for (var i = 0; i < items.length; i++) {
                            tables.push(items[i].model.table);
                        }
                        e.container.find("[name=foreign]").kendoComboBox({
                            dataSource: fields
                        });
                        e.container.find("[name=references]").kendoComboBox({
                            dataSource: []
                        });
                        e.container.find("[name=on]").kendoComboBox({
                            dataSource: tables,
                            change: function() {
                                var table = this.value();
                                if (tables.indexOf(table) === -1)
                                    return;
                                for (var i = 0; i < items.length; i++) {
                                    if (items[i].model.table === table) {
                                        var referencefields = [];
                                        for (var c = 0; c < items[i].model.fields.length; c++) {
                                            referencefields.push(items[i].model.fields[c].name);
                                        }
                                        e.container.find("[name=references]")
                                                .data("kendoComboBox")
                                                .setDataSource(referencefields);
                                    }
                                }
                            }
                        }).data("kendoComboBox").trigger("change");
                        e.container.find("[name=onDelete]").kendoComboBox({
                            dataSource: ["cascade", "restrict", "set null"]
                        });
                        
                    },
                    editable: {
                        mode: "popup",
                        template: $("#editRelations-popup-template-" + $ctrl.name).html()
                    }
                });
                
                container.find("#grid-nested").kendoGrid({
                    dataSource: nestedDataSource,
                    toolbar: ["create"],
                    height: 350,
                    scrollable: true,
                    columns: [{
                            field: "controller",
                            title: "Controller"
                        }, {
                            field: "foreign",
                            title: "Foreign key"
                        }, {
                            command: ["edit", "destroy"],
                            width: 160
                        }],
                    batch: true,
                    edit: function (e) {
                        var tables = [];
                        var items = $ctrl.dataSource.data();
                        for (var i = 0; i < items.length; i++) {
                            tables.push(items[i].name);
                        }
                        e.container.find("[name=foreign]").kendoComboBox({
                            dataSource: []
                        });
                        e.container.find("[name=controller]").kendoComboBox({
                            dataSource: tables,
                            change: function () {
                                var controller = this.value();
                                var fields = [];
                                var items = $ctrl.dataSource.data();
                                for (var i = 0; i < items.length; i++) {
                                    if (items[i].name !== controller)
                                        continue;
                                    for (var c = 0; c < items[i].model.fields.length; c++) {
                                        fields.push(items[i].model.fields[c].name);
                                    }
                                }
                                e.container.find("[name=foreign]")
                                        .data("kendoComboBox")
                                        .setDataSource(fields);
                            }
                        }).data("kendoComboBox").trigger("change");
                    },
                    editable: {
                        mode: "popup",
                        template: $("#editNested-popup-template-" + $ctrl.name).html()
                    }
                });
                
                container.find("#grid-columns").kendoGrid({
                    dataSource: columnsDataSource,
                    toolbar: ["create"],
                    height: 350,
                    scrollable: true,
                    columns: [{
                            field: "title",
                            title: "Title"
                        }, {
                            field: "field",
                            title: "Field",
                        }, {
                            field: "width",
                            title: "Width",
                        }, {
                            field: "template",
                            title: "Template"
                        }, {
                            field: "sortable",
                            title: "Sortable"
                        }, {
                            field: "filterable",
                            title: "Filterable"
                        }, {
                            field: "columnMenu",
                            title: "Menu"
                        }, {
                            field: "groupable",
                            title: "Groupable"
                        }, {
                            field: "resizable",
                            title: "Resizable"
                        }, {
                            field: "hidden",
                            title: "Hidden"
                        }, {
                            command: ["edit", "destroy"],
                            width: 160
                        }],
                    batch: true,
                    edit: function (e) {
                        var fields = [];
                        for (var i = 0; i < model.model.fields.length; i++) {
                            fields.push(model.model.fields[i].name);
                        }
                        e.container.find("[name=field]").kendoComboBox({
                            dataSource: fields
                        });
                    },
                    editable: {
                        mode: "popup",
                        template: $("#editColumns-popup-template-" + $ctrl.name).html()
                    }
                });
            }
        });

    };
</script>