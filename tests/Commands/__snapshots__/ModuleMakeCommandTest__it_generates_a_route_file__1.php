<?php return '<?php

Route::prefix(\'blog\')
    ->namespace(\'Modules\\Blog\\Http\\Controllers\')
    ->middleware(\'web\')
    ->group(function () {
        Route::get(\'/\', \'BlogController@index\');
    });
';
