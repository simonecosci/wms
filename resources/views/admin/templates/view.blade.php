<?php if (!empty($element->view->windowed)) : ?>
<div id="window-@{{ $controllerName }}">
<?php endif; ?>
    <div id="grid-@{{ $controllerName }}"></div>
<?php if (!empty($element->view->windowed)) : ?>
    <div id="view-@{{ $controllerName }}"></div>
</div>
<?php endif; ?>
<div style="display: none" id="editForm-popup-template-@{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
<?php if (!empty($element->controller->nested)) : ?>
        <div id="tabstrip" class="tabstrip">
            <ul>
                <li class="k-state-active">Interface</li>
<?php foreach ($element->controller->nested as $nested) : ?>
                <li><?php echo $nested->controller; ?></li>
<?php endforeach; ?>
            </ul>
<?php endif; ?>
<?php if (!empty($element->controller->nested)) : ?>
            <div>
<?php endif; ?>
                
        <div class="divEdit">
            <table class="tableEdit">
<?php foreach ($element->model->fields as $field) : ?>
<?php if ($field->primary || !$field->fillable) continue; ?>
                <tr>
                    <th>
                        <label for="<?php echo $field->name; ?>"><?php echo $field->label; ?></label>
                    </th>
                    <td>
<?php switch ($field->input): case 'text': ?>
                        <input type="text" class="k-textbox" name="<?php echo $field->name; ?>" <?php echo $field->required ? '' : 'required="required" '; ?>data-bind="value:<?php echo $field->name; ?>">
<?php break; case 'textarea': ?>
                        <textarea class="k-textbox" name="<?php echo $field->name; ?>" <?php echo $field->required ? '' : 'required="required" '; ?>data-bind="value:<?php echo $field->name; ?>"></textarea>
<?php break; case 'editor': ?>
                        <textarea name="<?php echo $field->name; ?>" <?php echo $field->required ? '' : 'required="required" '; ?>data-bind="value:<?php echo $field->name; ?>"></textarea>
<?php break; case 'date': case 'datetime': case 'number': ?>
                        <input type="text" name="<?php echo $field->name; ?>" required="required" data-bind="value:<?php echo $field->name; ?>">
<?php break; case 'checkbox': ?>
                        <input type="checkbox" name="<?php echo $field->name; ?>" data-bind="checked:<?php echo $field->name; ?>">
<?php break; case 'select': ?>
                        <select class="k-textbox" name="<?php echo $field->name; ?>" <?php echo $field->required ? '' : 'required="required" '; ?>data-bind="value:<?php echo $field->name; ?>"></select>
