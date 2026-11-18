@props(['type','slug'])
@if(auth()->user()?->isStudent())
    @php $saved=auth()->user()->savedItems()->where('item_type',$type)->where('item_slug',$slug)->exists(); @endphp
    <form action="{{ route('student.saved.toggle') }}" method="POST" class="inline-flex">@csrf
        <input type="hidden" name="item_type" value="{{ $type }}"><input type="hidden" name="item_slug" value="{{ $slug }}">
        <button type="submit" aria-pressed="{{ $saved ? 'true' : 'false' }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-border-default px-3.5 py-2 text-xs font-semibold text-brand-700"><x-icon name="bookmark" class="size-4" />{{ $saved ? 'Remove from saved' : 'Save '.ucfirst($type) }}</button>
    </form>
@else
    <a href="{{ route('auth.login') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-border-default px-3.5 py-2 text-xs text-brand-700"><x-icon name="bookmark" class="size-4" />Sign in to save</a>
@endif
