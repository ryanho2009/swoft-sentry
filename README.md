# swoft-sentry
Sentry log component for Swoft

Usage：

install
```php
composer require gaodeng/sowft-sentry
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
            '${applicationSentryHandler}',   //添加handler
            //...  其他handler
        ],
    ],

```