<?php break; case 'chooser': ?>
                        @@include('chooser', ['field' => '<?php echo $field->name; ?>')
<?php break; case 'hidden': ?>
                        <input type="hidden" name="<?php echo $field->name; ?>" <?php echo $field->required ? '' : 'required="required" '; ?>data-bind="value:<?php echo $field->name; ?>">
<?php break; default: break; endswitch; ?>
                    </td>
                </tr>
<?php endforeach; ?>
            </table>
        </div>
                
<?php if (!empty($element->controller->nested)) : ?>
            </div>
<?php foreach ($element->controller->nested as $nested) : ?>
            <div>
                <div id="grid-<?php echo $nested->controller; ?>"></div>
            </div>
<?php endforeach; ?>
<?php endif; ?>
    </div>
</div>
<script id="script-@{{ $controllerName }}">
    app().controllers['@{{ $controllerName }}'].run = function () {
        this.name = '@{{ $controllerName }}';
        this.pageable = <?php echo $element->view->pageable ? 'true' : 'false' ?>;
<?php if ($element->view->pageable && $element->view->pageSize) : ?>
        this.pageSize = <?php echo $element->view->pageSize; ?>;
<?php endif; ?>
        var $ctrl = this;
        var fields = {
<?php foreach ($element->model->fields as $field) : ?>
            <?php echo $field->name ?>: {
                type: "<?php echo $field->vartype ?>",
                validation: {
                    required: <?php echo $field->required ? 'true' : 'false' ?>,
                    nullable: <?php echo $field->nullable ? 'true' : 'false' ?>
            
                }
<?php if ($field->input == 'select') : ?>
                ,select: {
                    controller: "<?php echo $field->inputOptions->controller ?>",
                    path: "<?php echo $field->inputOptions->path ?>",
                    valueField: "<?php echo $field->inputOptions->valueField ?>",
                    textField: "<?php echo $field->inputOptions->textField ?>"
                }
<?php endif; ?>
<?php if ($field->input == 'chooser') : ?>
                ,chooser: {
                    field: "<?php echo $field->name ?>",
                    controller: "<?php echo $field->inputOptions->controller ?>",
                    path: "<?php echo $field->inputOptions->path ?>",
                    display: <?php echo collect(explode(',', $field->inputOptions->display))->map(function ($name) { return sprintf('"%s"',trim($name)); })->toJson(); ?>
                }
<?php endif; ?>
            },
<?php endforeach; ?>
        };

        var columns = [
<?php foreach ($element->view->columns as $column) : ?>
            {
                field: "<?php echo $column->field; ?>",
                title: "<?php echo $column->title; ?>",
<?php if (!empty($column->width)): ?>
                width: "<?php echo $column->width; ?>",
<?php endif; ?>
<?php if (!empty($column->template)): ?>
                template: "<?php echo $column->template; ?>",
<?php endif; ?>
<?php if ($column->hidden): ?>
                hidden: true,
<?php endif; ?>
<?php if (!$column->filterable): ?>
                filterable: false,
<?php endif; ?>
<?php if (!$column->sortable): ?>
                sortable: false,
<?php endif; ?>
<?php if (!$column->columnMenu): ?>
                columnMenu: false,
<?php endif; ?>
<?php if (!$column->groupable): ?>
                groupable: false,
<?php endif; ?>
<?php if (!$column->resizable): ?>
                resizable: false,
<?php endif; ?>
            },
<?php endforeach; ?>
        ];

        this.createUI({
            title: "<?php echo $element->view->window->title; ?>",
            fields: fields,
            columns: columns,
            onView: function (container, model) {
                app().tabstrip(".tabstrip", container);
                app().createWidgets($ctrl.name, container, model);
<?php foreach ($element->model->fields as $field) : ?>
<?php if ($field->input == "editor"): ?>
                var text = container.find("[name=<?php echo $field->name ?>]");
                text.kendoEditor(app().ui.editor.EMPTY);
                $(text.data("kendoEditor").body).attr("contenteditable", false);    
<?php endif;?>
<?php if ($field->input == "date"): ?>
                container.find("[name=<?php echo $field->name ?>]").kendoDatePicker({
                    enable: false
                });
<?php endif;?>
<?php if ($field->input == "datetime"): ?>
                container.find("[name=<?php echo $field->name ?>]").kendoDateTimePicker({
                    enable: false
                });
<?php endif;?>
<?php if ($field->input == "number"): ?>
                container.find("[name=<?php echo $field->name ?>]").kendoDateTimePicker({
                    enable: false,
                    format: "n<?php echo is_numeric($field->decimals) ? $field->decimals : 0 ?>"
                });
<?php endif;?>
<?php endforeach; ?>
    
<?php foreach ($element->controller->nested as $nested) : ?>
                controller("<?php echo $nested->controller; ?>", function () {
                    var $ctrl = this;
                    var cols = this.columns.slice(0, -1); // remove the last col (actions) and use only the following  actions
                    cols.push({
                        command: [{
                                name: "view",
                                text: " ",
                                title: "Details",
                                width: "80px",
                                iconClass: "k-icon k-i-zoom",
                                click: function (e) {
                                    $ctrl.showDetails(e);
                                }
                            }]
                    });
                    this.dataSource.filter({field: "<?php echo $nested->foreign; ?>", operator: 'eq', value: model.id});
                    this.grid = this.createGrid({
                        container: container,
                        toolbar: false,
                        columns: cols,
                        editable: false
                    });
                });    
<?php endforeach; ?>
            },

            onEdit: function (container, model) {
                app().tabstrip(".tabstrip", container);
<?php foreach ($element->model->fields as $field) : ?>
<?php if ($field->input == "editor"): ?>
                container.find("[name=<?php echo $field->name ?>]").kendoEditor(app().ui.editor.FULL);
<?php endif;?>
<?php if ($field->input == "date"): ?>
                container.find("[name=<?php echo $field->name ?>]").kendoDatePicker();
<?php endif;?>
<?php if ($field->input == "datetime"): ?>
                container.find("[name=<?php echo $field->name ?>]").kendoDateTimePicker();
<?php endif;?>
<?php if ($field->input == "number"): ?>
                container.find("[name=<?php echo $field->name ?>]").kendoDateTimePicker({
                    format: "n<?php echo is_numeric($field->decimals) ? $field->decimals : 0 ?>"
                });
<?php endif;?>
<?php endforeach; ?>
    
<?php foreach ($element->controller->nested as $nested) : ?>
                controller("<?php echo $nested->controller; ?>", function () {
                    this.dataSource.filter({field: "<?php echo $nested->foreign; ?>", operator: 'eq', value: model.id});
                    this.grid = this.createGrid({
                        container: container,
                        toolbar: !model.isNew() && this.acl.create ? ["create"] : false,
                        columns: this.columns,
                        grid: {
                            edit: function (ev) {
                                ev.model.set("<?php echo $nested->foreign; ?>", model.id);
                                ev.model.set("<?php echo $element->name; ?>", model);
                                var title = !ev.model.isNew() ? "EDIT" : "NEW";
                                $(".k-window-title", ev.container.parent()).html(title);
                                app().onEdit("<?php echo $nested->controller; ?>", ev.container, ev.model);
                            },
                            editable: {
                                window: {
                                    maxHeight: 700,
                                    width: "80%"
                                }
                            }
                        }
                    });
                });
<?php endforeach; ?>
                
            }
        });

    };
</script>