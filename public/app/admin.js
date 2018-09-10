$(function () {
    $("[data-table]").each(function () {
        var table = $(this).attr("data-table");
        var field = $(this).attr("data-field");
        var id = $(this).attr("data-id");
        
        var model = {
            id: id
        };
        var save = {
            name: "save",
            tooltip: "Save",
            exec: function (e) {
                var editor = $(this).data("kendoEditor");
                model[field] = editor.value();
                $.ajax({
                    url: "/admin/" + table + "/update",
                    type: "POST",
                    dataType: "json",
                    data: {
                        models: kendo.stringify([model])
                    },
                    error: function (xhr, status, msg) {
                        console.log(xhr, status, msg);
                        alert(xhr.responseText);
                    },
                    success: function (data) {
                        alert("Update successfull");
                    }
                });
            }
        };
        
        var publish = {
            name: "custom",
            tooltip: "Publish",
            exec: function (e) {
                var editor = $(this).data("kendoEditor");
                model[field] = editor.value();
                $.ajax({
                    url: "/admin/" + table + "/publish",
                    type: "POST",
                    dataType: "json",
                    data: {
                        models: kendo.stringify([model])
                    },
                    error: function (xhr, status, msg) {
                        console.log(xhr, status, msg);
                        alert(xhr.responseText);
                    },
                    success: function (data) {
                        alert("Update successfull");
                    }
                });
            }
        };
        
        var config = {
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
                save
            ],
            stylesheets: [
                "/app/css/editor.css",
                "/app/css/pdf-export-styles.css"
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
        };
        if (table === "pages-languages") {
            config.tools.push(publish);
        }
        $(this).kendoEditor(config);
    });
});