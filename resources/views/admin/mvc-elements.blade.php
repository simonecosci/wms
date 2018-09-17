<div id="window-{{ $controllerName }}">
    <div id="grid-{{ $controllerName }}"></div>
    <div id="view-{{ $controllerName }}"></div>
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
                    <label for="type">Type</label>
                </th>
                <td>
                    <select name="type" data-bind="value:type" required></select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="input">Input</label>
                </th>
                <td>
                    <select name="input" data-bind="value:input" required></select>
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
            </ul>
            <div>
                <table class="tableEdit">
                    <tr>
                        <th>
                            <label for="windowed">Has a window</label>
                        </th>
                        <td>
                            <input type="checkbox" name="view.windowed" data-bind="checked:view.windowed">
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
                            <label for="view.name">View Name (dashed lower case)</label>
                        </th>
                        <td>
                            <input placeholder="eg. my-items" type="text" class="k-textbox" name="view.name" required="required" data-bind="value:view.name">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="controller.name">Controller Name</label>
                        </th>
                        <td>
                            <input placeholder="eg. MyItemsController" type="text" class="k-textbox" name="controller.name" required="required" data-bind="value:controller.name">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="model.name">Model Name</label>
                        </th>
                        <td>
                            <input placeholder="eg. MyItem" type="text" class="k-textbox" name="model.name" required="required" data-bind="value:model.name">
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="tableEdit">
                    <tr>
                        <th>
                            <label for="timestamps">timestamps (append created_at, updated_at columns as TIMESTAMP)</label>
                        </th>
                        <td>
                            <input type="checkbox" name="view.timestamps" data-bind="checked:view.timestamps">
                        </td>
                    </tr>
                </table>
                <div id="grid-fields"></div>
            </div>
            <div>
                <div id="grid-relations"></div>
            </div>
        </div>
    </div>
</div>
<script id="script-{{ $controllerName }}">
    app().controllers['{{ $controllerName }}'].run = function () {
        this.name = '{{ $controllerName }}';
        this.pageable = false;
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
                    fields: [],
                    timestamps: true
                }
            },
            view: {
                defaultValue: {
                    windowed: true,
                    name: "",
                    columns: []
                }
            },
            controller: {
                defaultValue: {
                    name: ""
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
            window: {
                height: 600
            },
            grid: {
                editable: {
                    window: {
                        width: "70%",
                        height: 500
                    }
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
                app().tabstrip(".tabstrip", container);
                var fieldsDataSource = new kendo.data.DataSource({
                    transport: {
                        read: function (options) {
                            options.success(model.model.fields);
                        },
                        create: function (options) {
                            console.log("create", options);
                            options.success();
                        },
                        update: function (options) {
                            console.log("update", options);
                            options.success();
                        },
                        destroy: function (options) {
                            console.log("destroy", options);
                            options.success(model.model.fields);
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
                                type: {
                                    defaultValue: "string",
                                    validation: {
                                        required: true
                                    }
                                },
                                input: {
                                    defaultValue: "text",
                                    validation: {
                                        required: true
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
                                primary: {
                                    type: "boolean",
                                    defaultValue: false
                                },
                                autoincrement: {
                                    type: "boolean",
                                    defaultValue: false
                                }
                            }
                        }
                    }
                });
                if (model.isNew()) {
                    fieldsDataSource.add({
                        name: "id",
                        type: "numeric",
                        input: null,
                        fillable: false,
                        required: false,
                        nullable: true,
                        primary: true,
                        autoincrement: true
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
                            field: "type",
                            title: "Type",
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
                        e.container.find("[name=input]")
                                .kendoDropDownList({
                                    valuePrimitive: true,
                                    dataSource: {
                                        data: ["text", "textarea", "date", "datetime", "number", "checkbox", "select", "chooser", "hidden"]
                                    }
                                });

                        e.container.find("[name=type]")
                                .kendoDropDownList({
                                    valuePrimitive: true,
                                    dataSource: {
                                        data: ["string", "numeric", "date", "boolean"]
                                    }
                                });

                    },
                    editable: {
                        mode: "popup",
                        template: $("#editFields-popup-template-{{ $controllerName }}").html()
                    }
                });
            }
        });

    };
</script>