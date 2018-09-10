<div id="window-about">
    <div class="k-block" style="padding: 10px; text-align: center;">
        <h1>{{ config('app.name') }}</h1>
        <h2>{{ config('app.description') }}</h2>
        <div class="k-content">
            PHP <span id="php-version"></span><br>
            Kendo <span id="kendo-version"></span><br>
            HOST IP <?php echo $_SERVER['SERVER_ADDR']; ?><br>
            CLIENT IP <?php echo $_SERVER['REMOTE_ADDR']; ?><br>
            Path <?php echo @getcwd(); ?><br>
            Debug <?php echo config('app.debug') ? 'On' : 'Off'; ?><br>
        </div>
    </div>   
</div>
<script id="script-about">
    app().controllers.about.run = function () {
        this.name = "about";
        this.stackOnOpen = false;
        this.window = $("#window-about").kendoWindow({
            actions: ["Close"],
            height: "250px",
            width: "400px",
            title: "About",
            visible: false,
            modal: true,
            activate: function() {
                this.element.find("#php-version").text("{{ phpversion() }}");
                this.element.find("#kendo-version").text(kendo.version);
            },
            deactivate: function() {
                this.destroy();
                setTimeout(function () {
                    delete app().controllers.about;
                }, 50);
            }
        }).data("kendoWindow");
        this.window.center();
    };
</script>