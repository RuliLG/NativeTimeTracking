<div wire:poll.15s class="h-[calc(100vh_-_2rem)] overflow-hidden flex flex-col">
    <div class="flex items-end w-full">
        <h1 class="text-3xl font-bold">{{ $trackedToday }}</h1>
        <p class="ml-2 text-gray-500">Tracked today</p>
        <h1 class="text-3xl font-bold ml-auto text-gray-500">{{ $trackedThisWeek }}</h1>
        <p class="ml-2 text-gray-500">Tracked this week</p>
    </div>
    @if ($attendances->isEmpty())
        <p class="w-full mt-4">It's never too late to track your time :)</p>
    @else
        <ul class="space-y-1 mt-4 flex-1 overflow-auto">
            @foreach ($attendances as $attendance)
                <li class="bg-gray-900 p-2 rounded-md flex items-center">
                    <div class="flex-1">
                        <p class="font-bold text-gray-200 text-sm">
                            {{ $attendance->is_break ? 'Break' : ($attendance->is_holiday ? 'Holidays' : ($attendance->is_time_off ? 'Time off' : $attendance->project)) }}
                        </p>
                        @if (filled($attendance->description))
                            <p class="text-gray-400 text-xs">{{ str($attendance->description)->limit(40) }}</p>
                        @endif
                    </div>
                    <div class="flex-shrink-0 ml-4 font-bold text-gray-300 text-right">
                        {{ $attendance->formatted_duration }}
                        <p class="text-gray-300 text-xs font-normal">{{ $attendance->start_time->format('H:i') }} -
                            {{ $attendance->end_time->format('H:i') }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
