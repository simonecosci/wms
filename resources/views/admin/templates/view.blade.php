<div id="window-@{{ $controllerName }}">
    <div id="grid-@{{ $controllerName }}"></div>
    <div id="view-@{{ $controllerName }}"></div>
</div>
<div style="display: none" id="editForm-popup-template-@{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
        <div class="divEdit">
            <table class="tableEdit">
                <?php
                foreach ($lement->model->fields as $field) {
                    
                }
                ?>
            </table>
        </div>
    </div>
</div>
<script id="script-@{{ $controllerName }}">
    app().controllers['@{{ $controllerName }}'].run = function () {
        this.name = '@{{ $controllerName }}';
        this.pageable = true;
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
            email: {
                type: "string",
                validation: {
                    required: true
                }
            },
            password: {
                type: "string",
                validation: {
                    required: true
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
            }, {
                field: "email",
                title: "Email"
            }];

        this.createUI({
            title: "Users",
            fields: fields,
            columns: columns,
            onView: function (container, model) {
                app().tabstrip(".tabstrip", container);
                app().createWidgets($ctrl.name, container, model);
            },
            onEdit: function (container, model) {
                app().tabstrip(".tabstrip", container);
            }
        });

    };
</script>