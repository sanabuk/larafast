<?php
/**
 * Models with their foreignKeyNames and relatedKeyNames
 */
return [
	'models' => [
		'driver' => 'sanabuk\\driver\\models\\Driver',
		'vehicle' => 'sanabuk\\driver\\models\\Vehicle'
	],
    'driver'   => ['id'],
    'vehicle'  => ['id', 'driver_id'],
    'historic' => ['driver_id', 'vehicle_id'],
];
