<?php

namespace App\MiniApps\NnvnApisGo;

class Constants
{
    const MAX_APIS = 90; // Total cap: round_apis_found + gift_apis_found
    const TOTAL_ROUNDS = 1;
    const APIS_PER_SESSION = 1;
    const MAX_GIFT_APIS_GLOBAL = 200;
    const MAX_SESSIONS = 90; // 90 total apis / 1 per game
    const TIMER_SECONDS = 30;
    const MIN_ROUND_TIME = 2.0;
    const MAX_ROUND_TIME = 30.0;

    const TOTAL_BOXES = 8;
    const BOX_APIS_COUNT = 2;
    const BOX_MESSAGE_COUNT = 1;

    const BOX_MESSAGES = [
        'Chúc bạn may mắn lần sau!',
        'Chúc mừng ngày Phụ nữ Việt Nam 20/10! 💐',
        'Hãy thử lại nhé, Apis đang ẩn nấp đâu đó!',
    ];

    const BOX_MESSAGES_EN = [
        'Better luck next time!',
        'Happy Vietnamese Women\'s Day 20/10! 💐',
        'Try again, Apis is hiding somewhere!',
    ];
}
