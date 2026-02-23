<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Tryout</h2>
            <a href="{{ route('admin.tryouts.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                + Buat Tryout
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Tryout</th>
                            <th class="px-4 py-3 text-center">Durasi</th>
                            <th class="px-4 py-3 text-center">Soal</th>
                            <th class="px-4 py-3 text-center">Peserta</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tryouts as $t)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $t->title }}</td>
                                <td class="px-4 py-3 text-center text-gray-500">{{ $t->duration_minutes }}m</td>
                                <td class="px-4 py-3 text-center text-gray-500">{{ $t->questions_count }}</td>
                                <td class="px-4 py-3 text-center text-gray-500">{{ $t->sessions_count }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $t->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $t->is_active ? 'Aktif' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right flex items-center justify-end gap-2">
                                    @can('publish tryout')
                                        <form method="POST" action="{{ route('admin.tryouts.publish', $t) }}">
                                            @csrf @method('PATCH')
                                            <button
                                                class="text-xs {{ $t->is_active ? 'text-gray-500 hover:text-red-600' : 'text-blue-600 hover:text-blue-800' }} transition">
                                                {{ $t->is_active ? 'Nonaktifkan' : 'Publish' }}
                                            </button>
                                        </form>
                                    @endcan
                                    @can('manage tryout')
                                        <form method="POST" action="{{ route('admin.tryouts.destroy', $t) }}"
                                            onsubmit="return confirm('Hapus tryout ini?')">
                                            @csrf @method('DELETE')
                                            <button
                                                class="text-xs text-red-500 hover:text-red-700 transition">Hapus</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Belum ada tryout.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $tryouts->links() }}</div>
        </div>
    </div>
</x-app-layout>
