<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Frame ancestors (embedding allowlist)
    |--------------------------------------------------------------------------
    |
    | Origins that are allowed to embed this site in an <iframe>. Leave empty
    | to keep the default hardened behaviour (X-Frame-Options: SAMEORIGIN),
    | which blocks all cross-origin framing.
    |
    | Set FRAME_ANCESTORS in your .env to a comma separated list of origins,
    | e.g. "https://portfolio.example.com,http://localhost:5175". When one or
    | more origins are present we drop X-Frame-Options (it cannot express an
    | allowlist and its ALLOW-FROM form is deprecated) and emit a CSP
    | frame-ancestors directive instead. 'self' is always included so the app
    | can still frame its own pages.
    |
    */

    'frame_ancestors' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('FRAME_ANCESTORS', '')),
    ))),

];
