<?php

return [
	'blog' => [
		'view0' => [
			'priority' => 1,
			'view'     => 'recipe::view.name.2'
		],
		'view1' => [
			'priority' => 0,
			'view'     => 'recipe::view.name'
		]
	],
	'other-module' => [
		'view1' => [
			'priority' => 0,
			'view'     => 'recipe::view.name'
		]
	]
];