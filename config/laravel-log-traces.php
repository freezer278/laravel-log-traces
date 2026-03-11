<?php

return [
    'requests' => [
        'enabled' => true,
        'log_level' => 'debug',
        'skip_paths' => [
            'metrics',
        ],
    ],
    'commands' => [
        'enabled' => true,
        'log_level' => 'debug',
        'skip_commands' => [
            'schedule:run',
            'queue:work',
            'queue:listen',
            'horizon',
        ],
    ],
];
