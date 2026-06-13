<?php

return [
    'currency' => 'FCFA',
    'delivery_fee' => (float) env('SHOP_DELIVERY_FEE', 1000),
    'free_delivery_threshold' => (float) env('SHOP_FREE_DELIVERY_THRESHOLD', 10000),
    'max_quantity' => (int) env('SHOP_MAX_QUANTITY', 12),
];
