<div id="window-about">
    <div class="k-block" style="padding: 10px; text-align: center;">
        <figure style="text-align: center"><img src="/images/logo_wms.svg"></figure>
        <h2>{{ config('app.description') }}</h2>
        <a href="https://github.com/simonecosci/wms" target="_blank">Visit the Application's page on Github</a>
        <h3>
            <b>Make a donation to support this project</b><br>
            <button type="button" class="k-button" style="background: silver; font-size: 120%; font-weight: bold;" onclick="window.open('https://www.paypal.me/simonecosci');">
                <img src="https://www.paypalobjects.com/webstatic/i/logo/rebrand/ppcom-white.svg" style="width: 136px; height: 43px">
            </button>
        </h3>
        <div class="k-content">
            PHP <span id="php-version"></span><br>
            Kendo <span id="kendo-version"></span><br>
            HOST IP <?php echo ($_SERVER['SERVER_ADDR'] ?? '') ?><br>
            CLIENT IP <?php echo ($_SERVER['REMOTE_ADDR'] ?? '') ?><br>
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
            height: "425px",
            width: "600px",
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
