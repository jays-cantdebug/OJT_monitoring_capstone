@props(['log'])

<li class="py-3">
    <p class="text-sm text-black">
        <span class="font-medium">{{ $log->actor->name }}</span>
        {{ str($log->action->label())->lower() }}
        @if ($log->actor->id !== $log->subject->id)
            &mdash; <span class="font-medium">{{ $log->subject->name }}</span>
        @endif
    </p>
    @if (!empty($log->changes))
        <ul class="mt-1 space-y-0.5 text-xs text-black/60">
            @foreach ($log->changes as $field => $change)
                <li>
                    <span class="font-medium">{{ str($field)->replace('_', ' ')->title() }}:</span>
                    "{{ is_bool($change['from']) ? ($change['from'] ? 'Verified' : 'Unverified') : ($change['from'] ?? 'none') }}"
                    &rarr;
                    "{{ is_bool($change['to']) ? ($change['to'] ? 'Verified' : 'Unverified') : $change['to'] }}"
                </li>
            @endforeach
        </ul>
    @endif
    <p class="mt-1 text-xs text-black/40">{{ $log->created_at->format('M j, Y g:i A') }}</p>
</li>
