<div id="window-{{ $controllerName }}">
    <div id="splitter1">
        <div>
            <div id="splitter2">
                <div style="padding: 5px; overflow: auto; height: 100%;">
                    <div id="tree-{{ $controllerName }}"></div>
                </div>
                <div style="overflow: auto; height: 100%;">
                    <div id="view-{{ $controllerName }}" style="display: none;">
                        <div class="divEdit">
                            <table class="tableEdit">
                                <tr>
                                    <th>
                                        <label for="name">Name</label>
                                    </th>
                                    <td>
                                        <input type="text" class="k-textbox" name="name" required="required" data-bind="value:name">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="controller">Controller</label>
                                    </th>
                                    <td>
                                        <input type="text" class="k-textbox" name="controller" data-bind="value:controller">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="path">Path</label>
                                    </th>
                                    <td>
                                        <input type="text" class="k-textbox" name="path" data-bind="value:path">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="icon">Icon</label>
                                    </th>
                                    <td>
                                        <input type="text" class="k-textbox" name="icon" data-bind="value:icon">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="callback">Callback</label>
                                    </th>
                                    <td>
                                        <textarea name="callback" data-bind="value:callback"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 5px; text-align: right" class="k-block">
            <button id="button-reload"></button>
            <button id="button-new"></button>
            <button id="button-save"></button>
            <button id="button-destroy"></button>
            <button id="button-publish"></button>
        </div>
    </div>
</div>
<script id="script-{{ $controllerName }}">
    app().controllers['{{ $controllerName }}'].run = function () {
        var $ctrl = this;
        var expanded = [];

        this.name = '{{ $controllerName }}';

        this.save = function () {
            var data = this.tree.dataSource.view().toJSON();
            $.ajax({
                url: app().service.url + "/" + $ctrl.path + "/update",
                dataType: "json",
                type: "POST",
                data: {models: kendo.stringify(data)},
                success: function (data) {
                    app().notification.show("Menu saved", "success");
                    $ctrl.read();
                }
            });
        };

        this.destroy = function (items) {
            if (!confirm("Do you really wand to delete this entry and all its children ?"))
                return;
            $.ajax({
                url: app().service.url + "/" + $ctrl.path + "/destroy",
                dataType: "json",
                type: "POST",
                data: {models: kendo.stringify(items)},
                success: function (data) {
                    app().notification.show("Item deleted", "success");
                    $ctrl.read();
                }
            });
        };

        this.publish = function () {
            if (!confirm("Do you really wand to publish the menu ?"))
                return;
            $.ajax({
                url: app().service.url + "/" + $ctrl.path + "/publish",
                dataType: "json",
                type: "POST",
                success: function (data) {
                    app().notification.show("Menu published", "success");
                    $ctrl.read();
                }
            });
        };

        this.read = function () {
            $ctrl.template.hide();
            $.ajax({
                url: app().service.url + "/" + $ctrl.path + "/read",
                dataType: "json",
                success: function (data) {
                    $ctrl.tree.dataSource.data(data);
                    $ctrl.tree.expandPath(expanded);
                }
            });
        };

        this.getNode = function (id, items) {
            var data = items || this.tree.dataSource.data();
            for (var i = 0; i < data.length; i++) {
                if (data[i].id === id)
                    return data[i];
                var found = this.getNode(id, data[i].items);
                if (found)
                    return found;
            }
        };

        var window = {
            actions: ["Maximize", "Minimize", "Close"],
            height: "600px",
            width: "70%",
            title: "Admin Menu",
            visible: false,
            resize: app().ui.state.windowResize,
            dragend: app().ui.state.windowMove,
            close: app().ui.state.windowClose,
            open: app().ui.state.windowOpen,
            controllerName: this.name,
            deactivate: function (e) {
                this.destroy();
                setTimeout(function () {
                    delete app().controllers[$ctrl.name];
                }, 50);
            },
            activate: function (e) {
                $("#splitter1", this.element).kendoSplitter({
                    orientation: "vertical",
                    panes: [
                        {collapsible: false, resizable: false},
                        {collapsible: false, resizable: false, size: "50px"}
                    ]
                });
                $("#splitter2", this.element).kendoSplitter({
                    panes: [
                        {size: "250px"}
                    ]
                });
                $ctrl.read();
            }
        };
        this.window = $("#window-" + this.name).kendoWindow(window).data("kendoWindow");

        this.template = $("#view-" + this.name);

        var tree = {
            autoBind: false,
            dataTextField: "name",
            dragAndDrop: true,
            loadOnDemand: false,
            select: function (e) {
                var model = this.dataItem(e.node);
                kendo.bind($ctrl.template, model);
                $ctrl.template.show();
            },
            expand: function (e) {
                var index = expanded.indexOf(this.dataItem(e.node).id);
                if (index < 0)
                    expanded.push(this.dataItem(e.node).id);
            },
            collapse: function (e) {
                var index = expanded.indexOf(this.dataItem(e.node).id);
                expanded.splice(index, 1);
            }
        };
        this.tree = $("#tree-" + this.name, this.window.element).kendoTreeView(tree).data("kendoTreeView");

        $("#button-reload", this.window.element)
                .text(" " + kendo.ui.Grid.prototype.options.messages.commands.update)
                .kendoButton({
                    icon: "refresh",
                    click: function () {
                        $ctrl.tree.dataSource.cancelChanges();
                        $ctrl.read();
                    }
                });

        $("#button-save", this.window.element)
                .text(" " + kendo.ui.Grid.prototype.options.messages.commands.save)
                .kendoButton({
                    icon: "save",
                    click: function () {
                        $ctrl.save();
                    }
                });

        $("#button-new", this.window.element)
                .text(" " + kendo.ui.Grid.prototype.options.messages.commands.create)
                .kendoButton({
                    icon: "file-add",
                    click: function () {
                        var item = $ctrl.tree.dataSource.add({
                            menu_id: null,
                            name: "new menu item",
                            visible: false,
                            items: []
                        });
                        $ctrl.tree.select($ctrl.tree.items().last());
                        kendo.bind($ctrl.template, item);
                        $ctrl.template.show();
                    }
                });

        $("#button-destroy", this.window.element)
                .text(" " + kendo.ui.Grid.prototype.options.messages.commands.destroy)
                .kendoButton({
                    icon: "cancel",
                    click: function () {
                        var data = $ctrl.tree.select();
                        if (!data)
                            return;
                        var item = $ctrl.tree.dataItem(data[0]);
                        function removeExpanded(node) {
                            var index = expanded.indexOf(node.id);
                            if (index >= 0)
                                expanded.splice(index, 1);
                            for (var i = 0; i < node.items.length; i++) {
                                removeExpanded(node.items[i]);
                            }
                        }
                        removeExpanded(item);
                        $ctrl.destroy([item]);
                    }
                });

        $("#button-publish", this.window.element)
                .text(" Publish")
                .kendoButton({
                    icon: "cancel",
                    click: function () {
                        $ctrl.publish();
                    }
                });

    };
</script>