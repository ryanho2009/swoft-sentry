# swoft-sentry
Sentry log component for Swoft2.0

Usage：

install
```php
composer require rpr/sowft-sentry
```

sentry log definition
编辑 config/beans/log.php 添加如下内容定义
```php

    'sentryHandler' => [
        'class'     => \Gaodeng\SwoftSentry\SentryHandler::class,
        'dsn' => 'YOUR SENTRY DSN',
        'formatter' => '${lineFormatter}',
        'levels'    => [
            \Swoft\Log\Logger::ERROR,
            \Swoft\Log\Logger::WARNING,
        ],
    ],

    'logger' => [
        'name'          => APP_NAME,
        'enable'        => true,
        'flushInterval' => 100,
        'flushRequest'  => true,
        'handlers'      => [
            '${sentryHandler}',   //添加handler
            //...  其他handler
        ],
    ],

```
