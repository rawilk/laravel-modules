<?php return '{
    "name": "rawilk/blog",
    "description": "",
    "authors": [
        {
            "name": "Randall Wilk",
            "email": "randall@randallwilk.com"
        }
    ],
    "extra": {
        "laravel": {
            "providers": [
                "Modules\\\\Blog\\\\Providers\\\\BlogServiceProvider"
            ],
            "aliases": {

            }
        }
    },
    "autoload": {
        "psr-4": {
            "Modules\\\\Blog\\\\": ""
        }
    }
}';
