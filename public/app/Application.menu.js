[{"id":1,"menu_id":null,"name":"Options","index":0,"controller":null,"path":null,"callback":null,"icon":"gear","items":[{"id":3,"menu_id":1,"name":"Logout","index":0,"controller":"logout","path":null,"callback":"if (confirm(\"Do you really want to logout ?\")) window.location =\"\/logout\";","icon":"logout","items":[]},{"id":4,"menu_id":1,"name":"Settings","index":1,"controller":"settings","path":"admin\/settings","callback":null,"icon":"gears","items":[]},{"id":5,"menu_id":1,"name":"About","index":2,"controller":"about","path":"admin\/about","callback":null,"icon":"info","items":[]},{"id":6,"menu_id":1,"name":"Windows","index":3,"controller":null,"path":null,"callback":null,"icon":"window","items":[{"id":7,"menu_id":6,"name":"Select one","index":0,"controller":"windows-select-one","path":null,"callback":"var wins = app().ui.openedWindows();var dataSource = new kendo.data.DataSource();for (var i = 0; i < wins.length; i++) { dataSource.add({ title: wins[i].options.title, controller: wins[i].options.controllerName });}if ($(\"#wnd-manager\").length === 0) $(\"<div id='wnd-manager'\/>\").appendTo(\"#placeholder\").kendoWindow({ title: \"Opened windows\", modal: true, width: \"50%\", heigth: \"60%\", close: function () { this.destroy(); }, open: function () { var listView = $(\"<div\/>\").kendoListView({ dataSource: dataSource, template: \"<div title='#:title#' class='wnd-icon k-block k-pane k-widget k-button' onclick='app().controllers[\\\"#:controller#\\\"].window.toFront();$(this).closest(\\\"div.k-window\\\").find(\\\"[data-role=window]\\\").data(\\\"kendoWindow\\\").close();'>#:title.substr(0, 32)#<\/div>\"}).data(\"kendoListView\");this.element.empty().append(listView.element);}});var wnd = $(\"#wnd-manager\").data(\"kendoWindow\");wnd.center().open();","icon":"windows","items":[]},{"id":8,"menu_id":6,"name":"Close all","index":1,"controller":"windows-close-all","path":null,"callback":"var wins = app().ui.openedWindows();for (var i = 0; i < wins.length; i++) {wins[i].close();}","icon":"close-outline","items":[]}]}]},{"id":2,"menu_id":null,"name":"Application","index":1,"controller":null,"path":null,"callback":null,"icon":"window","items":[{"id":9,"menu_id":2,"name":"Users","index":0,"controller":"users","path":"admin\/users","callback":null,"icon":"user","items":[]},{"id":10,"menu_id":2,"name":"Menus","index":1,"controller":"menus","path":"admin\/menus","callback":null,"icon":"menu","items":[]},{"id":11,"menu_id":2,"name":"Files","index":2,"controller":"filesystem","path":"admin\/filesystem","callback":null,"icon":"document-manager","items":[]},{"id":12,"menu_id":2,"name":"MVC Elements","index":2,"controller":"mvc-elements","path":"admin\/mvc-elements","callback":null,"icon":"page-properties","items":[]}]}]