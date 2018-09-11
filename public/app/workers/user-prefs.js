
var DEBUG = false;

function load(url, options, callback) {
    if (DEBUG) {
        console.log("LOAD", [
            url, 
            (options && options.method === "POST") ? "POST" : "GET",
            (options && options.params) ? options.params : {}
        ]);
    }
    var xhr, options = options || {};
    try {
        if (typeof XMLHttpRequest !== 'undefined')
            xhr = new XMLHttpRequest();
        else {
            var versions = [
                "MSXML2.XmlHttp.5.0",
                "MSXML2.XmlHttp.4.0",
                "MSXML2.XmlHttp.3.0",
                "MSXML2.XmlHttp.2.0",
                "Microsoft.XmlHttp"
            ];
            for (var i = 0, len = versions.length; i < len; i++) {
                try {
                    xhr = new ActiveXObject(versions[i]);
                    break;
                } catch (ex) {
                    console.log("Exception: " + ex.message);
                }
            }
        }
        xhr.onreadystatechange = function () {
            if (xhr.readyState < 4) {
                return;
            }
            if (xhr.status !== 200) {
                return;
            }
            if (xhr.readyState === 4) {
                if (DEBUG) {
                    console.log("RESPONSE: " + xhr.readyState);
                }
                callback(xhr);
            }
        };
        var method = options.method || 'GET';
        xhr.open(method, url, true);
        if (DEBUG) {
            console.log("CALL: " + method, options, url);
        }
        if (options && options.method === "POST") {
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }
        if (options && options.headers) {
            for (var header in options.headers) {
                xhr.setRequestHeader(header, options.headers[header]);
            }
        }
        xhr.send(options.params || '');
    } catch (ex) {
        console.log("Exception: ", ex.message);
    }
}

self.addEventListener('message', function (e) {
    if (DEBUG) {
        console.log("message: " + e.data);
    }
    if (e.data === "load") {
        load("/admin/users/prefs", false, function (response) {
            var result = response.responseText;
            try {
                var object = JSON.parse(result);
                self.postMessage(JSON.stringify(object));
                if (DEBUG) {
                    console.log(JSON.stringify(object));
                }
            } catch (ex) {
                console.log("Exception: " + ex.message);
            }
        });
        return;
    }
    try {
        var object = JSON.parse(e.data);
        load("/admin/users/prefs", {
            method: "POST",
            params: "prefs=" + JSON.stringify(object.prefs),
            headers: object.headers
        }, function (response) {
            var object = JSON.parse(response.responseText);
            self.postMessage(JSON.stringify(object));
            if (DEBUG) {
                console.log("postMessage: " + JSON.stringify(object));
            }
        });
    } catch (ex) {
        console.log("Exception: " + ex.message);
    }
}, false);