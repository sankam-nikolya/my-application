<?php

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        /** Dashboard */
        [
            'pattern' => '',
            'route' => 'site/index',
        ],

        /** Users module */
        [
            'pattern' => '<action:(login|logout|signup|request-password-reset|resend-verification-email)>',
            'route' => 'users/default/<action>',
        ],

        /** Purchases module */
        [
            'pattern' => 'purchases',
            'route' => 'purchases/default/index',
        ],
        [
            'pattern' => 'purchases/<id:\d+>',
            'route' => 'purchases/default/view',
        ],
        [
            'pattern' => 'my-purchases',
            'route' => 'purchases/customer-purchase/index',
        ],
        [
            'pattern' => 'my-purchases/<action:(view|update|create)>/<id:\d+>',
            'route' => 'purchases/customer-purchase/<action>',
        ],
        [
            'pattern' => 'my-purchases/create',
            'route' => 'purchases/customer-purchase/update',
        ],
    ],
];
