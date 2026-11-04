@props([
    'events' => [
        ['title' => 'Admission Portal Opened', 'date' => '01 June 2026', 'description' => 'Online application submissions active for Fall 2026 cycle.', 'status' => 'completed', 'icon' => 'check'],
        ['title' => 'Entry Test (NET / NAT / ECAT)', 'date' => '12 July 2026', 'description' => 'Paper and computer-based national aptitude testing centers.', 'status' => 'current', 'icon' => 'clock'],
        ['title' => 'First Merit List Publication', 'date' => '01 August 2026', 'description' => 'Cutoff merit scores and initial fee challan generation.', 'status' => 'upcoming', 'icon' => 'list-ordered'],
        ['title' => 'Commencement of Classes', 'date' => '01 September 2026', 'description' => 'Orientation week and academic semester kickoff.', 'status' => 'upcoming', 'icon' => 'graduation-cap'],
    ],
])

<div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-border-default">
    @foreach($events as $event)
        @php
            $isCompleted = ($event['status'] ?? '') === 'completed';
            $isCurrent = ($event['status'] ?? '') === 'current';
        @endphp
        <div class="relative group">
            <!-- Timeline Pin -->
            <div class="absolute -left-6 top-1 flex items-center justify-center w-5 h-5 rounded-full text-xs transition-smooth 
                {{ $isCompleted ? 'bg-success-600 text-white' : ($isCurrent ? 'bg-brand-600 text-white ring-4 ring-brand-100' : 'bg-surface border-2 border-border-default text-ink-400') }}">
                @if(isset($event['icon']))
                    <x-icon :name="$event['icon']" class="w-3 h-3" />
                @else
                    <span class="w-1.5 h-1.5 rounded-full {{ $isCompleted || $isCurrent ? 'bg-white' : 'bg-ink-400' }}"></span>
                @endif
            </div>

            <!-- Content -->
            <div class="text-xs font-sans">
                <div class="flex items-center justify-between gap-2">
                    <h5 class="font-bold text-sm text-ink-950 font-heading">
                        {{ $event['title'] }}
                    </h5>
                    <time class="text-[11px] font-mono text-ink-500 font-medium shrink-0">
                        {{ $event['date'] }}
                    </time>
                </div>
                @if(isset($event['description']))
                    <p class="mt-1 text-ink-600 leading-relaxed">
                        {{ $event['description'] }}
                    </p>
                @endif
            </div>
        </div>
    @endforeach
</div>
