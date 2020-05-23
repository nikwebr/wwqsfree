<?php

    return [
        /*
        |--------------------------------------------------------------------------
        | Adfly key.
        |--------------------------------------------------------------------------
        |
        | The Adfly API key must be set or the request will return an error.
        | Can be overwritten in the options array.
        |
        */
        'key'         => env('ADFLY_API_KEY'),

        /*
        |--------------------------------------------------------------------------
        | Adfly uid.
        |--------------------------------------------------------------------------
        |
        | The Adfly user must be set or the request will return an error.
        | Can be overwritten in the options array.
        |
        */
        'uid'         => env('ADFLY_UID'),

        /*
        |--------------------------------------------------------------------------
        | Adfly advert type.
        |--------------------------------------------------------------------------
        |
        | The Adfly advert type. Can be overwritten in the options array.
        |
        */
        'advert_type' => 'int',

        /*
        |--------------------------------------------------------------------------
        | Adfly domain.
        |--------------------------------------------------------------------------
        |
        | The Adfly domain, (adf.ly, q.gs or custom).
        | Can be overwritten in the options array.
        |
        */
        'domain'      => 'adf.ly',

        /*
        |--------------------------------------------------------------------------
        | Adfly folder.
        |--------------------------------------------------------------------------
        |
        | The Adfly default folder. Can be overwritten in the options array.
        |
        */
        'folder'      => 'Default'
    ];
