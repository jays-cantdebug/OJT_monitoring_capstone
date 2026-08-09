@extends('layouts.dean')

@section('title', 'Live Map')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                <x-heroicon-o-map-pin class="h-5 w-5" />
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">OJT Compliance Map</p>
                <h2 class="flex items-center gap-2 font-bold text-gray-900">
                    Live Student Location Map
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                </h2>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-4 mb-6 flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <x-heroicon-o-magnifying-glass class="h-4 w-4" />
            </span>
            <input type="text" placeholder="Search student or ID..." class="w-full rounded-md border-0 bg-[#eef2f7] py-2.5 pl-9 pr-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40">
        </div>
        <button type="button" class="inline-flex items-center gap-1.5 rounded-md bg-[#eef2f7] px-3.5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-200">
            <x-heroicon-o-funnel class="h-4 w-4" />
            Filters
        </button>
        <button type="button" class="flex h-9 w-9 items-center justify-center rounded-md bg-[#eef2f7] text-gray-500 hover:bg-gray-200">
            <x-heroicon-o-paper-airplane class="h-4 w-4" />
        </button>
        <button type="button" class="flex h-9 w-9 items-center justify-center rounded-md bg-[#eef2f7] text-gray-500 hover:bg-gray-200">
            <x-heroicon-o-arrow-path class="h-4 w-4" />
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wide text-gray-900">Intern List (2)</h3>
            <span class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">MySQL Dynamic View</span>
        </div>

        <ul class="divide-y divide-gray-100 mb-6">
            <li class="py-3 flex items-center justify-between text-sm">
                <span class="text-gray-900">Juan Dela Cruz</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                    Since 8:02 AM
                </span>
            </li>
            <li class="py-3 flex items-center justify-between text-sm">
                <span class="text-gray-900">Maria Reyes</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                    Since 8:04 AM
                </span>
            </li>
        </ul>

        <div class="relative h-96 rounded-lg bg-[#e8ecef] overflow-hidden">
            <div class="absolute inset-0" style="background-image: linear-gradient(#d7dde1 1px, transparent 1px), linear-gradient(90deg, #d7dde1 1px, transparent 1px); background-size: 32px 32px;"></div>
            <div class="absolute inset-0 flex items-center justify-center text-center px-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Map will render here</p>
                    <p class="mt-1 text-xs text-gray-400">Waiting on Maps API key provisioning (open question) and Reverb broadcasting (Step 6)</p>
                </div>
            </div>
            <div class="absolute bottom-4 right-4 flex flex-col rounded-md bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
                <button type="button" class="flex h-8 w-8 items-center justify-center text-gray-600 hover:bg-gray-50 border-b border-gray-100">+</button>
                <button type="button" class="flex h-8 w-8 items-center justify-center text-gray-600 hover:bg-gray-50">−</button>
            </div>
        </div>
    </div>
@endsection
