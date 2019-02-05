<?php
return [
    'p_admin' => [
        'type' => 2,
        'description' => 'Админ',
    ],
    'p_moderator' => [
        'type' => 2,
        'description' => 'Модератор',
    ],
    'user' => [
        'type' => 1,
        'description' => 'Пользователь',
        'ruleName' => 'userRole',
    ],
    'moderator' => [
        'type' => 1,
        'description' => 'Модератор',
        'ruleName' => 'userRole',
        'children' => [
            'user',
            'p_moderator',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Админ',
        'ruleName' => 'userRole',
        'children' => [
            'p_admin',
            'moderator',
        ],
    ],
];
