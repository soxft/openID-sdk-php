# openIdSdk
 > sdk of x openId

# 使用方法

## 下载sdk

> 目前仅提供php sdk, 支持php 7+,php 8

- 使用Git: [https://github.com/soxft/openIdSdk/](https://github.com/soxft/openIdSdk/)
- 使用Composer: composer require soxft/openidsdk

## 使用sdk

在`v1 API`中, 您可以在您的网站上添加一个按钮或图标跳转至我们的授权接口获取`token`, 验证用户身份后, 将自动携带token跳转至您的回调地址.

对于使用 composer 引入`sdk`后, 我们可以尝试通过以下方法跳转至授权界面:

```php
<?php
    require_once "/path/to/vendor/autoload.php";
    
    use soxft\OpenIdSdk; //使用命名空间

    $xopenid = new OpenIdSdk('appid', 'app_secret');

    $xopenid->jump('redirect_uri'); //该方法将会直接跳转至授权界面, 不用让用户点击
?>
```
- 在上述代码中 `appid` 代表您的应用ID, `app_secret`代表您的应用密钥,`redirect_uri` 代表您的回调地址

接下来, 在您的业务代码中再次调用:
```php
<?php
    require_once "/path/to/vendor/autoload.php";
    
    use soxft\OpenIdSdk; //使用命名空间

    $xopenid = new OpenIdSdk('appid', 'app_secret');

    $res = $xopenid->getUserInfo($_GET['token'] ?? ""); // 如果token正确 该方法会返回用户信息

    print_r($res); //输出用户信息

    /** 用户登录逻辑代码 **/
?>
```
- 在上述的代码中 `token` 为您的回调端中 Query 参数中的token, 一般由授权端跳转得到

如果配置正确, 您将会得到用户的 `open_id` 以及 `unique_id`, 之后你可以继续处理您的登录逻辑.
