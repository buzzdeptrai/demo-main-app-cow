<?php

namespace App\MiniApps\NnvnApisGo;

class Constants
{
    const MAX_APIS = 90; // Total cap: round_apis_found + gift_apis_found
    const TOTAL_ROUNDS = 1;
    const APIS_PER_SESSION = 1;
    const MAX_GIFT_APIS_GLOBAL = 400;
    const MAX_SESSIONS = 90; // 90 total apis / 1 per game
    const TIMER_SECONDS = 30;
    const MIN_ROUND_TIME = 2.0;
    const MAX_ROUND_TIME = 30.0;

    const TOTAL_BOXES = 9;
    const BOX_APIS_COUNT = 1;
    const BOX_MESSAGE_COUNT = 1;
    const BOX_ECARD_COUNT = 2;

    // Paired messages (vi/en) — mỗi ô message hiển thị 1 câu khác nhau.
    const BOX_MESSAGES = [
        [
            'vi' => 'Cố lên! Giải Nhất đang nằm trong tầm tay bạn rồi!',
            'en' => 'Keep going! First Prize is within your reach!',
        ],
    ];

    // E-cards (vi/en) — mỗi ô ecard hiển thị 1 thiệp khác nhau.
    // FE dùng `key` để render HTML/ảnh tương ứng (10/10 vs 20/10).
    const BOX_ECARDS = [
        [
            'key' => 'digital_transformation', // 10/10
            'vi' => '10/10 là ngày Chuyển đổi số quốc gia của Việt Nam!',
            'en' => 'October 10 marks Vietnam\'s National Digital Transformation Day.',
        ],
        [
            'key' => 'women_day', // 20/10
            'vi' => '20/10 - Chúc mừng ngày Phụ nữ Việt Nam!',
            'en' => 'October 20 - Happy Vietnamese Women\'s Day!',
        ],
    ];
}
