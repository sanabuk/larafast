<?php
/**
 * Models with their foreignKeyNames and relatedKeyNames
 */
return [
    'driver'   => ['id'],
    'vehicle'  => ['id', 'driver_id'],
    'historic' => ['driver_id', 'vehicle_id'],
];
