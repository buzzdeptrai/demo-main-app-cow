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
