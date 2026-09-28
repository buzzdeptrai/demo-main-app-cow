<?php

namespace App\MiniApps\NnvnApisGo;

class Constants
{
    const MAX_APIS = 50;
    const TOTAL_ROUNDS = 2;
    const APIS_PER_SESSION = 3; // 2 rounds + 1 gift
    const MAX_SESSIONS = 17; // ceil(50/3)
    const MAX_GAMES_PER_DAY = 5;
    const TIMER_SECONDS = 30;
    const MIN_ROUND_TIME = 2.0;
    const MAX_ROUND_TIME = 30.0;
}
