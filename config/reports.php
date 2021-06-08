<?php

use Otrium\Reports\BrandReport;
use Otrium\Reports\DailyReport;

return [
    'path' => dirname(__DIR__) . '/reports',

    'available_reports' => [
        'daily' => DailyReport::class,
        'brand' => BrandReport::class,
    ],
];
