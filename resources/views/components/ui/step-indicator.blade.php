@props([
    'steps' => [
        ['number' => 1, 'title' => 'Personal & FSc Marks', 'description' => 'Matric & Intermediate grades', 'status' => 'completed'],
        ['number' => 2, 'title' => 'Entry Test Score', 'description' => 'NET, ECAT, MDCAT or NTS', 'status' => 'current'],
        ['number' => 3, 'title' => 'Preferences', 'description' => 'Disciplines & Universities', 'status' => 'upcoming'],
        ['number' => 4, 'title' => 'Aggregate & Merit', 'description' => 'Final eligibility report', 'status' => 'upcoming'],
    ],
    'orientation' => 'horizontal', // horizontal, vertical
])

<nav aria-label="Progress" class="w-full">
    @if($orientation === 'horizontal')
        <ol class="flex items-center w-full">
            @foreach($steps as $step)
                @php
                    $isCompleted = $step['status'] === 'completed';
                    $isCurrent = $step['status'] === 'current';
                @endphp
                <li class="flex items-center {{ !$loop->last ? 'w-full' : '' }}">
                    <div class="flex items-center gap-2.5">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-semibold font-mono shrink-0 transition-smooth 
                            {{ $isCompleted ? 'bg-success-600 text-white' : ($isCurrent ? 'bg-brand-600 text-white ring-4 ring-brand-100' : 'bg-surface-subtle text-ink-500 border border-border-default') }}">
                            @if($isCompleted)
                                <x-icon name="check" class="w-4 h-4 text-white" />
                            @else
                                {{ $step['number'] }}
                            @endif
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-xs font-semibold font-sans {{ $isCurrent ? 'text-brand-700' : ($isCompleted ? 'text-ink-900' : 'text-ink-400') }}">
                                {{ $step['title'] }}
                            </p>
                            @if(isset($step['description']))
                                <p class="text-[10px] text-ink-500 font-sans">{{ $step['description'] }}</p>
                            @endif
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div class="flex-1 mx-3 h-0.5 {{ $isCompleted ? 'bg-success-500' : 'bg-border-default' }}"></div>
                    @endif
                </li>
            @endforeach
        </ol>
    @else
        <ol class="space-y-4">
            @foreach($steps as $step)
                @php
                    $isCompleted = $step['status'] === 'completed';
                    $isCurrent = $step['status'] === 'current';
                @endphp
                <li class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold font-mono shrink-0 
                        {{ $isCompleted ? 'bg-success-600 text-white' : ($isCurrent ? 'bg-brand-600 text-white ring-4 ring-brand-100' : 'bg-surface-subtle text-ink-500 border border-border-default') }}">
                        @if($isCompleted)
                            <x-icon name="check" class="w-3.5 h-3.5 text-white" />
                        @else
                            {{ $step['number'] }}
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold font-sans {{ $isCurrent ? 'text-brand-700' : ($isCompleted ? 'text-ink-900' : 'text-ink-400') }}">
                            {{ $step['title'] }}
                        </p>
                        @if(isset($step['description']))
                            <p class="text-[11px] text-ink-500 font-sans mt-0.5">{{ $step['description'] }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    @endif
</nav>
