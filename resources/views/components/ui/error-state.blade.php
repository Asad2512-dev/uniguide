@props([
    'title' => 'Unable to load admission data',
    'message' => 'The National Higher Education Registry connection timed out. Please check your network or try again.',
    'code' => 'HEC_API_TIMEOUT_504',
    'retryAction' => null,
    'banner' => false,
])

@if($banner)
    <div class="rounded-xl border border-error-200 bg-error-50 p-4 flex items-start gap-3 text-xs font-sans">
        <x-icon name="alert-circle" class="w-4 h-4 text-error-600 shrink-0 mt-0.5" />
        <div class="flex-1">
            <h5 class="font-bold text-error-900">{{ $title }}</h5>
            <p class="text-error-700 mt-0.5">{{ $message }}</p>
        </div>
        @if($retryAction)
            <button type="button" @click="{{ $retryAction }}" class="font-semibold text-error-800 hover:underline">
                Retry
            </button>
        @endif
    </div>
@else
    <div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 md:p-12 text-center rounded-2xl border border-error-200/80 bg-error-50/30']) }}>
        <div class="w-12 h-12 rounded-2xl bg-error-100/80 text-error-600 flex items-center justify-center mb-4">
            <x-icon name="alert-triangle" class="w-6 h-6" />
        </div>

        <h3 class="font-heading font-bold text-base md:text-lg text-ink-950">
            {{ $title }}
        </h3>

        <p class="mt-1.5 text-xs md:text-sm text-ink-600 font-sans max-w-sm leading-relaxed">
            {{ $message }}
        </p>

        @if($code)
            <span class="mt-2 text-xs sm:text-[10px] font-mono text-ink-600 bg-surface px-2 py-0.5 rounded border border-border-default">
                Error Code: {{ $code }}
            </span>
        @endif

        <div class="mt-5 flex items-center gap-2.5">
            <x-ui.button variant="outline" size="sm" onclick="window.location.reload()">
                Reload Page
            </x-ui.button>
            @if($retryAction)
                <x-ui.button variant="primary" size="sm" @click="{{ $retryAction }}">
                    Retry Request
                </x-ui.button>
            @endif
        </div>
    </div>
@endif

