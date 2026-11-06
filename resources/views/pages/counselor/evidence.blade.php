<x-layouts.public title="University source evidence — UniGuide">
    <div class="px-4 py-8 sm:px-8">
        <x-ui.page-header title="University source evidence" description="Recorded official references, review status and verification dates. Source availability does not establish current fees, program accreditation or admission decisions.">
            <x-slot:actions><x-ui.button :href="route('counselor.chat')" variant="outline">Open counselor</x-ui.button></x-slot:actions>
        </x-ui.page-header>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($records as $record)
                <article class="rounded-2xl border border-border-default bg-white p-5">
                    <p class="text-xs font-semibold text-brand-700">{{ $record->university->name }}</p>
                    <h2 class="mt-2 font-heading text-base font-bold">{{ $record->dataSource->name }}</h2>
                    <p class="mt-3 text-sm text-ink-600">{{ str_replace('_', ' ', $record->evidence_type) }} · {{ str_replace('_', ' ', $record->verification_status) }}</p>
                    <p class="mt-2 text-xs text-ink-500">Last checked: {{ $record->dataSource->verified_at?->toDateString() ?? 'Not recorded' }}</p>
                    @if(!$record->dataSource->verified_at || $record->dataSource->verified_at->lt(now()->subMonths(6)))<p class="mt-2 text-sm text-warning-700">Freshness review needed.</p>@endif
                    @if(app(\App\Services\RegistryCounselorResponder::class)->safeUrl($record->dataSource->source_url))
                        <a href="{{ $record->dataSource->source_url }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex min-h-11 items-center text-sm font-semibold text-brand-700">Open official reference →</a>
                    @endif
                </article>
            @empty
                <x-ui.empty-state title="No source evidence recorded yet" description="Records will appear here after an official-source import has been reviewed." />
            @endforelse
        </div>
        <div class="mt-6">{{ $records->links() }}</div>
    </div>
</x-layouts.public>
