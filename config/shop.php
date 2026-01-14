<?php

return [
    'currency' => 'FCFA',
    'delivery_fee' => (float) env('SHOP_DELIVERY_FEE', 1500),
    'free_delivery_threshold' => (float) env('SHOP_FREE_DELIVERY_THRESHOLD', 35000),
    'max_quantity' => (int) env('SHOP_MAX_QUANTITY', 12),
];
