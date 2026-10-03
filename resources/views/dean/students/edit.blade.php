@extends('layouts.dean')

@section('title', 'Edit Student Intern')

@section('content')
    <div class="mb-4 flex items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-pencil-square class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-navy">Edit Student Intern</h2>
                <p class="text-sm text-black/60">{{ $student->email }}</p>
            </div>
        </div>
        <a href="{{ route('dean.students.show', $student) }}" class="text-xs font-medium text-navy hover:underline shrink-0">
            &larr; Back to Student Intern Details
        </a>
    </div>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
        <form method="POST" action="{{ route('dean.students.update', $student) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wide text-black/60">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $student->name) }}"
                    required
                    class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                >
                @error('name')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <p class="block text-xs font-bold uppercase tracking-wide text-black/60 mb-1.5">Last Updated</p>
                <p class="text-sm text-black/60">{{ $profile->updated_at?->format('M j, Y g:i A') ?? 'Never' }}</p>
            </div>

            <div>
                <span class="block text-xs font-bold uppercase tracking-wide text-black/60 mb-1.5">Verification Status</span>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_verified"
                        value="1"
                        @checked(old('is_verified', $profile->is_verified))
                        class="rounded border-light-gray text-navy shadow-sm focus:ring-navy/40"
                    >
                    <span class="text-sm text-black">Mark as Verified</span>
                </label>
                <p class="mt-1 text-xs text-black/40">No workflow is attached to this yet &mdash; it's a simple status marker.</p>
            </div>

            <div
                id="geofence"
                class="border-t border-light-gray pt-5"
                x-data="geofencePicker({{ Illuminate\Support\Js::from([
                    'latitude' => old('company_latitude', $geofence['latitude'] ?? null),
                    'longitude' => old('company_longitude', $geofence['longitude'] ?? null),
                    'radius' => (int) old('geofence_radius_m', $geofenceRadius),
                ]) }}, {{ Illuminate\Support\Js::from($recentTimeIns) }})"
            >
                <span class="block text-xs font-bold uppercase tracking-wide text-black/60">Company Location (Geofence)</span>
                <p class="mt-1 text-xs text-black/40">
                    Click the map to pin where {{ $student->name }} works, then drag the pin to adjust.
                    Readings outside the circle are logged for your review &mdash; the student is never blocked from timing in or out.
                </p>
                <p class="mt-1 text-xs text-black/60">
                    Student-entered address: {{ $profile->company_address ?: 'not provided' }}
                </p>

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    @if ($profile->company_address)
                        <button
                            type="button"
                            @click="findAddress({{ Illuminate\Support\Js::from($profile->company_address) }})"
                            :disabled="searching"
                            class="inline-flex items-center gap-1.5 rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-navy ring-1 ring-light-gray hover:bg-light-gray/40 disabled:opacity-60"
                        >
                            <x-heroicon-o-magnifying-glass class="h-4 w-4" />
                            <span x-text="searching ? 'Searching…' : 'Find address on map'"></span>
                        </button>
                    @endif
                    <button
                        type="button"
                        x-show="hasPin"
                        @click="clearPin()"
                        class="inline-flex items-center gap-1.5 rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-danger ring-1 ring-light-gray hover:bg-danger/5"
                    >
                        <x-heroicon-o-x-mark class="h-4 w-4" />
                        Remove pin
                    </button>
                </div>
                <p x-show="searchMessage" x-cloak class="mt-2 text-xs text-black/60" x-text="searchMessage"></p>

                <div class="relative mt-3 h-72 rounded-lg bg-light-gray overflow-hidden" x-ref="map"></div>
                @if ($recentTimeIns->isNotEmpty())
                    <p class="mt-1.5 text-[11px] text-black/40">Grey dots: this student's last {{ $recentTimeIns->count() }} Time In locations.</p>
                @endif

                <input type="hidden" name="company_latitude" :value="latitude ?? ''">
                <input type="hidden" name="company_longitude" :value="longitude ?? ''">
                @error('company_latitude')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror

                <div class="mt-4">
                    <label for="geofence_radius_m" class="block text-xs font-bold uppercase tracking-wide text-black/60">
                        Radius: <span x-text="radius"></span> m
                    </label>
                    <input
                        type="range"
                        id="geofence_radius_m"
                        name="geofence_radius_m"
                        min="{{ App\Support\Geofence::MIN_RADIUS_M }}"
                        max="{{ App\Support\Geofence::MAX_RADIUS_M }}"
                        step="10"
                        x-model.number="radius"
                        class="mt-2 block w-full accent-navy"
                    >
                    <div class="mt-1 flex justify-between text-[11px] text-black/40">
                        <span>{{ App\Support\Geofence::MIN_RADIUS_M }} m</span>
                        <span>{{ number_format(App\Support\Geofence::MAX_RADIUS_M / 1000) }} km</span>
                    </div>
                    @error('geofence_radius_m')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 justify-center rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gold/90"
                >
                    <x-heroicon-o-check class="h-4 w-4" />
                    Save Changes
                </button>
                <a href="{{ route('dean.students.show', $student) }}" class="text-sm font-medium text-black/60 hover:underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
