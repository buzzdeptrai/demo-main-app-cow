<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bot / Scanner Blocking
    |--------------------------------------------------------------------------
    |
    | block_bots  : bật/tắt middleware BlockBots.
    | app_key     : shared key mà frontend phải gửi qua header X-App-Key.
    |               Để trống = bỏ qua lớp kiểm tra header (chỉ chặn User-Agent).
    |
    */

    'block_bots' => env('BLOCK_BOTS_ENABLED', true),

    'app_key' => env('NNVN_APP_KEY', ''),

];
