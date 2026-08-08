@props([
    'title' => 'Are you sure?',
    'confirmText' => 'Confirm',
    'confirmClass' => 'bg-gray-900 hover:bg-gray-700',
    'action' => null,
    'method' => 'POST',
])

<div x-data="{ open: false }" class="inline-block">
    <div x-on:click="open = true" class="inline-block cursor-pointer">
        {{ $trigger }}
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/40" x-on:click="open = false"></div>

        <div class="relative bg-white rounded-lg shadow-lg ring-1 ring-gray-200 max-w-sm w-full p-6">
            <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
            <div class="mt-2 text-sm text-gray-600">
                {{ $slot }}
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="open = false" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Cancel
                </button>

                @if ($action)
                    <form method="POST" action="{{ $action }}">
                        @csrf
                        @if (strtoupper($method) !== 'POST')
                            @method($method)
                        @endif
                        <button type="submit" class="rounded-md px-3 py-2 text-sm font-medium text-white {{ $confirmClass }}">
                            {{ $confirmText }}
                        </button>
                    </form>
                @else
                    <button type="button" x-on:click="open = false" class="rounded-md px-3 py-2 text-sm font-medium text-white {{ $confirmClass }}">
                        {{ $confirmText }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
