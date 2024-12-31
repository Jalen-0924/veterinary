<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $globals = [
        'before' => [],
        'after'  => [
            'alerts' => ['except' => ['auth/*']], // Add alerts filter
        ],
    ];

    public $methods = [];
    public $filters = [];
}
