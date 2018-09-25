<?php return 'const { mix } = require(\'laravel-mix\');
require(\'laravel-mix-merge-manifest\');

mix.setPublicPath(\'../../public\').mergeManifest();

const RESOURCE_ROOT = __dirname + \'/resources/assets/\';

mix
    //-------- Add your module assets here

    //-------- Module assets -- do not remove or add anything past here
    .js(RESOURCE_ROOT + \'js/module/blog.js\', \'js/modules/blog\')
    .js(RESOURCE_ROOT + \'js/module/app.js\', \'js/modules/blog\');

if (mix.inProduction()) {
	mix.version();
} else {
    mix.sourceMaps();
}

mix.webpackConfig({
    resolve: {
        extensions: [\'.js\', \'.json\', \'.vue\'],
        alias: {
            \'~\': path.join(__dirname, \'../../resources/assets/js\')
        }
    }
});
';
