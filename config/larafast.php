<?php
/**
 * Models with their foreignKeyNames and relatedKeyNames
 */
return [
	'route' => 'lara',
	'models' => [
		'driver' => 'sanabuk\\driver\\models\\Driver',
		'vehicle' => 'sanabuk\\driver\\models\\Vehicle',
		'historic' => 'sanabuk\\driver\\models\\HistoryDriverVehicle',
		'user' => 'App\\User'
	],
    'driver'   => ['id'],
    'drivers'  => ['id','user_id'],
    'vehicle'  => ['id', 'driver_id'],
    'historic' => ['driver_id', 'vehicle_id'],
    'user' => ['id']
];
