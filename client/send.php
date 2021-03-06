<?php
//しかいぉしけいけいぉし
?>
<!DOCUMENT html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Sample of WebSocket</title>
        <script type="text/javascript" src="http://static.danawa.com/globaljs/external/jquery/core/last/jquery-last.min.js?v=1501301818"></script>

        <script type="text/javascript">

            var WSobj;

            // Set this to dump debug message from Flash to console.log:
            WEB_SOCKET_DEBUG = true;

            function init() {

                var appType;

                if (typeof MozWebSocket !== "undefined") {
                    appType = "Mozilla";
                } else if (window.WebSocket) {
                    appType = "Chrome";
                }

                //constant: see web_socket.
                // Connect to Web Socket.
                if (appType == "Mozilla") {
                    WSobj = new MozWebSocket("ws://192.168.56.101:2667/example");
                } else {
                    WSobj = new WebSocket("ws://192.168.56.101:2667/example");
                }

                //var CLOSED     = 3
                //var CLOSING    = 2
                //var CONNECTING = 0
                //var OPEN       = 1

                setTimeout(function () {
                    if(WSobj.readyState != 1){
                        alert('Your browser not support webSocket.');
                    }
                }, 2000);

                WSobj.onopen = function() {
                    output("onopen");

                    //send JSON to server
                    WSobj.send(JSON.stringify({
                        type:'join',
                        group_id:1
                    }));
                };

                WSobj.onmessage = function(e) {
                    // e.data contains received string.
                    output("onmessage: " + e.data);
                };
                WSobj.onclose = function() {
                    output("onclose");
                };
                WSobj.onerror = function() {
                    output("onerror");
                };

            }

            function onSubmit() {
                var input = document.getElementById("input");
                // You can send message to the Web Socket using WSobj.send.
                WSobj.send(JSON.stringify({
                    comment: input.value
                }));

                output("send: " + input.value);
                input.value = "";
                input.focus();
            }

            function onCloseClick() {
                WSobj.close();
            }

            function output(str) {
                var log = document.getElementById("log");
                var escaped = str.replace(/&/, "&amp;").replace(/</, "&lt;").
                    replace(/>/, "&gt;").replace(/"/, "&quot;"); // "
                log.innerHTML = escaped + "<br>" + log.innerHTML;
            }

        </script>
    </head>
    <body onload="init();">
        <form onsubmit="onSubmit(); return false;">
            <input type="text" id="input">
            <input type="submit" value="Send">
            <button onclick="onCloseClick(); return false;">close</button>
        </form>
        <div id="log"></div>
    </body>
</html>
