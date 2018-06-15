<?php return 'const { mix } = require(\'laravel-mix\');
require(\'laravel-mix-merge-manifest\');

mix.setPublicPath(\'../../public\').mergeManifest();

mix
	.js(__dirname + \'/resources/assets/js/app.js\', \'js/blog.js\');

if (mix.inProduction()) {
	mix.version();
}';
