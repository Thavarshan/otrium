<?php

return [
    'channel' => env('LOG_CHANNEL', 'otrium'),
    'path' => dirname(__DIR__) . '/logs/otrium.log',
    'level' => env('LOG_LEVEL', 'debug'),
];
