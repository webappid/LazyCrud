<?php
return [
    'inject' => [
        'controller' => [
            'user_id' => [
                'method' => 'Auth::user()->id',
                'use' => 'use Illuminate\Support\Facades\Auth;'
            ]
        ],
        'route' => env('INJECT_ROUTE', 'web.php')
    ]
];
