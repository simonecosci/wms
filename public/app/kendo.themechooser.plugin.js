(function($, window) {
    var kendo = window.kendo,
            sessionStorage = localStorage,
            supports = {
                sessionStorage: (function() {
                    // try-catch for obscure cases that do not allow "sessionStorage" in window
                    // also for Safari private mode
                    try {
                        sessionStorage.setItem("kendo-test", "success!");
                        sessionStorage.removeItem("kendo-test");
                        return !!sessionStorage.getItem;
                    } catch (e) {
                        return false;
                    }
                })(),
                pushState: ("pushState" in history)
            };


    var Widget = kendo.ui.Widget,
            ThemeChooser = Widget.extend({
                init: function(element, options) {
                    options = options || {};

                    if (supports.sessionStorage) {
                        options.theme = sessionStorage.getItem("kendoSkin");
                    }
                    options.theme = options.theme || ThemeChooser.prototype.options.theme;

                    Widget.prototype.init.call(this, element, options);

                    this._render();

                    this.setTheme(options.theme);

                    this.element.on("click touchend", ".tc-link", $.proxy(function(e) {
                        e.preventDefault();

                        var icon = $(e.target).closest(".tc-link").find(".k-icon"),
                                expand = icon.hasClass("k-i-arrow-s");

                        icon.toggleClass("k-i-arrow-s", !expand)
                                .toggleClass("k-i-arrow-n", expand);

                        this._getThemeContainer().animate({height: "toggle", margin: "toggle", paddingBottom: "toggle"}, "fast");
                    }, this));
                },
                reset: function() {
                    var icon = this.element.find(".tc-link .k-icon");

                    icon.removeClass("k-i-arrow-n").addClass("k-i-arrow-s");
                },
                _getThemeContainer: function() {
                    var themeChooser = this,
                            options = this.options,
                            container = $(options.listContainer).children(".tc-theme-container");

                    if (container.length) {
                        return container;
                    }

                    container = $("<ul class='tc-theme-container' />").prependTo(options.listContainer);

                    container.on("click", ".tc-link", function(e) {
                        e.preventDefault();

                        var link = $(this), theme = link.attr("data-value");

                        if (link.hasClass("active")) {
                            return;
                        }

                        container.find(".tc-link").removeClass("active");

                        link.addClass("active");

                        themeChooser.setTheme(theme);

                        app().ui.theme.changeTheme(theme, true);

                    });

                    container.html(kendo.render(options.itemTemplate, options.themes));

                    return container;
                },
                setTheme: function(themeName) {
                    var themes = this.options.themes,
                            theme;

                    for (var i = 0; i < themes.length; i++) {
                        if (themes[i].value == themeName) {
                            theme = themes[i];
                            theme.selected = true;
                        } else {
                            themes[i].selected = false;
                        }
                    }

                    this.element.find(".tc-theme-name").text(theme.text);

                    if (supports.sessionStorage) {
                        sessionStorage.setItem("kendoSkin", themeName);
                    }
                },
                _render: function() {
                    var label = this.options.label;

                    this.element
                            .addClass("k-theme-chooser")
                            .html(
                                    (label ? "<span class='tc-choose-theme'>" + label + "</span>" : "") +
                                    "<a class='tc-link k-state-selected' href='#'>" +
                                    "<span class='tc-theme-name'></span>" +
                                    "<span class='k-icon k-i-arrow-s'></span>" +
                                    "</a>"
                                    );
                },
                options: {
                    name: "ThemeChooser",
                    label: "Choose theme:",
                    theme: "silver",
                    listContainer: "#theme-list-container",
                    itemTemplate: kendo.template(
                            "<li class='tc-theme'>" +
                            "<a href='\\#' class='tc-link#= data.selected ? ' active' : '' #' data-value='#= data.value #'>" +
                            "<span class='k-content tc-theme-name'>#= data.text #</span>" +
                            "</a>" +
                            "</li>",
                            {useWithBlock: false}),
                    themes: [
                        {text: "Default", value: "default"},
                        {text: "Black", value: "black"},
                        {text: "Blue Opal", value: "blueopal"},
                        {text: "Bootstrap", value: "bootstrap"},
                        {text: "Flat", value: "flat"},
                        {text: "Fiori", value: "fiori"},
                        {text: "High Contrast", value: "highcontrast"},
                        {text: "Material", value: "material"},
                        {text: "Material Black", value: "materialblack"},
                        {text: "Metro", value: "metro"},
                        {text: "Metro Black", value: "metroblack"},
                        {text: "Moonlight", value: "moonlight"},
                        {text: "Nova", value: "nova"},
                        {text: "Office365", value: "office365"},
                        {text: "Silver", value: "silver"},
                        {text: "Uniform", value: "uniform"},
                    ]
                }
            });

    kendo.ui.plugin(ThemeChooser);



})(jQuery, window);