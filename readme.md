# 基于Laravel、Swoole实现微信小程序登录授权
>基于微信小程序登录授权机制延伸第三方平台使用(Websocket方式实现)

## 在线体验

[微信小程序登录平台](https://auth.icharle.com/)

## 环境依赖

* php>7.1
* swoole 
* redis

## 实现原理

>实现思路：借用微信小程序中个人开发者使用微信授权登录的功能，再结合微信小程序中B接口生成带参数的小程序码，用户只需扫描小程序码即可进入微信小程序中，再微信小程序中实现登录逻辑以及登录反馈信息。技术上PC端等待反馈登录逻辑信息采用Websocket方式主动推送消息，抛弃采用ajax轮询方式实现，大大提高性能。同时PC端Websocket会话ID及其对应的场景值采用Redis存储，减少数据库压力。

![机制原理思路](./docs/theory.png)

## 安装使用

```
# 从仓库中下载
$ git clone https://github.com/icharle/WechatAuthorization.git

# 进入代码根目录安装依赖
$ composer install

# copy .env文件
$ cp .env.example .env

# 配置数据库相关

# 生成项目key
$ php artisan key:generate

# 生成JWT key
$ php artisan jwt:secret

# 修改.env文件配置微信小程序appID && appSecret
$ WX_APPID = 
$ WX_SECRET = 

# 公开storage/public文件夹
$ php artisan storage:link

# 更改Websocket监听端口
$ 修改app/Console/Swoole.php中第66行，默认为9502端口

# 配置SSL证书，实现wss://xxx.xxx
$ 修改app/Console/Swoole.php中第68、69行，放置自己域名的证书
$ 可注释第67-70行，不配置SSL证书

# 启用Websocket并常驻内存(建议用Supervisor 守护进程)
$ php artisan swoole start
```