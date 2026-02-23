<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div
        class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-body"><i class="bi bi-trophy text-warning me-2"></i>Papan Ranking</h5>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="text-body-secondary small text-uppercase">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>User</th>
                        <th class="text-end">Total Skor</th>
                    </tr>
                </thead>
                <tbody class="text-body">
                    @forelse($topUsers as $index => $session)
                        <tr>
                            <td>
                                @if ($index == 0)
                                    <span class="badge bg-warning text-dark rounded-circle p-2"><i
                                            class="bi bi-award-fill"></i></span>
                                @elseif($index == 1)
                                    <span class="badge bg-secondary text-white rounded-circle p-2">2</span>
                                @elseif($index == 2)
                                    <span class="badge bg-opacity-50 bg-warning text-dark rounded-circle p-2">3</span>
                                @else
                                    <span class="ps-2">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $session->user->name }}</div>
                                <div class="small text-body-secondary">{{ $session->total_sessions }} Sesi</div>
                            </td>
                            <td class="text-end">
                                <span class="fw-bolder text-primary">{{ number_format($session->total_score) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-body-secondary py-3">Belum ada data ranking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
