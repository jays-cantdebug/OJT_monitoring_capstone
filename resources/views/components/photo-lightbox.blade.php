{{-- Expects a `lightboxUrl` property on the enclosing Alpine scope (set to
     a photo URL to open, null to close) - Alpine directives here bind to
     whatever ancestor x-data provides it, regardless of this component's
     own file boundary. --}}
<div
    x-show="lightboxUrl"
    x-cloak
    @keydown.escape.window="lightboxUrl = null"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div
        x-show="lightboxUrl"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="lightboxUrl = null"
    ></div>

    <button
        type="button"
        @click="lightboxUrl = null"
        class="absolute top-4 right-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
        aria-label="Close"
    >
        <x-heroicon-o-x-mark class="h-6 w-6" />
    </button>

    <img
        x-show="lightboxUrl"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        :src="lightboxUrl"
        class="relative max-h-full max-w-full rounded-lg object-contain"
        alt="Photo evidence"
    >
</div>
