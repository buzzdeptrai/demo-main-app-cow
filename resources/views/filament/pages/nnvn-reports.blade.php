<x-filament::page>
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-filament::card>
            <div class="text-sm text-gray-500">Total Games</div>
            <div class="text-2xl font-bold">{{ $summary['total_games'] }}</div>
        </x-filament::card>
        <x-filament::card>
            <div class="text-sm text-gray-500">Total Apis Found</div>
            <div class="text-2xl font-bold">{{ $summary['total_apis'] }}</div>
        </x-filament::card>
        <x-filament::card>
            <div class="text-sm text-gray-500">Total Box Clicks</div>
            <div class="text-2xl font-bold">{{ $summary['total_box_clicks'] }}</div>
        </x-filament::card>
        <x-filament::card>
            <div class="text-sm text-gray-500">Apis Found Clicks</div>
            <div class="text-2xl font-bold">{{ $summary['apis_clicks'] }}</div>
        </x-filament::card>
    </div>

    {{-- Gift Apis Status --}}
    <x-filament::card class="mb-6">
        <h2 class="text-lg font-semibold mb-3">Gift Apis (Limit {{ $summary['gift_limit'] }})</h2>
        <div class="flex items-center gap-6">
            <div>
                <span class="text-sm text-gray-500">Used:</span>
                <span class="text-xl font-bold text-purple-600">{{ $summary['gift_used'] }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-500">Remaining:</span>
                <span class="text-xl font-bold {{ $summary['gift_remaining'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $summary['gift_remaining'] }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-500">Limit:</span>
                <span class="text-xl font-bold">{{ $summary['gift_limit'] }}</span>
            </div>
        </div>
        <div class="mt-3 w-full bg-gray-200 rounded-full h-3">
            <div class="bg-purple-600 h-3 rounded-full" style="width: {{ min(100, ($summary['gift_used'] / $summary['gift_limit']) * 100) }}%"></div>
        </div>
        <div class="text-xs text-gray-400 mt-1">{{ round(($summary['gift_used'] / $summary['gift_limit']) * 100, 1) }}% used</div>
    </x-filament::card>

    {{-- Leaderboard --}}
    <x-filament::card>
        <h2 class="text-lg font-semibold mb-4">Leaderboard (Top 20)</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Rank</th>
                    <th class="text-left py-2">Name</th>
                    <th class="text-right py-2">Round APIs</th>
                    <th class="text-right py-2">Gift APIs</th>
                    <th class="text-right py-2">Total APIs</th>
                    <th class="text-right py-2">Best Time</th>
                    <th class="text-right py-2">Sessions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaderboard as $index => $player)
                <tr class="border-b {{ $index < 3 ? 'bg-yellow-50' : '' }}">
                    <td class="py-2 font-semibold">{{ $index + 1 }}</td>
                    <td class="py-2">{{ $player->name }}</td>
                    <td class="py-2 text-right">{{ $player->round_apis_found }}/{{ \App\MiniApps\NnvnApisGo\Constants::MAX_APIS }}</td>
                    <td class="py-2 text-right text-purple-600">{{ $player->gift_apis_found }}</td>
                    <td class="py-2 text-right font-bold">{{ $player->round_apis_found + $player->gift_apis_found }}</td>
                    <td class="py-2 text-right">{{ $player->best_total_time ? $player->best_total_time . 's' : '-' }}</td>
                    <td class="py-2 text-right">{{ $player->total_sessions }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-4 text-center text-gray-400">No data yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-filament::card>

    {{-- Box Click Report --}}
    <x-filament::card>
        <h2 class="text-lg font-semibold mb-4">Box Click Report</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Box</th>
                    <th class="text-left py-2">URL</th>
                    <th class="text-right py-2">Clicks</th>
                    <th class="text-right py-2">Apis Found</th>
                </tr>
            </thead>
            <tbody>
                @forelse($boxClicks as $box)
                <tr class="border-b">
                    <td class="py-2">Box {{ $box->box_index }}</td>
                    <td class="py-2 text-gray-500 truncate max-w-xs">{{ $box->url ?? 'Apis Box' }}</td>
                    <td class="py-2 text-right font-semibold">{{ $box->total_clicks }}</td>
                    <td class="py-2 text-right">{{ $box->apis_found }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-400">No data yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-filament::card>

    {{-- Quiz Report --}}
    <x-filament::card class="mt-6">
        <h2 class="text-lg font-semibold mb-4">Quiz Report</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Question</th>
                    <th class="text-right py-2">Answers</th>
                    <th class="text-right py-2">Correct</th>
                    <th class="text-right py-2">Wrong</th>
                    <th class="text-right py-2">Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizStats as $q)
                <tr class="border-b">
                    <td class="py-2">{{ Str::limit($q->question_vi, 60) }}</td>
                    <td class="py-2 text-right">{{ $q->total_answers }}</td>
                    <td class="py-2 text-right text-green-600">{{ $q->correct_count }}</td>
                    <td class="py-2 text-right text-red-600">{{ $q->wrong_count }}</td>
                    <td class="py-2 text-right font-semibold">{{ $q->correct_rate }}%</td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-4 text-center text-gray-400">No data yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-filament::card>
</x-filament::page>
