<?php

return [
    'default_down_payment' => 1_000_000,

    'default_down_payment_percentage' => 10,

    'tenor_options' => [3, 6, 12, 18, 24, 36],

    'late_fee_percentage_per_day' => 0.5,

    'margin' => [
        'default_percentage' => 6.5,
        'tenor_overrides' => [
            3 => 2.5,
            6 => 4.0,
            12 => 6.5,
            18 => 8.0,
            24 => 9.5,
            36 => 11.0,
        ],
    ],
];
