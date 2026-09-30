<?php

namespace App\MiniApps\NnvnApisGo;

class Constants
{
    const MAX_APIS = 50; // Max for round_apis only
    const TOTAL_ROUNDS = 5;
    const APIS_PER_SESSION = 5;
    const MAX_GIFT_APIS_GLOBAL = 200;
    const MAX_SESSIONS = 10; // ceil(50/5)
    const TIMER_SECONDS = 30;
    const MIN_ROUND_TIME = 2.0;
    const MAX_ROUND_TIME = 30.0;
}
