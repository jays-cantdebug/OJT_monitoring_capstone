@props([
    'title' => 'Are you sure?',
    'confirmText' => 'Confirm',
    'confirmClass' => 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600',
    'icon' => null,
    'iconClass' => 'bg-amber-100 text-amber-600',
    'action' => null,
    'method' => 'POST',
])

<div x-data="{ open: false }" class="inline-block">
    <div x-on:click="open = true" class="inline-block cursor-pointer">
        {{ $trigger }}
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/40 backdrop-blur-sm"
            x-on:click="open = false"
        ></div>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-xl shadow-xl ring-1 ring-gray-100 max-w-sm w-full p-6"
        >
            @if ($icon)
                <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full {{ $iconClass }}">
                    <x-dynamic-component :component="'heroicon-o-'.$icon" class="h-5 w-5" />
                </div>
            @endif

            <h3 class="font-bold text-gray-900">{{ $title }}</h3>
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
                        <button type="submit" class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm {{ $confirmClass }}">
                            {{ $confirmText }}
                        </button>
                    </form>
                @else
                    <button type="button" x-on:click="open = false" class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm {{ $confirmClass }}">
                        {{ $confirmText }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
