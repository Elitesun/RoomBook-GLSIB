<?php

return [
    // Local business hours used by reservation validation.
    'open_time' => env('BOOKING_OPEN_TIME', '08:00'),
    'close_time' => env('BOOKING_CLOSE_TIME', '20:00'),
];
