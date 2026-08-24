<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center py-2">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('battle.index') }}" class="text-decoration-none text-secondary">
                    <i class="bi bi-arrow-left"></i> Keluar
                </a>
                <h4 class="fw-bold text-body mb-0">⚔️ Arena Duel CAT 1 vs 1</h4>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-semibold">
                    Kode Room: {{ $battle->room_code }}
                </span>
            </div>
        </div>
    </x-slot>

    <livewire:battle-arena :battle="$battle" />
</x-app-layout>