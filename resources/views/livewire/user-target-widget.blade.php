<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 text-body">
        <h5 class="fw-bold mb-3">Target Personal</h5>

        @if ($currentTarget)
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-bold">Progres Target</span>
                    <span
                        class="small text-primary fw-bold">{{ $currentTarget->current_value }}/{{ $currentTarget->target_value }}</span>
                </div>
                <div class="progress rounded-pill" style="height: 10px;">
                    @php
                        $percent = ($currentTarget->current_value / $currentTarget->target_value) * 100;
                    @endphp
                    <div class="progress-bar bg-success rounded-pill" style="width: {{ $percent }}%"></div>
                </div>
                <small class="text-body-secondary mt-2 d-block small">Tipe:
                    {{ ucfirst($currentTarget->target_type) }}</small>
            </div>
        @endif

        <hr class="my-3 opacity-25">

        <form wire:submit.prevent="setTarget">
            <div class="mb-2">
                <label class="small fw-semibold mb-1">Set Target Mingguan</label>
                <select wire:model="target_type" class="form-select form-select-sm rounded-3">
                    <option value="tryout">Selesaikan Tryout</option>
                    <option value="module">Selesaikan Modul</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="number" wire:model="target_value" class="form-control form-control-sm rounded-3"
                    placeholder="Jumlah target">
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">Update Target</button>
        </form>
    </div>
</div>
