<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>微信授权平台</title>
    <script src="http://libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
<img src="" class="scan" id="imgId">
</body>
<script type="text/javascript">
    wsServer = new WebSocket("wss://auth.icharle.com/websocket?site=5b8a3dfe6f32b");

    wsServer.onopen = function (evt) {
        //wsServer.readyState 属性：
        /*
         CONNECTING  0   The connection is not yet open.
         OPEN        1   The connection is open and ready to communicate.
         CLOSING     2   The connection is in the process of closing.
         CLOSED      3   The connection is closed or couldn't be opened.
         */
        if (wsServer.readyState == 1) {
            console.log("连接成功！");
        } else {
            console.log("连接失败！");
        }
    };

    wsServer.onmessage = function (evt) {
        let data = JSON.parse(evt.data);
        $("#imgId").attr('src',data.image);
        if (data.id) {
            alert('登录成功！')
        }
    };

    wsServer.onclose = function () {
        console.log("关闭连接！");
    };

    wsServer.onerror = function () {
        console.log("未知错误！");
    };
</script>
</html>