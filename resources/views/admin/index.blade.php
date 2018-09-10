@extends('layouts.admin')

@section('content')
<div id="menu-sensor">&nbsp;</div>
<div id="menu-container"></div> 
<div id="placeholder"></div>
<div id="notification"></div>
<div id="shortcuts"></div>

<script type="text/javascript">

    function toggleFullScreen() {
        if (!document.fullscreenElement &&
                !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
            return true;
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
            return false;
        }
    }

    function onFullScreen(editor) {

        if (toggleFullScreen())
        {
            editor.wrapper.css({width: $("body").width(), height: $(document).height()});
        } else {
            editor.wrapper.css({width: 600, height: 400});
        }
    }

    $(document).ready(function () {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        try {
            app().Auth = {
                logout: function () {
                    window.location = '/logout';
                },
                Name: "{{ $user->name }}",
                Email: "{{ $user->email }}"
            };
            $(app()).on("ready", function () {

                var names = kendo.culture().calendars.standard.months.names;
                app().months = new kendo.data.DataSource();
                for (var i = 0; i < names.length; i++) {
                    app().months.add({
                        text: names[i],
                        value: i + 1
                    });
                }
                app().getMonth = function (index) {
                    var data = app().months.data();
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].value === parseInt(index))
                            return data[i].text;
                    }
                    return "";
                };

                $("<div>Logged as <b>" + app().Auth.Name + "</b> " + app().Auth.Email + " <a href=\"#\" id=\"logout-button\" title=\"Logout\"><span class=\"k-icon k-i-logout\"></span></a></div>")
                        .css({
                            display: "inline-block",
                            float: "right",
                            "padding-right": "5px",
                            "padding-top": "5px"
                        })
                        .appendTo("#menu");
                $("#menu #logout-button").on("click", function (e) {
                    e.preventDefault();
                    app().Confirm("Do you really want to log off ?", null, function () {
                        app().Auth.logout();
                    });
                });

            });
            app().storage = {
                data: {},
                setItem: function (key, value) {
                    this.data[key] = value;
                    var self = this;
                    return $.ajax({
                        url: "/admin/users/prefs",
                        type: "POST",
                        data: {prefs: kendo.stringify(this.data)},
                        dataType: "json",
                        success: function (data) {
                            self.data = data;
                        },
                        error: function (xhr, status, msg) {
                            console.log(xhr, status, msg);
                            app().Warning(xhr.responseText, msg);
                        }
                    });
                },
                getItem: function (key) {
                    return this.data[key];
                },
                clear: function () {
                    this.data = {};
                    var self = this;
                    return $.ajax({
                        url: "/admin/users/prefs",
                        type: "POST",
                        data: {prefs: kendo.stringify(this.data)},
                        dataType: "json",
                        success: function (data) {
                            self.data = data;
                        },
                        error: function (xhr, status, msg) {
                            console.log(xhr, status, msg);
                            app().Warning(xhr.responseText, msg);
                        }
                    });
                },
                load: function () {
                    return $.ajax({
                        url: "/admin/users/prefs",
                        dataType: "json",
                        success: function (data) {
                            app().storage.data = data;
                        },
                        error: function (xhr, status, msg) {
                            app().Warning(xhr.responseText, msg);
                        }
                    });
                }
            };
            app().storage.load().done(function () {
                app().init();
                app().run();
            });

        } catch (e) {
            console.log(e);
            alert(e.message);
        }
    });
</script>
@endsection
