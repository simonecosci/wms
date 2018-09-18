<div id="window-@{{ $controllerName }}">
    <div id="grid-@{{ $controllerName }}"></div>
    <div id="view-@{{ $controllerName }}"></div>
</div>
<div style="display: none" id="editForm-popup-template-@{{ $controllerName }}" type="x-kendo/template">
    <div class="editForm-popup">
        <div class="divEdit">
            <table class="tableEdit">
                <?php foreach ($lement->model->fields as $field) : ?>
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
    </div>
</div>
<script id="script-@{{ $controllerName }}">
    app().controllers['@{{ $controllerName }}'].run = function () {
        this.name = '@{{ $controllerName }}';
        this.pageable = <?php echo $element->view->pageable ? 'true' : 'false' ?>;
        <?php if ($element->view->pageable && $element->view->pageSize) : ?>
        this.pageSize = <?php echo $element->view->pageSize; ?>
        <?php endif; ?>
        var $ctrl = this;
        var fields = {
            <?php foreach ($lement->model->fields as $field) : ?>
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
            <?php foreach ($lement->view->columns as $column) : ?>
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
            },
            onEdit: function (container, model) {
                app().tabstrip(".tabstrip", container);
            }
        });

    };
</script>