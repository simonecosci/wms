<div id="window-filesystem">
    <div class="editForm-popup">
        <div id="tabstrip" class="tabstrip">
            <ul>
                <li class="k-state-active">Media</li>
                <li>Documents</li>
            </ul>
            <div>
                <div id="image-browser"></div>
                <div id="image-selection">
                    <table class="tableEdit">
                        <tr>
                            <th>
                                <label for="name">Name</label>
                            </th>
                            <td>
                                <input type="text" class="k-textbox" name="name" readonly="readonly" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="size">Size</label>
                            </th>
                            <td>
                                <input type="text" class="k-textbox" name="size" readonly="readonly" style="width: 100%">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div>
                <div id="file-browser"></div>
                <div id="file-selection">
                    <table class="tableEdit">
                        <tr>
                            <th>
                                <label for="name">Name</label>
                            </th>
                            <td>
                                <input type="text" class="k-textbox" name="name" readonly="readonly" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="size">Size</label>
                            </th>
                            <td>
                                <input type="text" class="k-textbox" name="size" readonly="readonly" style="width: 100%">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="script-filesystem">
    app().controllers.filesystem.run = function () {
        var $ctrl = this;
        $ctrl.name = "filesystem";
        $ctrl.window = $("#window-filesystem").kendoWindow({
            actions: ["Maximize", "Minimize", "Close"],
            height: "650px",
            width: "800px",
            title: "Filesystem",
            visible: false,
            resizable: false,
            resize: app().ui.state.windowResize,
            dragend: app().ui.state.windowMove,
            close: app().ui.state.windowClose,
            open: app().ui.state.windowOpen,
            deactivate: function (e) {
                this.destroy();
                setTimeout(function () {
                    delete app().controllers.filesystem;
                }, 50);
            },
            activate: function (e) {
                var self = this;
                app().tabstrip(".tabstrip", this.element);
                var imageBrowser = $("#image-browser", this.element).kendoImageBrowser({
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
                    },
                    path: "/"
                }).data("kendoImageBrowser");
                imageBrowser.dataSource.bind("requestEnd", function (e) {
                    if (e.type === "create" || e.type === "destroy") {
                        kendo.ui.progress(imageBrowser.element, false);
                        imageBrowser.dataSource.read();
                        return;
                    }
                    self.element.find("#image-selection [name=name]").val("");
                    self.element.find("#image-selection [name=size]").val("");
                });
                imageBrowser.upload.bind("upload", function (e) {
                    var xhr = e.XMLHttpRequest;
                    if (xhr) {
                        xhr.addEventListener("readystatechange", function (e) {
                            if (xhr.readyState === 1 /* OPENED */) {
                                xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                            }
                        });
                    }
                    kendo.ui.progress(imageBrowser.element, false);
                    imageBrowser.dataSource.read();
                });
                imageBrowser.bind("change", function (e) {
                    var selected = e.sender._selectedItem();
                    self.element.find("#image-selection [name=name]").val(selected.name);
                    self.element.find("#image-selection [name=size]").val(selected.size);
                });
                imageBrowser.bind("apply", function (e) {
                    var selected = e.sender._selectedItem();
                    var url = "/storage/" + imageBrowser._path + selected.name;
                    var img = $("<img/>").attr("src", url);
                    $("<div/>").html(img).kendoWindow({
                        actions: ["Close"],
                        title: url,
                        maxHeight: "80%",
                        close: function () {
                            this.destroy();
                        }
                    }).data("kendoWindow").center().open();
                });
                imageBrowser.element.find(".k-listview").css("overflow", "auto");

                var fileBrowser = $("#file-browser", this.element).kendoFileBrowser({
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
                    },
                    path: "/"
                }).data("kendoFileBrowser");
                fileBrowser.dataSource.bind("requestEnd", function (e) {
                    if (e.type === "create" || e.type === "destroy") {
                        kendo.ui.progress(fileBrowser.element, false);
                        fileBrowser.dataSource.read();
                        return;
                    }
                    self.element.find("#file-selection [name=name]").val("");
                    self.element.find("#file-selection [name=size]").val("");
                });
                fileBrowser.upload.bind("upload", function (e) {
                    var xhr = e.XMLHttpRequest;
                    if (xhr) {
                        xhr.addEventListener("readystatechange", function (e) {
                            if (xhr.readyState === 1 /* OPENED */) {
                                xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                            }
                        });
                    }
                    kendo.ui.progress(fileBrowser.element, false);
                    fileBrowser.dataSource.read();
                });
                fileBrowser.bind("change", function (e) {
                    var selected = e.sender._selectedItem();
                    self.element.find("#file-selection [name=name]").val(selected.name);
                    self.element.find("#file-selection [name=size]").val(selected.size);
                });
                fileBrowser.element.find(".k-listview").css("overflow", "auto");
            },
            controllerName: $ctrl.name
        }).data("kendoWindow");

        $ctrl.window.center();
    };
</script>