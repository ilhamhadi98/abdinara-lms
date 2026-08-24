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
                <button type="button" 
                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 fw-bold btn btn-sm d-flex align-items-center gap-2 shadow-sm"
                        onclick="copyHeaderRoomCode('{{ $battle->room_code }}', this)"
                        title="Klik untuk salin kode room">
                    <span>Kode Room: <strong class="font-monospace fs-6 text-primary">{{ $battle->room_code }}</strong></span>
                    <i class="bi bi-copy fs-6 text-primary" id="headerCopyIcon"></i>
                </button>
            </div>
        </div>
    </x-slot>

    <script>
        function copyHeaderRoomCode(code, btn) {
            navigator.clipboard.writeText(code).then(() => {
                const icon = document.getElementById('headerCopyIcon');
                if (icon) {
                    icon.className = 'bi bi-check2-circle fs-6 text-success';
                    setTimeout(() => {
                        icon.className = 'bi bi-copy fs-6 text-primary';
                    }, 2000);
                }
            }).catch(() => {
                prompt('Salin kode room:', code);
            });
        }
    </script>

    <livewire:battle-arena :battle="$battle" />
</x-app-layout>