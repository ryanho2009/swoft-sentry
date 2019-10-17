# swoft-sentry
Sentry log component for Swoft2.0

Usage：

install
```php
composer require rpr/sowft-sentry
```

sentry log definition
编辑 app/bean.php 添加如下内容定义
```php

    'sentryHandler' => [
        'class'     => \Gaodeng\SwoftSentry\SentryHandler::class,
        'dsn'       => 'YOUR SENTRY DSN',
        'formatter' => '${lineFormatter}',
        'levels'    => 'error,info', //2.0.1支持直接逗号分隔的字符串
    ],

    'logger' => [
        'flushRequest' => false,
        'enable'       => true,
        'json'         => true,
        'handlers'     => [
            '${sentryHandler}',   //添加handler
            //...  其他handler
        ],
    ],

```
