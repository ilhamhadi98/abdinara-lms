<div class="card border-0 shadow-sm rounded-4 mb-5 activity-card">
    <div class="card-body p-4 p-md-5">
        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 text-body">
            <i class="bi bi-activity text-success"></i> Aktivitas Belajar Anda
        </h5>

        <div class="d-flex w-100 overflow-auto pb-3"
            style="scrollbar-width: thin; scrollbar-color: var(--activity-border) var(--activity-bg);">
            <!-- Penanda Kolom Hari -->
            <div class="d-flex flex-column justify-content-between me-3 text-body-secondary small pe-2 border-end border-secondary border-opacity-25"
                style="font-size: 0.75rem; min-width: 40px;">
                <span class="d-block" style="padding-top: 15px;">Mon</span>
                <span class="d-block" style="padding-top: 15px;">Wed</span>
                <span class="d-block" style="padding-top: 15px;">Fri</span>
                <span class="d-block"></span>
            </div>

            <!-- Map Grid -->
            <div
                style="display: grid; grid-template-rows: repeat(7, 1fr); grid-auto-flow: column; gap: 4px; padding-bottom: 5px;">
                @foreach ($days as $day)
                    @php
                        // Level colors using variables
                        $bg = "var(--activity-lvl-{$day['level']})";
                    @endphp

                    <div data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $day['count'] > 0 ? $day['count'] . ' aktivitas' : 'Tidak ada aktivitas' }} pada {{ $day['label'] }}"
                        style="width: 13px; height: 13px; border-radius: 3px; background-color: {{ $bg }}; outline: 1px solid rgba(255,255,255,0.05); cursor: pointer;">
                    </div>
                @endforeach
            </div>
        </div>

        <div
            class="d-flex justify-content-between align-items-center mt-3 border-top border-secondary border-opacity-25 pt-3">
            <span class="text-body-secondary small">
                <a href="#" class="text-decoration-none text-info">Pelajari bagaimana kami menghitung
                    aktivitas</a>
            </span>
            <div class="d-flex align-items-center small text-body-secondary gap-2">
                <span>Kurang</span>
                <div class="d-flex gap-1">
                    <div
                        style="width: 12px; height: 12px; border-radius: 2px; background-color: var(--activity-lvl-0);">
                    </div>
                    <div
                        style="width: 12px; height: 12px; border-radius: 2px; background-color: var(--activity-lvl-1);">
                    </div>
                    <div
                        style="width: 12px; height: 12px; border-radius: 2px; background-color: var(--activity-lvl-2);">
                    </div>
                    <div
                        style="width: 12px; height: 12px; border-radius: 2px; background-color: var(--activity-lvl-3);">
                    </div>
                    <div
                        style="width: 12px; height: 12px; border-radius: 2px; background-color: var(--activity-lvl-4);">
                    </div>
                </div>
                <span>Lebih</span>
            </div>
        </div>
    </div>

    <!-- Enable Tooltips Bootstrap -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl))
        });

        // Initial init in case Livewire isn't loaded dynamically yet but component renders
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl))
        });
    </script>
</div>
